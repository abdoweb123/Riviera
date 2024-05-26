<?php

namespace App\Http\Requests\API;

class HomeRequest extends BaseRequest
{
    public function rules()
    {
        return [
            'device_type' => ['required'],
            'device_version' => ['required'],
        ];
    }
}
