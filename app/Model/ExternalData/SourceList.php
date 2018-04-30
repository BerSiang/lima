<?php

namespace App\Model\ExternalData;

use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

class SourceList extends Eloquent
{
    protected $collection = "source_list";
}
