<?php

namespace Modules\Setting\Http\Controllers;

use App\Functions\ResponseHelper;
use App\Http\Controllers\BasicController;
use Modules\Branch\Entities\Model as Branch;
use Modules\Setting\Entities\Model;

class APIController extends BasicController
{
    public function video()
    {
        return ResponseHelper::make(asset(setting('video')));
    }

    public function contact()
    {
        $keys = ['phone', 'whatsapp', 'website']; // Specify the keys you want to retrieve

        $settings = Model::whereIn('key', $keys)->get();

        return ResponseHelper::make([

            'info' => Model::select(setting('phone'),setting('whatsapp'), setting('website'))->get(),
            'social' => [
                [
                    'key' => 'facebook',
                    'link' => setting('facebook'),
                    'icon' => asset('icons/facebook.png'),
                ],
                [
                    'key' => 'instagram',
                    'link' => setting('instagram'),
                    'icon' => asset('icons/instagram.png'),
                ],
                [
                    'key' => 'tiktok',
                    'link' => setting('tiktok'),
                    'icon' => asset('icons/tiktok.png'),
                ],
                [
                    'key' => 'twitter',
                    'link' => setting('twitter'),
                    'icon' => asset('icons/twitter.png'),
                ],
                [
                    'key' => 'snapchat',
                    'link' => setting('snapchat'),
                    'icon' => asset('icons/snapchat.png'),
                ],
                [
                    'key' => 'linkedin',
                    'link' => setting('linkedin'),
                    'icon' => asset('icons/linkedin.png'),
                ],
            ],
        ]);
    }
}
