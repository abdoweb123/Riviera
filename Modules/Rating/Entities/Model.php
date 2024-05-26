<?php

namespace Modules\Rating\Entities;

use App\Models\BaseModel;
use \Modules\Client\Entities\Model as Client;
use \Modules\Driver\Entities\Model as Driver;

class Model extends BaseModel
{
    protected $table = 'ratings';

    protected $guarded = [];


    /*** start relations ***/
    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function driver()
    {
        return $this->belongsTo(Driver::class);
    }


} //end of class
