<?php

use Illuminate\Database\Seeder;
use App\Model\ExternalData\SourceInfo;

class SourceInfoSetup extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $source = new SourceInfo;
        $source->title = "殯葬業者";
        $source->provider = "Data Taipei";
        $source->url = "http://data.taipei/opendata/datalist/apiAccess";
        $source->query = [
            "scope" => "resourceAquire",
            "rid" => "132c39fa-34f8-47e5-93e7-6c1a6b36cbb8"
        ];
        $source->index_name = "location";
		$source->name_index = "name";
		$source->address_index = "address";
        $source->original_type = "json";
        $source->synced_interval = 3 * 24 * 60 * 60;
        $source->last_synced_time = "";
        $source->remote_updated_time = "";
        $source->save();
    }
}
