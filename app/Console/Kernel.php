<?php

namespace App\Console;

use App\Console\Commands\BillsPayable;
use App\Console\Commands\SendMeetingsContent;
use App\Console\Commands\sendNotificationBeforeRideStart;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected $commands = [
        SendMeetingsContent::class,
        BillsPayable::class,
        Commands\CheckTripCreationTime::class,
        sendNotificationBeforeRideStart::class,
    ];

    protected function schedule(Schedule $schedule)
    {
        $schedule->command('trips:check-creation-time')->everyMinute();
        $schedule->command('rides:send-Notification-Before-Ride-Start')->everyMinute();
    }

    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }

} //end of class
