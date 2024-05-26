<?php

namespace Modules\Country\Entities;

use App\Models\BaseModel;
use Rennokki\QueryCache\Traits\QueryCacheable;

class Region extends BaseModel
{
    use QueryCacheable;

    public $cacheFor = 3600;

    protected $guarded = [];

    protected $table = 'regions';

    public function Cities()
    {
        return $this->hasMany(City::class)->orderBy('title_'.lang());
    }
}
