<?php

namespace Modules\Ride\Entities;

use App\Models\BaseModel;
use Modules\Ride\Entities\Model as Ride;

class RideStatus extends BaseModel
{
    protected $table = 'ride_statuses';

    protected $guarded = [];


    public function ride()
    {
        return $this->belongsTo(Ride::class,'ride_id');
    }

} //end of class
