<?php

namespace Modules\Driver\Http\Requests;

use App\Http\Requests\API\BaseRequest;
use Illuminate\Validation\Rule;

class CarStatusRequest extends BaseRequest
{
    public function rules()
    {
        return [
            'status' => ['required', 'numeric', 'between:0,1'],
        ];
    }
}
