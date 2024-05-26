<?php

namespace Modules\Car\Entities;

use App\Models\BaseModel;
use Modules\Driver\Entities\Model as Driver;

class Model extends BaseModel
{
    protected $guarded = [];

    protected $table = 'cars';


    public function driver()
    {
        return $this->belongsTo(Driver::class,'driver_id');
    }

    public function carType()
    {
        return $this->belongsTo(CarType::class,'carType_id');
    }

} //end of class
