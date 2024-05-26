<?php

namespace Modules\Client\Entities;

use \Modules\Client\Entities\Model as Client;
class DeviceToken extends \App\Models\BaseModel
{
    protected $guarded = [];

    protected $table = 'client_device_tokens';

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

}//end of class
