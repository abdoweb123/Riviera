<?php

namespace Modules\Client\Entities;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Modules\Country\Entities\Country;

class Model extends Authenticatable
{
    use HasApiTokens, Notifiable;

    protected $guarded = [];

    protected $table = 'clients';

    protected $casts = ['email_verified_at' => 'datetime'];

    public function Country()
    {
        return $this->belongsTo(Country::class, 'country_id');
    }

    public function DeviceTokens()
    {
        return $this->hasMany(DeviceToken::class, 'client_id');
    }

} //end of class
