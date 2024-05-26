<?php

namespace Modules\Client\Http\Requests;

use App\Http\Requests\API\BaseRequest;

class OTPRequest extends BaseRequest
{
    public function rules()
    {
        return [
            'phone' => 'required|min:6',
        ];
    }
}
