<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTaipeiLandCollection extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('taipei_lands', function(Blueprint $collection) {
            $collection->geospatial('geometry', '2dsphere');
            $collection->index('properties.area_id');
            $collection->index('properties.section_id');
            $collection->index('properties.pracel_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('taipei_land');
    }
}
