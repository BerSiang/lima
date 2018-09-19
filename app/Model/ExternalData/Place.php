<?php

namespace App\Model\ExternalData;

use Jenssegers\Mongodb\Eloquent\Model as Eloquent;
use Jenssegers\Mongodb\Eloquent\SoftDeletes;

class Place extends Eloquent
{
    use SoftDeletes;

	protected $guarded = [];
}
