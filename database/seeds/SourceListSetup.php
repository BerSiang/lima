<?php

use Illuminate\Database\Seeder;
use App\Model\ExternalData\SourceList;

class SourceListSetup extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $source = new SourceList;
        $source->type = "殯葬業者";
        $source->provider = "Data Taipei";
        $source->url = "http://data.taipei/opendata/datalist/apiAccess";
        $source->query = [
            "scope" => "resourceAquire",
            "rid" => "132c39fa-34f8-47e5-93e7-6c1a6b36cbb8"
        ];
        $source->indexName = "location";
        $source->originalType = "json";
        $source->updatedTime = "";
        $source->createdTime = Carbon\Carbon::now();
        $source->save();
    }
}
