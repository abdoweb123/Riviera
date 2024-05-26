<?php

namespace App\Console\Commands;

use App\Functions\PushNotification;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Modules\Notification\Entities\Model as Notification;
use Modules\Ride\Entities\Model as Ride;

class CheckTripCreationTime extends Command
{
    // The name and signature of the console command.
    protected $signature = 'trips:check-creation-time';

    protected $description = 'Check if trip creation time exceeds more than a minute and send notification if it does';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        // Fetch rides that its start_time more than a minute ago and are missing driver_id
        $rides = Ride::query()
            ->whereNull('driver_id')
            ->whereNotNull('carType_id')
            ->where('canceled',0)
            ->where('date','=',Carbon::today())
            ->where('start_time', '<', Carbon::now()->addMinute()->toTimeString())
            ->get();

        foreach ($rides as $ride){
            $Notification = Notification::create([
                'title_ar' => 'للأسف تم إالغاء الرحلة, لايوجد سائقين متاحين الان.',
                'title_en' =>  'Unfortunately the ride has been cancelled, there are no drivers available now.',
                'type' => 'public',
                'ride_id' => $ride->id,
                'client_id' => $ride->client->id,
                'created_at' => now(),
            ]);


            // Determine the notification title based on the driver's language
            $title = ($ride->client &&$ride->client->lang == 'ar') ? $Notification->title_ar : $Notification->title_en;
            if ($ride->client->lang == null) {
                $title = $Notification->title_ar;
            }

            // Send the push notification
            PushNotification::send($title, ['type' => 'public'], $Notification->client_id, 'Client');

            $ride->update(['canceled' => 1]);
        }
    }

} //end of class
