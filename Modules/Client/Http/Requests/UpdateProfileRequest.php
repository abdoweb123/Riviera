<?php

namespace Modules\Client\Http\Requests;

use App\Http\Requests\API\BaseRequest;
use Illuminate\Validation\Rule;

class UpdateProfileRequest extends BaseRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {

        $phone_code = str_replace('+', '', $this->phone_code);

        return [
            'name' => 'required',
            'email' => 'required',
            'phone' => [
                'required',
                Rule::unique('clients')->ignore(auth('sanctum')->id())->where(function ($query) use ($phone_code) {
                    return $query->where('phone_code', $phone_code)->where('phone',$this->input('phone'));
                })
            ],
            'phone_code' => 'required_with:phone|string|max:10',
        ];
    }




}
