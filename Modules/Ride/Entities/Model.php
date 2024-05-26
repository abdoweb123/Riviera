<?php

namespace Modules\Ride\Entities;

use App\Models\BaseModel;
use Modules\Address\Entities\Model as Address;
use Modules\Car\Entities\CarType;
use Modules\Client\Entities\Model as Client;
use Modules\Payment\Entities\Model as Payment;
use Modules\Driver\Entities\Model as Driver;


class Model extends BaseModel
{
    protected $table = 'rides';

    protected $guarded = [];

    public function startAddress(){
        return $this->belongsTo(Address::class,'startAddress_id');
    }

    public function endAddress(){
        return $this->belongsTo(Address::class,'endAddress_id');
    }

    public function client(){
        return $this->belongsTo(Client::class,'client_id');
    }

    public function payment(){
        return $this->belongsTo(Payment::class,'payment_id');
    }

    public function rideStatuses()
    {
        return $this->hasMany(RideStatus::class,'ride_id');
    }

    public function driver()
    {
        return $this->belongsTo(Driver::class,'driver_id');
    }

    public function rideCancelations()
    {
        return $this->hasOne(RideCancellation::class,'ride_id');
    }

    public function unlikedRideDrivers()
    {
        return $this->hasMany(UnlikedRideDriver::class, 'ride_id');
    }

    public function nearestDrivers()
    {
        return $this->belongsToMany(Driver::class, 'nearest_drivers_rides', 'ride_id', 'driver_id');
    }

} //end of class


