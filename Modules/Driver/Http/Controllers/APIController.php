<?php

namespace Modules\Driver\Http\Controllers;

use App\Functions\ResponseHelper;
use App\Functions\Upload;
use App\Http\Controllers\BasicController;
use Illuminate\Http\Request;
use Modules\Driver\Entities\Model as Driver;
use Modules\Driver\Http\Requests\CheckExistsRequest;
use Modules\Driver\Http\Requests\DeviceTokenRequest;
use Modules\Driver\Http\Requests\LoginRequest;
use Modules\Driver\Http\Requests\OldPasswordRequest;
use Modules\Driver\Http\Requests\RegisterRequest;
use Modules\Driver\Http\Requests\UpdateProfileRequest;
use Modules\Driver\Http\Resources\DriverResource;
use Modules\Driver\Http\Resources\RideResource;
use Modules\Country\Entities\Country;

class APIController extends BasicController
{

    public function Login(LoginRequest $request)
    {
        $phone_code = str_replace('+', '', $request->phone_code);
        if (Auth('driver')->attempt(['phone' => $request->phone, 'password' => $request->password, 'phone_code' => $phone_code, 'deleted_at' => null, 'status' => 1])) {
            $Model = Auth('driver')->user();
        } else {
            return ResponseHelper::make(null, __('trans.emailPasswordIncorrect'), false, 404);
        }

        if (! $Model->DeviceTokens()->where(['device_token' => $request->device_token])->exists()) {
            $Model->DeviceTokens()->create([
                'device_token' => $request->device_token,
            ]);
        }
        $response['token'] = $Model->createToken('DriverToken')->plainTextToken;
        $response['driver'] = RideResource::make($Model);

        return ResponseHelper::make($response, __('trans.loginSuccessfully'));
    }


    public function Register(RegisterRequest $request)
    {
        $phone_code = str_replace('+', '', $request->phone_code);
        $Country = Country::where('phone_code', 'LIKE', "%{$phone_code}%")->first();
        $code = $Country->country_code;

        $Model = Driver::where('phone', $request->phone)->where('phone_code', $phone_code)->first();
        if ($Model) {
            $Model->name = $request->name;
            $Model->email = $request->email;
            $Model->password = bcrypt($request['password']);
            $Model->status = 1;
            $Model->deleted_at = null;
            $Model->save();
            $Model = Driver::where('phone', $request->phone)->where('phone_code', $phone_code)->first();
        } else {
            $Model = Driver::create([
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
        $success['token'] = $Model->createToken('DriverToken')->plainTextToken;
        $success['driver'] = RideResource::make($Model);


        return ResponseHelper::make($success, __('trans.addedSuccessfully'));
    }



    public function DeviceToken(DeviceTokenRequest $request)
    {
        $this->CheckAuth();
        if (! $this->Driver->DeviceTokens()->where(['device_token' => $request->device_token])->exists()) {
            $this->Driver->DeviceTokens()->create([
                'device_token' => $request->device_token,
            ]);
        }
        $response['token'] = request()->bearerToken();
        $response['driver'] = RideResource::make($this->Driver);

        return ResponseHelper::make($response, __('trans.addedSuccessfully'));
    }


    public function profile()
    {
        $this->CheckAuth();
        $response['token'] = request()->bearerToken();
        $response['driver'] = DriverResource::make($this->Driver);

        return ResponseHelper::make($response);
    }

    public function UpdateProfile(UpdateProfileRequest $request)
    {
        $this->CheckAuth();

        $phone_code = str_replace('+', '', $request->phone_code);

        $Model = $this->Driver;
        $Model->update([
            'name'=>$request->name,
            'email'=>$request->email,
            'phone_code'=>$phone_code,
            'phone'=>$request->phone,
        ]);

        $response['token'] = request()->bearerToken();
        $response['driver'] = DriverResource::make($Model->refresh());

        return ResponseHelper::make($response, __('trans.updatedSuccessfully'));
    }

    public function UpdateImage(Request $request)
    {
        $this->CheckAuth();
        $Model = $this->Driver;
        if (request('image')) {
            $Model->update(['image' => Upload::UploadFile(request('image'), 'Drivers')]);
        }

        $response['token'] = request()->bearerToken();
        $response['driver'] = RideResource::make($Model->refresh());

        return ResponseHelper::make($response, __('trans.updatedSuccessfully'));
    }

    public function UpdatePassword(Request $request)
    {
        $phone_code = str_replace('+', '', $request->phone_code);
        $Model = Driver::where('phone', $request->phone)->where('phone_code', $phone_code)->firstorfail();
        if (request('password')) {
            Driver::where('phone', $request->phone)->where('phone_code', $phone_code)->update(['password' => bcrypt(request('password'))]);
        }

        $response['token'] = request()->bearerToken();
        $response['driver'] = RideResource::make($Model);

        return ResponseHelper::make($response, __('trans.updatedSuccessfully'));

    }

    public function UpdateOldPassword(OldPasswordRequest $request)
    {
        $this->CheckAuth();
        $this->Driver->password = bcrypt(request('password'));
        $this->Driver->save();
        $response['token'] = request()->bearerToken();
        $response['driver'] = RideResource::make($this->Driver);

        $this->Driver->DeviceTokens()->where('device_token', request()->device_token)->delete();
        $this->Driver->currentAccessToken()->delete();

        return ResponseHelper::make($response, __('trans.updatedSuccessfully'));

    }

    public function CheckNumber(CheckExistsRequest $request)
    {
        $phone_code = str_replace('+', '', $request->phone_code);
        if ($request->phone) {
            $Model = Driver::where('phone', $request->phone)->where('phone_code', $phone_code)->first();
        } elseif ($request->email) {
            $Model = Driver::where('email', $request->email)->first();
        }
        $response['exist'] = $Model ? 1 : 0;
        $response['token'] = $Model ? $Model->createToken('DriverToken')->plainTextToken : null;
        $response['driver'] = $Model ? RideResource::make($Model) : null;

        return ResponseHelper::make($response, $Model ? __('trans.already_exist') : __('trans.dont_exist'));
    }

    public function Logout()
    {
        $this->CheckAuth();
        $this->Driver->DeviceTokens()->where('device_token', request()->device_token)->delete();
        $this->Driver->currentAccessToken()->delete();

        return ResponseHelper::make(null, __('trans.logoutSuccessfully'));
    }

    public function lang($lang)
    {
        $this->CheckAuth();
        $this->Driver->lang = $lang;
        $this->Driver->save();

        return ResponseHelper::make(null, __('trans.addedSuccessfully'));
    }

    public function DeleteAccount()
    {
        if (auth('sanctum')->check()) {
            $this->CheckAuth();
            if ($this->Driver) {
                $this->Driver->DeviceTokens()->where('device_token', request()->device_token)->delete();
                $this->Driver->tokens()->delete();
                $this->Driver->delete();
            }
        } elseif (request()->phone) {
            $Driver = Driver::where('phone', request()->phone)->firstorfail();
            if ($Driver) {
                $Driver->DeviceTokens()->delete();
                $Driver->tokens()->delete();
                $Driver->delete();
            }
        } elseif (request()->driver_id) {
            $Driver = Driver::where('id', request()->driver_id)->firstorfail();
            if ($Driver) {
                $Driver->DeviceTokens()->delete();
                $Driver->tokens()->delete();
                $Driver->delete();
            }
        }

        return ResponseHelper::make(null, __('trans.DeletedSuccessfully'));
    }

} //end of class
