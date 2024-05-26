<?php

namespace Modules\Admin\Http\Requests;

use App\Http\Requests\API\BaseRequest;

class RegisterRequest extends BaseRequest
{
    public function rules()
    {
        return [
            'name' => ['required'],
            'email' => ['required'],
            'phone' => ['required'],
            'password' => ['required'],
            'password_confirmation' => ['required', 'same:password'],
            'phone_code' => ['required'],
            'device_token' => ['nullable'],
        ];
    }
}
