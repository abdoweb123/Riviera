<?php

namespace Modules\Admin\Http\Requests;

use App\Http\Requests\API\BaseRequest;

class UpdateProfileRequest extends BaseRequest
{
    public function rules()
    {
        return [
            // 'phone' => ['required',Rule::unique('admins')->where(function ($query) {
            //     return $query->where('phone', request('phone'))->where('phone_code', request('phone_code'));
            // })],
            // 'phone_code'  =>  ['required'],
            'name' => ['nullable'],
            'password' => ['nullable'],
            'password_confirmation' => ['nullable', 'same:password'],
            'device_token' => ['nullable'],
        ];
    }
}
