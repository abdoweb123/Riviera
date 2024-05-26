<?php

namespace Modules\Client\Http\Requests;

use App\Http\Requests\API\BaseRequest;
use Illuminate\Validation\Rule;

class RegisterRequest extends BaseRequest
{
    public function rules()
    {
        $phone_code = str_contains(request('phone_code'), '+') ? request('phone_code') : '+'.request('phone_code');

        return [
            'name' => ['required'],
            'email' => ['required'],
            'phone' => ['required', Rule::unique('clients')->where(function ($query) use ($phone_code) {
                return $query->where('phone', request('phone'))->where('phone_code', $phone_code);
            })],
            'password' => ['required'],
            'password_confirmation' => ['required', 'same:password'],
            'phone_code' => ['required'],
            'device_token' => ['nullable'],
        ];
    }
}
