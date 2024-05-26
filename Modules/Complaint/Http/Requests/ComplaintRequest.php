<?php

namespace Modules\Complaint\Http\Requests;

use App\Http\Requests\API\BaseRequest;

class ComplaintRequest extends BaseRequest
{
    public function rules()
    {
        return [
            'title' => 'required',
            'full_name' => 'required',
            'email' => 'required|email',
            'complaint' => 'required',
        ];
    }
}

