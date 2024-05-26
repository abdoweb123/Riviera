<?php

namespace Modules\Country\Entities;

use App\Models\BaseModel;
use Rennokki\QueryCache\Traits\QueryCacheable;

class City extends BaseModel
{
    use QueryCacheable;

    public $cacheFor = 3600;

    protected $guarded = [];

    protected $table = 'cities';
}
