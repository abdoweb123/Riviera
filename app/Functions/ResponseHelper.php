<?php

namespace App\Functions;

use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Route;

class ResponseHelper
{
    public static function make($data, $msg = '', $success = true, $statusCode = 200)
    {
        $response = [
            'token' => request()->bearerToken(),
            'msg' => $msg,
            'statusCode' => $statusCode,
            'success' => $success,
            'payload' => $data,
        ];

        if (Route::currentRouteName() == 'home' || str_contains(Route::currentRouteName(), 'order')) {
            $response['app_lock'] = false;
            $response['ios_version'] = setting('ios_version');
            $response['android_version'] = setting('android_version');
            $response['ios_link'] = setting('apple');
            $response['android_link'] = setting('android');
            $response['app_lock_msg'] = __('trans.app_lock_msg');
        }
        throw new HttpResponseException(response()->json($response, $statusCode));
    }
}
