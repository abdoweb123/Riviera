<?php

namespace Modules\Driver\Http\Requests;

use App\Http\Requests\API\BaseRequest;

class DeviceTokenRequest extends BaseRequest
{
    public function rules()
    {
        return [
            'device_token' => ['required'],
        ];
    }
}
