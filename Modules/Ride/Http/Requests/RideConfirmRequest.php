<?php

namespace Modules\Ride\Http\Requests;

use App\Http\Requests\API\BaseRequest;
use Illuminate\Validation\Rule;

class RideConfirmRequest extends BaseRequest
{
    public function rules()
    {
        return [
//            'driver_id' => ['required', 'exists:drivers,id' ],
            'payment_id' => ['required','exists:payments,id'],
            'carType_id' => ['required','exists:car_types,id'],
        ];
    }
}

