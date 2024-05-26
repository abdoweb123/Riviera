<?php

namespace Modules\Admin\Http\Requests;

use App\Http\Requests\API\BaseRequest;
use Illuminate\Support\Facades\Hash;

class OldPasswordRequest extends BaseRequest
{
    public function rules()
    {
        $password = auth('sanctum')->check() ? auth('sanctum')->user()->password : null;

        return [
            'password' => ['required'],
            'password_confirmation' => ['required', 'same:password'],
            'old_password' => ['required', function ($attribute, $value, $fail) use ($password) {
                if (! Hash::check($value, $password)) {
                    $fail('Old Password didn\'t match');
                }
            }, ],
        ];
    }
}
