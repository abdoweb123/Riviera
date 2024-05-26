<?php

namespace App\Http\Controllers\Admin;

use App\Functions\WhatsApp;
use App\Http\Controllers\BasicController;
use App\Http\Requests\Admin\ForgetRequest;
use App\Http\Requests\Admin\VerifyRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Modules\Admin\Entities\Model;

class ForgetController extends BasicController
{
    public function index()
    {
        return view('Admin.forget');
    }

    public function forget(ForgetRequest $request)
    {
        $Model = Model::where('phone', $request->phone)->where('phone_code', $request->phone_code)->firstorfail();
        $code = $Model->code;
        if (! $code) {
            $code = WhatsApp::SendOTP($request->phone_code.$request->phone);
            Model::where('phone', $request->phone)->where('phone_code', $request->phone_code)->update([
                'code' => $code,
            ]);
        }

        return redirect()->route('admin.code', ['uuid' => $Model->uuid]);
    }

    public function resend(ForgetRequest $request)
    {
        $Model = Model::where('phone', $request->phone)->where('phone_code', $request->phone_code)->firstorfail();

        $code = WhatsApp::SendOTP($request->phone_code.$request->phone);
        Model::where('phone', $request->phone)->where('phone_code', $request->phone_code)->update([
            'code' => $code,
        ]);

        return redirect()->route('admin.code', ['uuid' => $Model->uuid]);
    }

    public function code($uuid)
    {
        $Model = Model::where('uuid', $uuid)->firstorfail();

        return view('Admin.code', compact('Model'));
    }

    public function verify(VerifyRequest $request)
    {
        $Model = Model::where('phone', $request->phone)->where('phone_code', $request->phone_code)->firstorfail();

        return redirect()->route('admin.reset', ['uuid' => $Model->uuid]);
    }

    public function reset($uuid)
    {
        $Model = Model::where('uuid', $uuid)->firstorfail();

        return view('Admin.reset', compact('Model'));
    }

    public function update($uuid, Request $request)
    {
        $Model = Model::where('uuid', $uuid)->firstorfail();
        $Model->code = null;
        $Model->password = Hash::make($request->password);
        $Model->save();
        toast(__('trans.profileUpdated'), 'success');

        return redirect()->route('admin.login');
    }
}
