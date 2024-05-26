<?php

namespace App\Console\Commands;

use App\Functions\PushNotification;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Modules\Driver\Entities\Model as Driver;
use Modules\Notification\Entities\Model as Notification;
use Modules\Ride\Entities\Model as Ride;
use Modules\Ride\Entities\NearestDriversRides;
use Modules\Ride\Http\Controllers\ConfirmController;

class checkRideAccept extends Command
{
    // The name and signature of the console command.
    protected $signature = 'rides:check-ride-accept';

    protected $description = 'check ride accept';

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

            if ($now >= $rideStartTime->addMinute()->toTimeString() && $ride->accepted == 0) {
                $old_driverId = $ride->driver_id;
                $ride->update(['driver_id' => null]);
                $this->sendNotificationToDrivers($ride,$old_driverId);
            }
        }

    }


    // send notification to (Nearest Drivers To Pickup)
    public function sendNotificationToDrivers($ride,$old_driverId)
    {
        $drivers = $ride->nearestDrivers->where('driver_id', '!=', $old_driverId)->unique('driver_id')->values();

        foreach ($drivers as $driver)
        {
            $Notification = Notification::create([
                'title_ar' => 'رحلة جديدة',
                'title_en' =>  'New Ride',
                'type' => 'public',
                'ride_id' => $ride->id,
                'driver_id' => $driver->id,
                'created_at' => now(),
            ]);

            // Determine the notification title based on the driver's language
            $title = ($driver && $driver->lang == 'ar') ? $Notification->title_ar : $Notification->title_en;
            if ($driver->lang == null) {
                $title = $Notification->title_ar;
            }

            // Send the push notification
            PushNotification::send($title, ['type' => 'public'], $Notification->driver_id, 'Driver');
        }
    }


} //end of class
