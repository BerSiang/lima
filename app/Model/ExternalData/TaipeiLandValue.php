<?php

namespace App\Model\ExternalData;

use Jenssegers\Mongodb\Eloquent\Model as Eloquent;
use Jenssegers\Mongodb\Eloquent\SoftDeletes;

class TaipeiLandValue extends Eloquent
{
    use SoftDeletes;

    protected $collection = 'taipei_land_value';
    protected $guarded = [];
}
