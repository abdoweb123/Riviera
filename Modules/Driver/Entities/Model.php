<?php

namespace Modules\Driver\Entities;

use App\Traits\Status;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Modules\Country\Entities\Country;
use Modules\Car\Entities\Model as Car;
use Modules\Rating\Entities\Model as Rating;
use Modules\Ride\Entities\Model as Ride;


class Model extends Authenticatable
{
    use HasApiTokens, Notifiable, Status;

    protected $guarded = [];

    protected $table = 'drivers';

    protected $casts = ['email_verified_at' => 'datetime'];

    public function totalRating()
    {
        $total = number_format($this->ratings()->avg('rating'),1);

        return $total ?? 0; // Return 0 if there are no ratings
    }

    function formatRides($rides)
    {
        if ($rides >= 1000) {
            $formatted = number_format($rides / 1000, 1) . 'K';
        } else {
            $formatted = number_format($rides);
        }
        return $formatted;
    }

    /*** start relations ***/

    public function Country()
    {
        return $this->belongsTo(Country::class, 'country_id');
    }


    public function DeviceTokens()
    {
        return $this->hasMany(DeviceToken::class, 'driver_id');
    }

    public function car()
    {
        return $this->hasOne(Car::class, 'driver_id');
    }

    public function ratings()
    {
        return $this->hasMany(Rating::class,'driver_id');
    }

    public function rides()
    {
        return $this->hasMany(Ride::class,'driver_id');
    }


} //end of class
