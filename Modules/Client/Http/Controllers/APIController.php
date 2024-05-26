<?php

namespace Modules\Client\Http\Controllers;

use App\Functions\ResponseHelper;
use App\Functions\Upload;
use App\Http\Controllers\BasicController;
use Illuminate\Http\Request;
use Modules\Car\Http\Resources\CarResource;
use Modules\Client\Entities\Model as Client;
use Modules\Client\Http\Requests\CheckExistsRequest;
use Modules\Client\Http\Requests\DeviceTokenRequest;
use Modules\Client\Http\Requests\LoginRequest;
use Modules\Client\Http\Requests\OldPasswordRequest;
use Modules\Client\Http\Requests\RegisterRequest;
use Modules\Client\Http\Requests\UpdateProfileRequest;
use Modules\Client\Http\Resources\ClientResource;
use Modules\Country\Entities\Country;

class APIController extends BasicController
{

    public function Login(LoginRequest $request)
    {
        $phone_code = str_replace('+', '', $request->phone_code);
        if (Auth('client')->attempt(['phone' => $request->phone, 'password' => $request->password, 'phone_code' => $phone_code, 'deleted_at' => null, 'status' => 1])) {
            $Model = Auth('client')->user();
        } else {
            return ResponseHelper::make(null, __('trans.emailPasswordIncorrect'), false, 404);
        }

        if (! $Model->DeviceTokens()->where(['device_token' => $request->device_token])->exists()) {
            $Model->DeviceTokens()->create([
                'device_token' => $request->device_token,
            ]);
        }
        $response['token'] = $Model->createToken('ClientToken')->plainTextToken;
        $response['client'] = CarResource::make($Model);

        return ResponseHelper::make($response, __('trans.loginSuccessfully'));
    }

    public function Register(RegisterRequest $request)
    {
        $phone_code = str_replace('+', '', $request->phone_code);
        $Country = Country::where('phone_code', 'LIKE', "%{$phone_code}%")->first();
        $code = $Country->country_code;

        $Model = Client::where('phone', $request->phone)->where('phone_code', $phone_code)->first();
        if ($Model) {
            $Model->name = $request->name;
            $Model->email = $request->email;
            $Model->password = bcrypt($request['password']);
            $Model->status = 1;
            $Model->deleted_at = null;
            $Model->save();
            $Model = Client::where('phone', $request->phone)->where('phone_code', $phone_code)->first();
        } else {
            $Model = Client::create([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'password' => bcrypt($request['password']),
                'phone_code' => $phone_code,
                'status' => 1,
            ]);
        }
        if (! $Model->DeviceTokens()->where(['device_token' => $request->device_token])->exists()) {
            $Model->DeviceTokens()->create([
                'device_token' => $request->device_token,
            ]);
        }
        $success['token'] = $Model->createToken('ClientToken')->plainTextToken;
        $success['client'] = CarResource::make($Model);


        return ResponseHelper::make($success, __('trans.addedSuccessfully'));
    }

    public function DeviceToken(DeviceTokenRequest $request)
    {
        $this->CheckAuth();
        if (! $this->Client->DeviceTokens()->where(['device_token' => $request->device_token])->exists()) {
            $this->Client->DeviceTokens()->create([
                'device_token' => $request->device_token,
            ]);
        }
        $response['token'] = request()->bearerToken();
        $response['client'] = CarResource::make($this->Client);

        return ResponseHelper::make($response, __('trans.addedSuccessfully'));
    }

    public function profile()
    {
        if(!auth('sanctum')->check())
        {
            return ResponseHelper::make(null,'guest');
        }

        $this->CheckAuth();
        $response['token'] = request()->bearerToken();
        $response['client'] = ClientResource::make($this->Client);

        return ResponseHelper::make($response);

    }

