<?php

use Illuminate\Support\Facades\Broadcast;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

Broadcast::channel('message.{id}', function ($user, $id) {
    if (auth('applicant')->check()) {
        return auth('applicant')->user();
    } elseif (auth('employer')->check()) {
        return auth('employer')->user();
    }
}, ['guards' => ['applicant', 'employer']]);
