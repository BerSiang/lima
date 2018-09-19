<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTaipeiLandValueCollection extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('taipei_land_value', function(Blueprint $collection) {
            $collection->string('parcel_id');
            $collection->index('regions');
            $collection->index('parcellary');
            $collection->index('parcel_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('taipei_land_value');
    }
}
