<?php

namespace Modules\Client\Http\Requests;

use App\Http\Requests\API\BaseRequest;
use Illuminate\Validation\Rule;

class CheckExistsRequest extends BaseRequest
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
            'device_token' => ['nullable'],
        ];
    }
}