    public function UpdateProfile(UpdateProfileRequest $request)
    {
        $this->CheckAuth();
        $Model = $this->Client;

        $phone_code = str_replace('+', '', $request->phone_code);

        $Model->update([
            'name'=>$request->name,
            'email'=>$request->email,
            'phone_code'=>$phone_code,
            'phone'=>$request->phone,
        ]);

        $response['token'] = request()->bearerToken();
        $response['client'] = ClientResource::make($Model->refresh());

        return ResponseHelper::make($response, __('trans.updatedSuccessfully'));
    }

    public function UpdateImage(Request $request)
    {
        $this->CheckAuth();
        $Model = $this->Client;
        if (request('image')) {
            $Model->update(['image' => Upload::UploadFile(request('image'), 'Clients')]);
        }

        $response['token'] = request()->bearerToken();
        $response['client'] = CarResource::make($Model->refresh());

        return ResponseHelper::make($response, __('trans.updatedSuccessfully'));
    }

    public function UpdatePassword(Request $request)
    {
        $phone_code = str_replace('+', '', $request->phone_code);
        $Model = Client::where('phone', $request->phone)->where('phone_code', $phone_code)->firstorfail();
        if (request('password')) {
            Client::where('phone', $request->phone)->where('phone_code', $phone_code)->update(['password' => bcrypt(request('password'))]);
        }

        $response['token'] = request()->bearerToken();
        $response['client'] = CarResource::make($Model);

        return ResponseHelper::make($response, __('trans.updatedSuccessfully'));

    }

    public function UpdateOldPassword(OldPasswordRequest $request)
    {
        $this->CheckAuth();

        $this->Client->password = bcrypt(request('password'));
        $this->Client->save();
        $response['token'] = request()->bearerToken();
        $response['client'] = CarResource::make($this->Client);

        $this->Client->DeviceTokens()->where('device_token', request()->device_token)->delete();
        $this->Client->currentAccessToken()->delete();

        return ResponseHelper::make($response, __('trans.updatedSuccessfully'));

    }

    public function CheckNumber(CheckExistsRequest $request)
    {
        $phone_code = str_replace('+', '', $request->phone_code);
        if ($request->phone) {
            $Model = Client::where('phone', $request->phone)->where('phone_code', $phone_code)->first();
        } elseif ($request->email) {
            $Model = Client::where('email', $request->email)->first();
        }
        $response['exist'] = $Model ? 1 : 0;
        $response['token'] = $Model ? $Model->createToken('ClientToken')->plainTextToken : null;
        $response['client'] = $Model ? CarResource::make($Model) : null;

        return ResponseHelper::make($response, $Model ? __('trans.already_exist') : __('trans.dont_exist'));
    }

    public function Logout()
    {
        $this->CheckAuth();
        $this->Client->DeviceTokens()->where('device_token', request()->device_token)->delete();
        $this->Client->currentAccessToken()->delete();

        return ResponseHelper::make(null, __('trans.logoutSuccessfully'));
    }

    public function lang($lang)
    {
        $this->CheckAuth();
        $this->Client->lang = $lang;
        $this->Client->save();

        return ResponseHelper::make(null, __('trans.addedSuccessfully'));
    }

    public function DeleteAccount()
    {
        if (auth('sanctum')->check()) {
            $this->CheckAuth();
            if ($this->Client) {
                $this->Client->DeviceTokens()->where('device_token', request()->device_token)->delete();
                $this->Client->tokens()->delete();
                $this->Client->delete();
            }
        } elseif (request()->phone) {
            $Client = Client::where('phone', request()->phone)->firstorfail();
            if ($Client) {
                $Client->DeviceTokens()->delete();
                $Client->tokens()->delete();
                $Client->delete();
            }
        } elseif (request()->client_id) {
            $Client = Client::where('id', request()->client_id)->firstorfail();
            if ($Client) {
                $Client->DeviceTokens()->delete();
                $Client->tokens()->delete();
                $Client->delete();
            }
        }

        return ResponseHelper::make(null, __('trans.DeletedSuccessfully'));
    }
}
