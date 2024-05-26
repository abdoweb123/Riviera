<?php

namespace App\Functions;

use Modules\Order\Entities\Model as Order;

class WhatsApp
{
    public static function SendOTP($phone)
    {
        $otp = rand(100000, 999999);

        $body = '';
        $body .= '%0a *'.env('APP_NAME').'* %0a';
        $body .= '%0a *Your Verification Code Is* '.$otp.' %0a';
        $body .= '%0a Powered By *Emcan Solutions*';

        self::SendWhatsApp($phone, $body);

        return $otp;
    }

    public static function SendOrder($order_id)
    {
        $Order = Order::query()->with(['Payment', 'Branch', 'Employer', 'Client', 'Items' => ['Service']])->first();
        $message = '%0a *An Order Has Been Placed By '.$Order->Client->name.' ('.env('APP_NAME').')* %0a';
        $message .= '%0a *Order Number :* '.$Order->id;

        $message .= '%0a *Branch :* '.$Order->Branch->title_en;

        $message .= '%0a *Client Name :* '.$Order->Client->name;
        $message .= '%0a *Client Phone :* '.$Order->Client->phone;

        $message .= '%0a *Order Date :* '.$Order->date;
        $message .= '%0a *Order Time :* '.date('g:i a', strtotime($Order->start_time)).' - '.date('g:i a', strtotime($Order->end_time));

        $message .= '%0a';
        $message .= '%0a *Services :* ';
        foreach ($Order->Items as $item) {
            $message .= '%0a *Item :* '.strip_tags($item->Service->title_en);
            $message .= '%0a *Price :* '.$item->price;
        }

        $message .= '%0a';
        $message .= '%0a *SubTotal :* '.$Order->sub_total;
        if ($Order->discount > 0) {
            $message .= '%0a *Discount :* '.$Order->discount;
        }
        if ($Order->vat > 0) {
            $message .= '%0a *VAT :* '.$Order->vat;
        }
        $message .= '%0a *NetTotal :* '.$Order->net_total;
        $message .= '%0a  %0a';

        $message .= '%0a *Map Link :* https://maps.google.com/?q='.$Order->Branch->lat.','.$Order->Branch->long;

        $message .= '%0a *Powered By Emcan Solutions* %0a';

        self::SendWhatsApp($Order->Client->phone_code.$Order->Client->phone, $message);
        self::SendWhatsApp(Setting('whatsapp'), $message);
    }

    public static function GetToken()
    {
        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => 'https://emcan.bh/api/UltraCredentials',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_POSTFIELDS => 'token=zuvzajw7goMh20q5YVu0&domain='.env('APP_URL'),
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_HTTPHEADER => [
                'content-type: application/x-www-form-urlencoded',
            ],
        ]);
        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);

        return json_decode($response);
    }

    public static function SendWhatsApp($numbers, $message)
    {
        $EmcanWhats = self::GetToken();
        $instance = $EmcanWhats->instance;
        $token = $EmcanWhats->token;
        if ($EmcanWhats->active) {
            $numbers = is_array($numbers) ? $numbers : [$numbers];
            foreach ($numbers as $number) {
                $number = str_replace('++', '+', $number);
                $curl = curl_init();
                curl_setopt_array($curl, [
                    CURLOPT_URL => 'https://api.ultramsg.com/'.$instance.'/messages/chat',
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => '',
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 30,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => 'POST',
                    CURLOPT_POSTFIELDS => "token=$token&to=$number&body=$message&priority=1&referenceId=",
                    CURLOPT_HTTPHEADER => [
                        'content-type: application/x-www-form-urlencoded',
                    ],
                ]);
                $response = curl_exec($curl);
                $err = curl_error($curl);
                curl_close($curl);
            }
        }
    }
}
