<?php

namespace App\Functions;

use Illuminate\Support\Facades\DB;

class PushNotification
{
    public static function send($message, $data, $id = null, $Type = 'Client')
    {
        $headers = [];
        $headers[] = 'Content-Type: application/json';
        $headers[] = 'Authorization: key='.env('FCM');
        $DeviceTokens = [];

        if ($Type == 'Client') {
            if ($id) {
                $DeviceTokens = DB::table('client_device_tokens')->where('client_id', $id)->pluck('device_token');
            } else {
                $DeviceTokens = DB::table('client_device_tokens')->pluck('device_token');
            }
        } elseif ($Type == 'Driver') {
            if ($id) {
                $DeviceTokens = DB::table('driver_device_tokens')->where('driver_id', $id)->pluck('device_token');
            } else {
                $DeviceTokens = DB::table('driver_device_tokens')->pluck('device_token');
            }
        }
        foreach ($DeviceTokens as $token) {
            $notification = [
                'to' => $token,
                'notification' => [
                    'title' => env('APP_NAME'),
                    'body' => $message,
                    'sound' => 'default',
                    'badge' => '1',
                ],
                'priority' => 'high',
                'data' => $data + ['user_type' => $Type],
                'content_available' => true,
            ];
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($notification));
            $response = curl_exec($ch);
            curl_close($ch);
        }
    }


}
