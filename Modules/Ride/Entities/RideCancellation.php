<?php

namespace Modules\Ride\Entities;

use App\Models\BaseModel;


class RideCancellation extends BaseModel
{
    protected $table = 'ride_cancellations';

    protected $guarded = [];

    public function ride(){
        return $this->belongsTo(Model::class,'ride_id');
    }

    public function cancellationReason(){
        return $this->belongsTo(CancellationReasons::class,'reason_id');
    }


} //end of class
