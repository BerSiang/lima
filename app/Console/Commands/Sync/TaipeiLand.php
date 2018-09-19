<?php

namespace App\Console\Commands\Sync;

use Illuminate\Console\Command;
use GuzzleHttp\Client;

use App\Model\ExternalData\TaipeiLand as tpeLand;

class TaipeiLand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:tpeland {file}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync Taipei Land';

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
        $filePath = $this->argument("file");
        $fp = fopen($filePath, 'r');
        $list = [];
        fgetcsv($fp, 1024, ',');
        $num = 0;
        while($row = fgetcsv($fp, 1024, ',')) {
            array_push($list, [
                "regions" => [trim($row[0], ' '), trim($row[1], ' ')],
                "pracellary" => $row[2],
                "pracel" => $row[3]
            ]);
            $num++;
        }
        $client = new Client(['base_uri' => 'http://twland.ronny.tw/index/search']);
        
        $real = 0;
        $reqNum = 50;
        for($i = 0; $i < floor($num / $reqNum); $i++) {
            $query = [];
            for($j = 0; $j < $reqNum; $j++) {
                $s = $list[$i * $reqNum + $j];
                $q = $s['regions'][0] . ',' . $s['pracellary'] . ',' . floor($s['pracel'] / 10000) . '-' . $s['pracel'] % 10000;
                //echo $i . ": " . $q, "\n";
                array_push($query, $q);
            }
            $res = $client->request('GET', '', ['query' => ['lands' => $query]]);
            $json = json_decode($res->getBody(), true);
            for($k = 0; $k < count($json['features']); $k++) {
                $land = $json['features'][$k];
                array_set($land, 'properties.pracel_id', $land['properties']['land_id']);
                array_forget($land, 'properties.land_id');
                //var_dump($land);
                try {
                    \App\Model\ExternalData\TaipeiLand::create($land);
                }
                catch(\Exception $e) {
                    echo "In Doc: " . "\n";
                    var_dump($land);
                    echo $e->getMessage() . "\n";
                }
            }
            if(count($json['features']) == 0) {
                print("Query not found: \n");
                var_dump($query);
            }
            $real += $reqNum;
            echo $real . "\n";
        }
        for($i = 0; $i < $num - floor($num / $reqNum) * $reqNum; $i++) {
            $base = floor($num / $reqNum) * $reqNum - 1;
            $query = [];
            $s = $list[$base + $i];
            $q = $s['regions'][0] . ',' . $s['pracellary'] . ',' . floor($s['pracel'] / 10000) . '-' . $s['pracel'] % 10000;
            array_push($query, $q);

            $res = $client->request('GET', '', ['query' => ['lands' => $query]]);
            $json = json_decode($res->getBody(), true);
            for($k = 0; $k < count($json['features']); $k++) {
                $land = $json['features'][$k];
                array_set($land, 'properties.pracel_id', $land['properties']['land_id']);
                array_forget($land, 'properties.land_id');
                var_dump($land);
                \App\Model\ExternalData\TaipeiLand::create($land);
            }
            if(count($json['features']) == 0) {
                print("Query not found: \n");
                var_dump($query);
            }
            $real++;
            echo $real . "\n";
        }
        /*foreach($list as $row) {
            print($row['regions'][0] . $row['regions'][1] . $row['pracellary'] . floor($row['pracel'] / 10000) . '-' . $row['pracel'] % 10000 . "\n");
        }*/
    }
}
