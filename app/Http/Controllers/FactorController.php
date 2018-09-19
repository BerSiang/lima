<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\PlaceRepository;
use App\Model\ExternalData\TaipeiLand;
use App\Model\ExternalData\TaipeiLandValue;
use Log;

class FactorController extends Controller
{
    private $placeRepo;

    public function __construct(PlaceRepository $repo) {
        $this->placeRepo = $repo;
    }
    public function factors(Request $req)
    {
        //$query = json_decode($req->query);
        //return $req['xy'][0];
        //$query['xy'] = [121.5014057, 25.0310886];
        $query['xy'] = [(float)$req['x'], (float)$req['y']];
        $query['config'] = json_decode($req['config']);
        
        return $this->summary($query['xy'], $query['config']);
 
    }

    public function factor($factorName, $initData, $factorWeight, $xy) {
        $title = $initData['title'];
        $weightList = $initData['weight'];
        $distance = $initData['distance'];
        $maxList = $initData['maxList'];

        $detailList = [];
        $i = 0;
        for($i = 0; $i < count($title); $i++) {
            $detailList[$i] = $this->placeRepo->inDistance($title[$i], $xy, $distance[$i]);
        }

        $details = [];
        $summaryScore = 0;
        $totalWeight = 0;
        for($i = 0; $i < count($title); $i++) {
            $num = 0;
            if(isset($detailList[$i])) {
                $num = count($detailList[$i]);
            }
            $score = $num >= $maxList[$i] ? 100 : $num / $maxList[$i] * 100;
            $summaryScore += $score * $weightList[$i];
            $totalWeight += $weightList[$i];

            $detail = array(
                'title' => $title[$i],
                'score' => $score,
                'weight' => $weightList[$i],
                'effectiveDistance' => $distance[$i],
                'count' => $num,
                'features' => $detailList[$i]->all()
            );

            array_push($details, $detail);
        }
        $summaryScore /= $totalWeight;

        $factor = array(
            'name' => $factorName['zh'],
            'enName' => $factorName['en'],
            'score' => $summaryScore,
            'weight' => $factorWeight,
            'details' => $details
        );

        return $factor;
    }

    public function summary($xy, $config) {
        //dd($config->safety);
        //Log::debug($config->safety);
        $safety = [
            'title' => ['警察局', '消防局', '消防栓'],
            'weight' => [1, 1, 1],
            'distance' => [0.5, 1, 0.02],
            'maxList' => [5, 5, 5]
        ];
        //$safety = $config->safety;
        $traffic = [
            'title' => ['公車站', '捷運站'],
            'weight' => [1, 1],
            'distance' => [0.3, 0.5],
            'maxList' => [10, 10]
        ];
        $livelihood = [
            'title' => ['便利商店', '中華郵政', '銀行', '傳統市場'],
            'weight' => [1, 1, 1, 1],
            'distance' => [0.1, 0.5, 0.5, 1],
            'maxList' => [10, 5, 5, 3]
        ];
        $medical = [
            'title' => ['藥局', '醫療單位'],
            'weight' => [1, 1],
            'distance' => [0.3, 0.5],
            'maxList' => [10, 10],
        ];
        $leisure = [
            'title' => ['河濱公園', '電影院'],
            'weight' => [1, 1],
            'distance' => [2, 1],
            'maxList' => [10, 10],
        ];
        $nimby = [
            'title' => ['殯葬業者', '加油站', '機場', '工廠', '垃圾焚化廠', '廢棄物處理廠', '煤氣行', '廟宇'],
            'weight' => [1, 1, 1, 1, 1, 1, 1, 1],
            'distance' => [0.5, 0.5, 5, 1, 5, 3, 0.5, 0.5],
            'maxList' => [5, 3, 2, 5, 1, 1, 5, 5],
        ];
        $humanities = [
            'title' => ['圖書館', '博物館', '宗教單位', '學校'],
            'weight' => [1, 1, 1, 1],
            'distance' => [0.5, 3, 1, 1],
            'maxList' => [3, 5, 3, 4],
        ];
        $factors[0] = $this->factor(['zh' => '安全', 'en' => 'safety'], $safety, $config->safety->weight, $xy);
        $factors[1] = $this->factor(['zh' => '交通', 'en' => 'traffic'], $traffic, $config->traffic->weight, $xy);
        $factors[2] = $this->factor(['zh' => '民生', 'en' => 'livelihood'], $livelihood, $config->livelihood->weight, $xy);
        $factors[3] = $this->factor(['zh' => '醫療', 'en' => 'medical'], $medical, $config->medical->weight, $xy);
        $factors[4] = $this->factor(['zh' => '休閒', 'en' => 'leisure'], $leisure, $config->leisure->weight, $xy);
        $factors[5] = $this->factor(['zh' => '嫌惡設施', 'en' => 'nimby'], $nimby, -$config->nimby->weight, $xy);
        $factors[6] = $this->factor(['zh' => '人文', 'en' => 'humanities'], $humanities, $config->humanities->weight, $xy);

        $score = 0;
        $weight = 0;
        foreach($factors as $factor) {
            $score += $factor['score'] * $factor['weight'];
            $weight += $factor['weight'];
        }
        $score /= $weight;

        $pracel = TaipeiLand::where('geometry', 'geoIntersects', [
                        '$geometry' => [
                            'type' => 'Point',
                            'coordinates' => $xy
                        ]
                    ])
                    ->first();
        $pracel_id = sprintf("%08d", $pracel->properties['pracel_id']);
        //dd($pracel_id);
        $tpeValue = TaipeiLandValue::where('pracel_id', $pracel_id)->where('pracellary', $pracel->properties['段名'])->first();
        $tpeValue['landCurrentValue'] = $tpeValue['公告土地現值'];
        $tpeValue['declaredValue'] = $tpeValue['公告地價'];
        unset($tpeValue['公告土地現值']);
        unset($tpeValue['公告地價']);
        //dd($tpeValue);
        $summary = array(
            'name' => '綜合指標',
            'enName' => 'summary',
            'score' => $score,
            'factors' => $factors,
            'pracel' => $tpeValue
        );
        return $summary;
    }
}
