<?php

namespace App\Model\ExternalData;

use Jenssegers\Mongodb\Eloquent\Model as Eloquent;
use Jenssegers\Mongodb\Eloquent\SoftDeletes;

class SourceInfo extends Eloquent
{
    use SoftDeletes;
}
