<?php

namespace Modules\Driver\Http\Requests;

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
            'name' => 'nullable',
            'surname' => 'nullable',
            'email' => 'nullable',
//            'phone' => 'nullable',
//            'phone_code' => 'required_if:phone,provided',
            'phone' => [
                'required',
                Rule::unique('drivers')->ignore(auth()->id())->where(function ($query) use ($phone_code) {
                    return $query->where('phone_code', $phone_code)->where('phone',$this->input('phone'));
                })
            ],
            'phone_code' => 'required_with:phone|string|max:10',
        ];
    }

    public function withValidator($validator)
    {
        $validator->sometimes('email', ['required','email','max:255',Rule::unique('drivers')->ignore(auth()->id())], function ($input) {
            return !$input->name && !$input->surname && !$input->phone && !$input->phone_code;
        });

        $validator->sometimes('name', 'required|max:255', function ($input) {
            return !$input->surname && !$input->email && !$input->phone && !$input->phone_code;
        });

        $validator->sometimes('surname', 'required|max:255', function ($input) {
            return !$input->name && !$input->email && !$input->phone && !$input->phone_code;
        });



        $phone_code = str_contains($this->input('phone_code'), '+') ? $this->input('phone_code') : '+'.$this->input('phone_code');

        $validator->sometimes('phone', [
            'required',
            Rule::unique('drivers')->ignore(auth('sanctum')->id())->where(function ($query) use ($phone_code) {
                return $query->where('phone', $this->input('phone'))
                    ->where('phone_code', $phone_code);
            }),
        ], function ($input) {
            return !$input->name && !$input->surname && !$input->email;
        });


    }




}
