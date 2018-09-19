<?php

namespace App\Console\Commands\Sync;

use Illuminate\Console\Command;

use GuzzleHttp\Client;
use Http\Adapter\Guzzle6\Client as AdapterClient;

use Geocoder\Query\GeocodeQuery;
use Geocoder\Query\ReverseQuery;
use Geocoder\Provider\GoogleMaps\GoogleMaps;
use Geocoder\StatefulGeocoder;
use GeoJson\Feature\Feature;
use GeoJson\Geometry\Point;

use proj4php\Proj4php;
use proj4php\Proj;
use proj4php\Point as Proj4Point;

use App\Model\ExternalData\SourceInfo;
use App\Model\ExternalData\Place;
use DB;

class ConvenienceStore extends Command
{
	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'sync:cvnstore';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Command description';

	protected $geocoder;

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function handle()
	{
		$client = new AdapterClient();
		$geoProvider = new GoogleMaps($client , '', env('GOOGLE_MAP_API_KEY'));
		$this->geocoder = new StatefulGeocoder($geoProvider);


		/*$family = $db->table('family')->get()->each(function($item) {
			return $item['店名'] == null || $item['地址'] == null;
		});
		$featureCollection = $family->map(function($item) {
			$result = $this->toXY($item['地址'])->first();
			$point = new Point($result->getCoordinates()->toArray());
			$properties = [
				'title' => '便利商店',
				'source_id' => null,
				'name' => $item['店名'],
				'common_name' => ['全家', 'Family', 'Family Mart'],
				'store_number' => $item['店號'],
				'address' => $item['地址'],
			];
			$properties['formatted_address'] = $result->getFormattedAddress();
			$properties['geocode_result'] = $result->toArray();
			$feature = new Feature($point, $properties);
			Place::create($feature->jsonSerialize());
		});
        //dd($family);
         */
		/*$busStop = $db->table('busStop')->get()->reject(function($item) {
			return $item['lon'] == null || $item['lat'] == null;
		});
		$busStop->each(function($item) {
			$point = new Point([(float)$item['lon'], (float)$item['lat']]);
			$properties = [
				'title' => '公車站',
				'source_id' => null,
				'name' => $item['name'],
				'address' => $item['address'],
			];
			$feature = new Feature($point, $properties);
			Place::create($feature->jsonSerialize());
        });*/
        /*$policeStation = $db->table('policeStation')->whereNotNull('緯度')->whereNotNull('經度')->whereNotNull('單位名稱')->whereNotNull('地址')->get();
        $policeStation->each(function($item) {
            $coor = $this->TWD97toWGS84((float)$item['經度'], (float)$item['緯度']);
			$point = new Point([$coor[0], $coor[1]]);
			$properties = [
				'title' => '警察局',
				'source_id' => null,
				'name' => $item['單位名稱'],
                'address' => $item['地址'],
                'telephone' => $item['電話']
			];
			$feature = new Feature($point, $properties);
			Place::create($feature->jsonSerialize());
        });*/

        /*$postStation = $db->table('postStation')->whereNotNull('緯度')->whereNotNull('經度')->whereNotNull('地址')->get();
        $postStation->each(function($item) {
			$point = new Point([(float)$item['經度'], (float)$item['緯度']]);
			$properties = [
				'title' => '中華郵政',
				'source_id' => null,
				'name' => $item['局名'],
                'address' => '臺北市' . $item['鄉鎮市區'] . $item['地址'],
			];
            $feature = new Feature($point, $properties);
			Place::create($feature->jsonSerialize());
        });*/

        /*$riverPark = $db->table('riverPark')->whereNotNull('緯度')->whereNotNull('經度')->whereNotNull('名稱')->get();
        $riverPark->each(function($item) {
			$point = new Point([(float)$item['緯度'], (float)$item['經度']]);   //原始資料放反了
			$properties = [
				'title' => '河濱公園',
				'source_id' => null,
				'name' => $item['名稱'],
                'river_name' => $item['河流']
			];
            $feature = new Feature($point, $properties);
            //dd($feature->jsonSerialize());
			Place::create($feature->jsonSerialize());
        });*/

        /*$this->fetchFromLife('GAS', 'name', 'addr_x', 'addr_y')
            ->each(function($item) {
                $xy = $this->TWD97toWGS84($item['addr_x'], $item['addr_y']);
                $point = new Point([$xy[0], $xy[1]]);

			    $properties = [
				    'title' => '加油站',
				    'source_id' => null,
                    'name' => $item['name'] . $item['s_name'],
                    'address' => $item['cityzone'] . $item['address']
			    ];
                $feature = new Feature($point, $properties);
                //dd($feature->jsonSerialize());
                Place::create($feature->jsonSerialize());
            });*/

        /*$name = 'properties.landmarkna';
        $x = 'geometry.coordinates[0]';
        $y = 'geometry.coordinates[1]';
        $this->fetchFromLife('MRT', 'properties.landmarkna', 'geometry.coordinates', 'geometry.coordinates')
            ->each(function($item) {
            	$xy = $this->TWD97toWGS84($item['geometry']['coordinates'][0], $item['geometry']['coordinates'][1]);
                $point = new Point([$xy[0], $xy[1]]);

			    $properties = [
				    'title' => '捷運站',
				    'source_id' => null,
                    'name' => $item['properties']['landmarkna'],
                    'address' => $item['properties']['address']
			    ];
                $feature = new Feature($point, $properties);
                //dd($feature->jsonSerialize());
                Place::create($feature->jsonSerialize());
            });*/

        /*$name = 'properties.landmarkna';
        $x = 'geometry.coordinates[0]';
        $y = 'geometry.coordinates[1]';
        $this->fetchFromLife('airport', 'properties.landmarkna', 'geometry.coordinates', 'geometry.coordinates')
            ->each(function($item) {
                $point = new Point([$item['geometry']['coordinates'][0], $item['geometry']['coordinates'][1]]);

			    $properties = [
				    'title' => '機場',
				    'source_id' => null,
                    'name' => $item['properties']['landmarkna'],
                    'address' => $item['properties']['address']
			    ];
                $feature = new Feature($point, $properties);
                //dd($feature->jsonSerialize());
                Place::create($feature->jsonSerialize());
            });*/
        /*$name = '機構名稱';
        $x = '經度';
        $y = '緯度';
        $this->fetchFromLife('bank', $name, $x, $y)
            ->each(function($item) use ($name, $x, $y) {
                $point = new Point([(float)$item[$x], (float)$item[$y]]);

			    $properties = [
				    'title' => '銀行',
				    'source_id' => null,
                    'name' => $item[$name]
			    ];
                $feature = new Feature($point, $properties);
                //dd($feature->jsonSerialize());
                Place::create($feature->jsonSerialize());
            });*/
        /*$name = '名稱';
        $x = '經度';
        $y = '緯度';
        $this->fetchFromLife('drugStore', $name, $name, $name)
            ->each(function($item) use ($name, $x, $y) {
                $result = $this->toXY('台北市' . $item['鄉鎮'] . $item['地址'])->first();
				$point = new Point($result->getCoordinates()->toArray());

			    $properties = [
				    'title' => '藥局',
				    'source_id' => null,
                    'name' => $item[$name]
			    ];
			    $properties['formatted_address'] = $result->getFormattedAddress();
				$properties['geocode_result'] = $result->toArray();
                $feature = new Feature($point, $properties);
                //dd($feature->jsonSerialize());
                Place::create($feature->jsonSerialize());
            });

        $name = '名稱';
        $x = '經度';
        $y = '緯度';
        $this->fetchFromLife('factory', $name, $name, $name)
            ->each(function($item) use ($name, $x, $y) {
                $xy = $this->TWD97toWGS84($item[$x], $item[$y]);
                $point = new Point([$xy[0], $xy[1]]);

			    $properties = [
				    'title' => '工廠',
				    'source_id' => null,
                    'name' => $item[$name],
                    'address' => $item['地址']
			    ];
                $feature = new Feature($point, $properties);
                //dd($feature->jsonSerialize());
                Place::create($feature->jsonSerialize());
            });*/

        /*$name = '單位名';
        $x = '經度';
        $y = '緯度';
        $this->fetchFromLife('fireDepart', $name, $x, $y)
            ->each(function($item) use ($name, $x, $y) {
                $xy = $this->TWD97toWGS84($item[$x], $item[$y]);
                $point = new Point([$xy[0], $xy[1]]);

			    $properties = [
				    'title' => '消防局',
				    'source_id' => null,
                    'name' => $item[$name]
			    ];
                $feature = new Feature($point, $properties);
                //dd($feature->jsonSerialize());
                Place::create($feature->jsonSerialize());
            });

        $x = '經度';
        $y = '緯度';
        $this->fetchFromLife('fireDepart', $x, $x, $y)
            ->each(function($item) use ($name, $x, $y) {
                $xy = $this->TWD97toWGS84($item[$x], $item[$y]);
                $point = new Point([$xy[0], $xy[1]]);

			    $properties = [
				    'title' => '消防栓',
				    'source_id' => null,
			    ];
                $feature = new Feature($point, $properties);
                //dd($feature->jsonSerialize());
                Place::create($feature->jsonSerialize());
            });

        $name = '名稱';
        $this->fetchFromLife('firebang', $name, $name, $name)
            ->each(function($item) use ($name) {
                $result = $this->toXY($item['地址'])->first();
				$point = new Point($result->getCoordinates()->toArray());

			    $properties = [
				    'title' => '垃圾焚化廠',
				    'source_id' => null,
                    'name' => $item[$name],
                    'address' => $item['地址'],
			    ];
			    $properties['formatted_address'] = $result->getFormattedAddress();
				$properties['geocode_result'] = $result->toArray();
                $feature = new Feature($point, $properties);
                //dd($feature->jsonSerialize());
                Place::create($feature->jsonSerialize());
            });*/

        /*$name = '名稱';
        $x = '經度';
        $y = '緯度';
        $this->fetchFromLife('garbage', $name, $name, $name)
            ->each(function($item) use ($name, $x, $y) {
                $point = new Point([(float)$item[$x], (float)$item[$y]]);

			    $properties = [
				    'title' => '廢棄物處理廠',
				    'source_id' => null,
                    'name' => $item[$name],
                    'address' => $item['地址'],
			    ];
			    
                $feature = new Feature($point, $properties);
                //dd($feature->jsonSerialize());
                Place::create($feature->jsonSerialize());
            });*/

        /*$name = '名稱';
        $x = '經度';
        $y = '緯度';
        $this->fetchFromLife('gas', $name, $name, $name)
            ->each(function($item) use ($name, $x, $y) {
                $xy = $this->TWD97toWGS84($item[$x], $item[$y]);
                $point = new Point([$xy[0], $xy[1]]);

			    $properties = [
				    'title' => '煤氣行',
				    'source_id' => null,
                    'name' => $item[$name],
                    'address' => $item['地址'],
			    ];
			    
                $feature = new Feature($point, $properties);
                //dd($feature->jsonSerialize());
                Place::create($feature->jsonSerialize());
            });*/

        /*$name = '機構名稱';
        $this->fetchFromLife('hospital', $name, $name, $name)
            ->each(function($item) use ($name) {
                $result = $this->toXY($item['地址'])->first();
				$point = new Point($result->getCoordinates()->toArray());

			    $properties = [
				    'title' => '醫療單位',
				    'source_id' => null,
                    'name' => $item[$name],
                    'address' => $item['地址'],
                    'division' => $item['診療科別'],
                    'unitType' => $item['型態別']
			    ];
			    $properties['formatted_address'] = $result->getFormattedAddress();
				$properties['geocode_result'] = $result->toArray();
			    
                $feature = new Feature($point, $properties);
                //dd($feature->jsonSerialize());
                Place::create($feature->jsonSerialize());
            });*/
        /*$name = '名稱';
        $this->fetchFromLife('library', $name, $name, $name)
            ->each(function($item) use ($name) {
                $result = $this->toXY($item['地址'])->first();
				$point = new Point($result->getCoordinates()->toArray());

			    $properties = [
				    'title' => '圖書館',
				    'source_id' => null,
                    'name' => $item[$name],
                    'address' => $item['地址']
			    ];
			    $properties['formatted_address'] = $result->getFormattedAddress();
				$properties['geocode_result'] = $result->toArray();
			    
                $feature = new Feature($point, $properties);
                //dd($feature->jsonSerialize());
                Place::create($feature->jsonSerialize());
            });

        $name = '名稱';
        $this->fetchFromLife('movie', $name, $name, $name)
            ->each(function($item) use ($name) {
                $result = $this->toXY($item['地址'])->first();
				$point = new Point($result->getCoordinates()->toArray());

			    $properties = [
				    'title' => '電影院',
				    'source_id' => null,
                    'name' => $item[$name],
                    'address' => $item['地址']
			    ];
			    $properties['formatted_address'] = $result->getFormattedAddress();
				$properties['geocode_result'] = $result->toArray();
			    
                $feature = new Feature($point, $properties);
                //dd($feature->jsonSerialize());
                Place::create($feature->jsonSerialize());
            });

        $name = 'name';
        $x = 'longitude';
        $y = 'latitude';
        $this->fetchFromLife('museum', $name, $name, $name)
            ->each(function($item) use ($name, $x, $y) {
                $point = new Point([(float)$item[$x], (float)$item[$y]]);

			    $properties = [
				    'title' => '博物館',
				    'source_id' => null,
                    'name' => $item[$name],
                    'address' => $item['cityName'] . $item['address'],
			    ];
			    
                $feature = new Feature($point, $properties);
                //dd($feature->jsonSerialize());
                Place::create($feature->jsonSerialize());
            });*/

        /*$name = '名稱';
        $x = '經度';
        $y = '緯度';
        $this->fetchFromLife('religion', $name, $name, $name)
            ->each(function($item) use ($name, $x, $y) {
                $xy = $this->TWD97toWGS84($item[$x], $item[$y]);
                $point = new Point([$xy[0], $xy[1]]);

			    $properties = [
				    'title' => '宗教單位',
				    'source_id' => null,
                    'name' => $item[$name],
                    'section' => $item['教別'],
                    'organization' => $item['團體']
			    ];
			    
                $feature = new Feature($point, $properties);
                //dd($feature->jsonSerialize());
                Place::create($feature->jsonSerialize());
            });

        $name = '名稱';
        $x = '經度';
        $y = '緯度';
        $this->fetchFromLife('school', $name, $name, $name)
            ->each(function($item) use ($name, $x, $y) {
                $point = new Point([(float)$item[$x], (float)$item[$y]]);

			    $properties = [
				    
				    'title' => '學校',
				    'level' => $item['學校'],
				    'source_id' => null,
                    'name' => $item[$name],
                    'address' => $item['地址'],
			    ];
			    
                $feature = new Feature($point, $properties);
                //dd($feature->jsonSerialize());
                Place::create($feature->jsonSerialize());
            });*/

        /*$name = '名稱';
        $x = '經度';
        $y = '緯度';
        $this->fetchFromLife('traditionalMarket', $name, $name, $name)
            ->each(function($item) use ($name, $x, $y) {
            	$xy = trim($item['經緯度'], '(');
            	$xy = trim($xy, ')');
            	$xy = explode(",",$xy);
                $point = new Point([(float)$xy[1], (float)$xy[0]]);
                if((float)$xy[0] > (float)$xy[1]) {
                	$point = new Point([(float)$xy[0], (float)$xy[1]]);
                }

			    $properties = [
				    'title' => '傳統市場',
				    'source_id' => null,
                    'name' => $item[$name],
                    'address' => $item['地址'],
			    ];
			    
                $feature = new Feature($point, $properties);
                //dd($feature->jsonSerialize());
                Place::create($feature->jsonSerialize());
            });*/

        $name = '廟名';
        $this->fetchFromLife('temple', $name, $name, $name)
            ->each(function($item) use ($name) {
                $result = $this->toXY($item['地址'])->first();
				$point = new Point($result->getCoordinates()->toArray());

			    $properties = [
				    'title' => '廟宇',
				    'source_id' => null,
                    'name' => $item[$name],
                    'address' => $item['地址'],
                    'god' => $item['神祇']
			    ];
			    $properties['formatted_address'] = $result->getFormattedAddress();
				$properties['geocode_result'] = $result->toArray();
			    
                $feature = new Feature($point, $properties);
                //dd($feature->jsonSerialize());
                Place::create($feature->jsonSerialize());
            });

        //dd($this->TWD97toWGS84(302652.865071764, 2775582.73828788));

    }
    public function fetchFromLife($collection, $name, $x, $y) {
        $db = DB::connection('life');
        $lifeCollection = $db->table($collection)->whereNotNull($x)->whereNotNull($y)->whereNotNull($name)->get();
        return $lifeCollection;
    }
	public function toXY($address) {
		$geocoder = $this->geocoder;
		$result = $geocoder->geocodeQuery(GeocodeQuery::create($address)->withLocale('zh_TW'));

		return $result;
    }
    public function TWD97toWGS84($x, $y) {
        $proj4 = new Proj4php();
        //$google = new Proj("+proj=merc +a=6378137 +b=6378137 +lat_ts=0.0 +lon_0=0.0 +x_0=0.0 +y_0=0 +k=1.0 +units=m +nadgrids=@null +wktext +no_defs", $proj4);
        $TWD97 = new Proj("+proj=tmerc +lat_0=0 +lon_0=121 +k=0.9999 +x_0=250000 +y_0=0 +ellps=GRS80 +units=m +no_defs" ,$proj4);

        $projTWD97 = new Proj('TWD97', $proj4);
        $projWGS84 = new Proj('EPSG:4326', $proj4);

        $pointSrc = new Proj4Point($x, $y, $TWD97);
        $pointDest = $proj4->transform($projWGS84, $pointSrc);
        return $pointDest->toArray();
    }
}
