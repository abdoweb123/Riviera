<?php

namespace Modules\Admin\Http\Controllers;

use App\Functions\ResponseHelper;
use App\Functions\Upload;
use App\Http\Controllers\BasicController;
use Illuminate\Http\Request;
use Modules\Admin\Entities\Model as Admin;
use Modules\Admin\Http\Requests\CheckExistsRequest;
use Modules\Admin\Http\Requests\DeviceTokenRequest;
use Modules\Admin\Http\Requests\LoginRequest;
use Modules\Admin\Http\Requests\OldPasswordRequest;
use Modules\Admin\Http\Requests\RegisterRequest;
use Modules\Admin\Http\Requests\UpdateProfileRequest;
use Modules\Admin\Http\Resources\AdminResource;
use Modules\Country\Entities\Country;

class APIController extends BasicController
{
    public function Login(LoginRequest $request)
    {
        $phone_code = str_replace('+', '', $request->phone_code);
        if (Auth('admin')->attempt($request->only('phone', 'password') + ['deleted_at' => null, 'phone_code' => $phone_code, 'status' => 1])) {
            $Model = Auth('admin')->user();
        } elseif (Auth('admin')->attempt($request->only('email', 'password') + ['deleted_at' => null, 'status' => 1])) {
            $Model = Auth('admin')->user();
        } else {
            return ResponseHelper::make(null, __('trans.emailPasswordIncorrect'), false, 404);
        }

        if (! $Model->DeviceTokens()->where(['device_token' => $request->device_token])->exists()) {
            $Model->DeviceTokens()->create([
                'device_token' => $request->device_token,
            ]);
        }
        $response['token'] = $Model->createToken('AdminToken')->plainTextToken;
        $response['admin'] = AdminResource::make($Model);

        return ResponseHelper::make($response, __('trans.loginSuccessfully'));
    }

    public function Register(RegisterRequest $request)
    {
        $phone_code = $Country->phone_code;
        $Country = Country::where('phone_code', 'LIKE', "%{$phone_code}%")->first();
        $code = $Country->country_code;

        $phone_code = str_contains($phone_code, '+') ? $phone_code : '+'.$phone_code;
        $Model = Admin::where('phone', $request->phone)->where('phone_code', $phone_code)->first();
        if ($Model) {
            $Model->branch_id = $request->branch_id;
            $Model->name = $request->name;
            $Model->email = $request->email;
            $Model->password = bcrypt($request['password']);
            $Model->status = 1;
            $Model->deleted_at = null;
            $Model->save();
            $Model = Admin::where('phone', $request->phone)->where('phone_code', $phone_code)->first();
        } else {
            $Model = Admin::create([
                'branch_id' => $request->branch_id,
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
        $success['token'] = $Model->createToken('AdminToken')->plainTextToken;
        $success['user'] = AdminResource::make($Model);

        return ResponseHelper::make($success, __('trans.addedSuccessfully'));
    }

    public function DeviceToken(DeviceTokenRequest $request)
    {
        $this->CheckAuth();
        if (! $this->Admin->DeviceTokens()->where(['device_token' => $request->device_token])->exists()) {
            $this->Admin->DeviceTokens()->create([
                'device_token' => $request->device_token,
            ]);
        }
        $response['token'] = request()->bearerToken();
        $response['admin'] = AdminResource::make($this->Admin);

        return ResponseHelper::make($response, __('trans.addedSuccessfully'));
    }

    public function profile()
    {
        $this->CheckAuth();
        $response['token'] = request()->bearerToken();
        $response['admin'] = AdminResource::make($this->Admin->refresh());

        return ResponseHelper::make($response);

    }

    public function UpdateProfile(UpdateProfileRequest $request)
    {
        $this->CheckAuth();
        $Model = $this->Admin;
        if (count($request->except(['image', 'device_token']))) {
            $Model->update($request->except(['image', 'device_token']));
        }
        if ($request->image) {
            $Model->update(['image' => Upload::UploadFile($request->image, 'Admins')]);
        }

        $response['token'] = request()->bearerToken();
        $response['admin'] = AdminResource::make($Model->refresh());

        return ResponseHelper::make($response, __('trans.updatedSuccessfully'));

    }

    public function UpdatePassword(Request $request)
    {
        $phone_code = str_replace('+', '', $request->phone_code);
        $Model = Admin::where('phone', $request->phone)->where('phone_code', $phone_code)->firstorfail();
        if (request('password')) {
            Admin::where('phone', $request->phone)->where('phone_code', $phone_code)->update(['password' => bcrypt(request('password'))]);
        }

        $response['token'] = request()->bearerToken();
        $response['admin'] = AdminResource::make($Model);

        return ResponseHelper::make($response, __('trans.updatedSuccessfully'));

    }

    public function UpdateOldPassword(OldPasswordRequest $request)
    {
        $this->CheckAuth();
        $this->Admin->password = bcrypt(request('password'));
        $this->Admin->save();
        $response['token'] = request()->bearerToken();
        $response['admin'] = AdminResource::make($this->Admin);

        return ResponseHelper::make($response, __('trans.updatedSuccessfully'));

    }

    public function UpdateImage(Request $request)
    {
        $this->CheckAuth();
        $Model = $this->Admin;
        if (request('image')) {
            $Model->update(['image' => Upload::UploadFile(request('image'), 'Admins')]);
        }

        $response['token'] = request()->bearerToken();
        $response['admin'] = AdminResource::make($Model->refresh());

        return ResponseHelper::make($response, __('trans.updatedSuccessfully'));

    }

    public function CheckNumber(CheckExistsRequest $request)
    {
        $phone_code = str_replace('+', '', $request->phone_code);
        if ($request->phone) {
            $Model = Admin::where('phone', $request->phone)->where('phone_code', $phone_code)->first();
        } elseif ($request->email) {
            $Model = Admin::where('email', $request->email)->first();
        }
        $response['exist'] = $Model ? 1 : 0;
        $response['token'] = $Model ? $Model->createToken('AdminToken')->plainTextToken : null;
        $response['admin'] = $Model ? AdminResource::make($Model) : null;

        return ResponseHelper::make($response, $Model ? __('trans.already_exist') : __('trans.dont_exist'));
    }

    public function Logout()
    {
        $this->CheckAuth();
        $this->Admin->DeviceTokens()->where('device_token', request()->device_token)->delete();
        $this->Admin->tokens()->where('token', request()->bearerToken())->delete();

        return ResponseHelper::make(null, __('trans.logoutSuccessfully'));
    }

    public function lang($lang)
    {
        $this->CheckAuth();
        $this->Admin->lang = $lang;
        $this->Admin->save();

        return ResponseHelper::make(null, __('trans.addedSuccessfully'));
    }

    public function DeleteAccount()
    {
        if (auth('sanctum')->check()) {
            $this->CheckAuth();
            if ($this->Admin) {
                $this->Admin->DeviceTokens()->where('device_token', request()->device_token)->delete();
                $this->Admin->tokens()->delete();
                $this->Admin->delete();
            }
        } elseif (request()->phone) {
            $Admin = Admin::where('phone', request()->phone)->firstorfail();
            if ($Admin) {
                $Admin->DeviceTokens()->delete();
                $Admin->tokens()->delete();
                $Admin->delete();
            }
        } elseif (request()->admin_id) {
            $Admin = Admin::where('id', request()->admin_id)->firstorfail();
            if ($Admin) {
                $Admin->DeviceTokens()->delete();
                $Admin->tokens()->delete();
                $Admin->delete();
            }
        }

        return ResponseHelper::make(null, __('trans.DeletedSuccessfully'));
    }
}
