<?php

namespace Modules\Admin\Entities;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Model extends Authenticatable
{
    use HasApiTokens, Notifiable;

    protected $guarded = [];

    protected $table = 'admins';

    protected $casts = ['email_verified_at' => 'datetime'];


    public function DeviceTokens()
    {
        return $this->hasMany(DeviceToken::class, 'admin_id');
    }

}
