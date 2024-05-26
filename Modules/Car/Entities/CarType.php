<?php

namespace Modules\Car\Entities;

use App\Models\BaseModel;
use Modules\Car\Entities\Model as Car;

class CarType extends BaseModel
{
    protected $guarded = [];

    protected $table = 'car_types';


    public function cars()
    {
        return $this->hasMany(Car::class,'carType_id');
    }

} //end of class
