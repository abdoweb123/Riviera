<?php

namespace Modules\Rating\Http\Requests;

use App\Http\Requests\API\BaseRequest;

class RatingRequest extends BaseRequest
{
    public function rules()
    {
        return [
            'driver_id' => 'required|exists:drivers,id',
            'rating' => 'required|numeric|min:1|max:5',
        ];
    }
}
