<?php

namespace Modules\Admin\Http\Requests;

use App\Http\Requests\API\BaseRequest;
use Illuminate\Validation\Rule;

class LoginRequest extends BaseRequest
{
    public function rules()
    {
        return [
            'phone' => [Rule::requiredIf(function () {
                return request('email') == null;
            })],
            'phone_code' => [Rule::requiredIf(function () {
                return request('phone');
            })],
            'email' => [Rule::requiredIf(function () {
                return request('phone') == null;
            })],
            'password' => ['required'],
            'device_token' => ['nullable'],
        ];
    }
}
