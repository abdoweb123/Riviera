<?php

namespace Modules\Ride\Entities;

use App\Models\BaseModel;


class CancellationReasons extends BaseModel
{
    protected $table = 'cancellation_reasons';

    protected $guarded = [];

    public function rideCancelations(){
        return $this->hasMany(RideCancellation::class,'reason_id');
    }



} //end of class
