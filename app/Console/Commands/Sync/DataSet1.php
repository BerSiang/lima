<?php

namespace App\Console\Commands\Sync;

use Illuminate\Console\Command;
use GuzzleHttp\Client;
use Http\Adapter\Guzzle6\Client as AdapterClient;
use Geocoder\Query\GeocodeQuery;
use Geocoder\Query\ReverseQuery;
use Geocoder\Provider\GoogleMaps\GoogleMaps;
use Geocoder\StatefulGeocoder;

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
						$this->update($item['data']);
					}
					else if($item['action'] == 'create') {
						$this->create($item['data']);
					}
					else if($item['action'] == 'delete') {
						Place::where('address', $item['data']['address'])->delete();
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
            "title" => $source->title,
            "source_id" => $source->_id,
			"name_index" => $source->name_index,
            "address_index" => $source->address_index,
            "body" => collect($syncData->result->results)
        );
    }

    public function fetch($type) {
        $source = $this->remote($type);
        $local = $this->local($source['source_id']);

		$name_index = $source['name_index'];
		$address_index = $source['address_index'];

		$collection = $source['body']->reject(function($item) use ($name_index, $address_index) {
			return $item->{$name_index} == null || $item->{$address_index} == null;
		});

        $collection = $collection->map(function($item) use ($source, $local) {
            $found = Place::where('address', $item->{$source['address_index']})->first();
			//$geocode = $found ? 

			return array(
				'action' => $found ? 'update' : 'create',
				'data' => [
					'title' => $source['title'],
					'source_id' => $source['source_id'],
					'name' => $item->{$source['name_index']},
					'address' => $item->{$source['address_index']},
				]
			);
        });

		$deletePlaces = $local->map(function($item) use ($source, $address_index) {
			$has = $source['body']->where($address_index, $item->address)->first();
			if(!$has) {
				return array(
					'action' => 'delete',
					'data' => $item
				);
			}
		});

		return $collection->merge($deletePlaces);
    }

    public function create($data) {
		$geocodeResult = $this->toXY($data['address'])->first();
		$data['formatted_address'] = $geocodeResult->getFormattedAddress();
		$location = array(
			'type' => 'Point',
			'coordinates' => $geocodeResult->getCoordinates()->toArray()
		);
		$data['location'] = $location;
		$data['geocode_result'] = $geocodeResult->toArray();

		Place::create($data);
    }

    public function update($data) {
        Place::where('address', $data['address'])
			->update($data);
    }

	public function delete($data) {
        Place:where('address', $data['address'])
			->delete($data);
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
