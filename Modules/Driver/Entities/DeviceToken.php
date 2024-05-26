<?php

namespace Modules\Driver\Entities;

use Modules\Client\Entities\Model as Driver;

class DeviceToken extends \App\Models\BaseModel
{
    protected $guarded = [];

    protected $table = 'driver_device_tokens';

    public function driver()
    {
        return $this->belongsTo(Driver::class);
    }

} //end of class
