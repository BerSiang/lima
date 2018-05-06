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

use App\Model\ExternalData\SourceInfo;
use App\Model\ExternalData\Place;

class DataSet1 extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:dataset1';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    protected $geocoder = null;
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
        $list = [
            "殯葬業者"
        ];
        $client = new AdapterClient();
        $geoProvider = new GoogleMaps($client , '', env('GOOGLE_MAP_API_KEY'));
        $this->geocoder = new StatefulGeocoder($geoProvider);

        foreach($list as $target) {
			$this->fetch($target)
				->each(function($item) {
					if($item['action'] == 'update') {
						$this->update($item['_id'], $item['properties']);
					}
					else if($item['action'] == 'create') {
						$this->create($item['properties']);
					}
                    else if($item['action'] == 'delete') {
                        $this->delete($item['_id']);
					}
				});
		}

    }
    
    public function remote($type)
    {    
        $syncData = [];
        $source = SourceInfo::where("title", $type)->first();

        $client = new Client(['base_uri' => $source->url]);
		$response = $client->request('GET', '', ['query' => $source->query]);
        $syncData = json_decode($response->getBody());

        return array(
            "source" => $source,
            "body" => collect($syncData->result->results)
        );
    }

    public function fetch($type) {
        $remote = $this->remote($type);
        $local = $this->local($remote['source']->_id);

		$name_index = $remote['source']->name_index;
		$address_index = $remote['source']->address_index;

		$collection = $remote['body']->reject(function($item) use ($name_index, $address_index) {   //移除空項
			return $item->{$name_index} == null || $item->{$address_index} == null;
		});

        $collection = $collection->map(function($item) use ($name_index, $address_index, $remote, $local) {
            $found = Place::where('properties.name', $item->{$name_index})  //檢查是否存在local db
                        ->where('properties.address', $item->{$address_index})
                        ->first();

			$data = array(
                'action' => $found ? 'update' : 'create',
                '_id' => $found->_id ?? null,
				'properties' => [
					'title' => $remote['source']->title,
					'source_id' => $remote['source']->_id,
					'name' => $item->{$name_index},
					'address' => $item->{$address_index},
				]
            );

            return $data;
        });

		$deletePlaces = $local->map(function($item) use ($remote, $address_index) {
			$has = $remote['body']->where($address_index, $item->address)->first();
			if(!$has) {
				return array(
					'action' => 'delete',
					'_id' => $item->_id
				);
			}
        })->reject(function($item) {
            return $item === null;
        });

		return $collection->merge($deletePlaces);
    }

    public function create($data) {
        $result = $this->toXY($data['address'])->first();

        $point = new Point($result->getCoordinates()->toArray());
        $properties = $data;
        $properties['formatted_address'] = $result->getFormattedAddress();
        $properties['geocode_result'] = $result->toArray();
        $feature = new Feature($point, $properties);

		Place::create($feature->jsonSerialize());
    }

    public function update($id, $data) {
        Place::find($id)
			->update($data);
    }

	public function delete($id) {
        Place::find($id)
			->delete();
	}

    public function local($sourceId) {
        return Place::where('source_id', $sourceId)->get();
    }

    public function toXY($address) {
        $geocoder = $this->geocoder;
        $result = $geocoder->geocodeQuery(GeocodeQuery::create($address)->withLocale('zh_TW'));

        return $result;
    }
}
