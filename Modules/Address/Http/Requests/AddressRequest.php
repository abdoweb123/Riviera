<?php

namespace Modules\Address\Http\Requests;

use App\Http\Requests\API\BaseRequest;

class AddressRequest extends BaseRequest
{
    public function rules()
    {
        return [
            'latitude' => ['required','max:255'],
            'longitude' => ['required','max:255'],
        ];
    }
}

