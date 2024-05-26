<?php

namespace App\Console\Commands;

use App\Functions\PushNotification;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Modules\Notification\Entities\Model as Notification;
use Modules\Ride\Entities\Model as Ride;

class sendNotificationBeforeRideStart extends Command
{
    // The name and signature of the console command.
    protected $signature = 'rides:send-Notification-Before-Ride-Start';

    protected $description = 'send Notification Before Ride Start';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        // Get current time
        $now = Carbon::now()->toTimeString();

        // Fetch rides for today that are not canceled and not completed
        $rides = Ride::query()
            ->whereNotNull('driver_id')
            ->whereNotNull('carType_id')
            ->where('canceled', 0)
            ->where('completed', 0)
            ->where('date', '=', Carbon::today())
            ->get();

        foreach ($rides as $ride) {
            // Convert the ride's start_time to a Carbon instance
            $rideStartTime = Carbon::createFromFormat('H:i:s', $ride->start_time);


            if (!$ride->notification_sent_60 && $now >= $rideStartTime->copy()->subMinutes(60)->toTimeString() && $now < $rideStartTime->toTimeString()) {   // 60 minutes before ride start
                $title_ar = 'رحلتك ستبدأ خلال 60 دقيقة';
                $title_en = 'Your Ride will start in 60 minutes';
                $this->sendNotifications($ride, $title_ar, $title_en, 'Driver', $ride->driver);
                $this->sendNotifications($ride, $title_ar, $title_en, 'Client', $ride->client);
                $ride->update(['notification_sent_60' => true]);
            }

            if  (!$ride->notification_sent_30 && $now >= $rideStartTime->copy()->subMinutes(30)->toTimeString() && $now < $rideStartTime->toTimeString()) {   // 30 minutes before ride start
                $title_ar = 'رحلتك ستبدأ خلال 30 دقيقة';
                $title_en = 'Your Ride will start in 30 minutes';
                $this->sendNotifications($ride, $title_ar, $title_en, 'Driver', $ride->driver);
                $this->sendNotifications($ride, $title_ar, $title_en, 'Client', $ride->client);
                $ride->update(['notification_sent_30' => true]);
            }
            if (!$ride->notification_sent_10 && $now >= $rideStartTime->subMinutes(10)->toTimeString()){
                $title_ar = 'رحلتك ستبدأ خلال 10 دقيقة';
                $title_en = 'Your Ride will start in 10 minutes';
                $this->sendNotifications($ride, $title_ar, $title_en, 'Driver', $ride->driver);
                $this->sendNotifications($ride, $title_ar, $title_en, 'Client', $ride->client);
                $ride->update(['notification_sent_10' => true]);
            }


        }
    }


    private function sendNotifications($ride, $title_ar, $title_en, $recipientType, $rideType)
    {
        $columnName = "{$recipientType}_id";
        $Notification = Notification::create([
            'title_ar' => $title_ar,
            'title_en' => $title_en,
            'type' => 'public',
            'ride_id' => $ride->id,
            $columnName => $rideType->id,
            'created_at' => now(),
        ]);


        // Determine the notification title based on the driver's language
        $title = ($rideType && $rideType->lang == 'ar') ? $Notification->title_ar : $Notification->title_en;
        if ($rideType->lang == null) {
            $title = $Notification->title_ar;
        }

        // Send the push notification
        PushNotification::send($title, ['type' => 'public'], $rideType->id, $recipientType);
    }


} //end of class
