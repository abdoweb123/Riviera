<?php

namespace Modules\Ride\Http\Controllers;

use App\Functions\PushNotification;
use App\Functions\ResponseHelper;
use App\Http\Controllers\BasicController;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Modules\Driver\Http\Resources\DriverDetailsResource;
use Modules\Ride\Entities\CancellationReasons;
use Modules\Ride\Entities\Model as Ride;
use Modules\Ride\Entities\NearestDriversRides;
use Modules\Ride\Entities\RideCancellation;
use Modules\Ride\Http\Requests\RideRequest;
use Modules\Ride\Entities\Model;
use Modules\Address\Entities\Model as Address;
use Modules\Driver\Entities\Model as Driver;
use Modules\Ride\Http\Requests\RideConfirmRequest;
use Modules\Car\Entities\CarType;
use Modules\Ride\Http\Resources\RideDetailsResource;
use Modules\Ride\Http\Resources\RideDriverResource;
use Modules\Ride\Http\Resources\RideGetResource;
use Modules\Ride\Http\Resources\RideResource;
use Modules\Ride\Http\Resources\RideRouteResource;
use Modules\Notification\Entities\Model as Notification;

class ConfirmController extends BasicController
{
    /*** confirm ride ***/
    public function confirm(RideConfirmRequest $request)
    {
        $this->CheckAuth();

        $ride = Model::query()
            ->where('client_id',$this->Client->id)
            ->whereNull('driver_id')
            ->whereNull('carType_id')
            ->latest()
            ->first();

        if(!$ride)
        {
            $response = null;
            return ResponseHelper::make($response, __('trans.Data not found'), false,404);
        }

        $carType = CarType::where('id',$request->carType_id)->firstOrFail();

//        $driver_id = (int)$request->driver_id;
        $payment_id = (int)$request->payment_id;
        $carTypeId = (int)$request->carType_id;
        $cost = number_format($ride->distance * $carType->price, 3);

        $ride->update([
//            'driver_id'=>$driver_id,
            'payment_id'=> $payment_id,
            'carType_id'=> $carTypeId,
            'cost'=> $cost,
            'requested'=>1
        ]);

        // send ride to driver
        $this->sendRideToDrivers($ride);

        $response['ride'] = $ride;
        $response['token'] = request()->bearerToken();

        return ResponseHelper::make($response, __('trans.updatedSuccessfully'));
    }


    // To send notifications to drivers when ride confirmed
    private function sendRideToDrivers($ride)
    {
        // get online drivers who doesn't have rides in current ride time
        $drivers = $this->getAllAvailableDrivers($ride);

        // get Nearest Drivers To Pickup
        $getNearestDriversToPickup = $this->getNearestDriversToPickup($drivers,$ride);

        // get Nearest Drivers To Pickup
        foreach ($getNearestDriversToPickup as $driver)
        {
            NearestDriversRides::query()->create([
                'ride_id'=> $ride->id,
                'driver_id'=> $driver->id,
            ]);
        }

        // send notification to drivers
        $this->sendNotificationToDrivers($getNearestDriversToPickup,$ride);
    }


    // get online drivers who doesn't have rides in current ride time
    private function getAllAvailableDrivers($ride)
    {
        $drivers = Driver::Active()
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->whereDoesntHave('rides', function ($subQuery) use ($ride) {
                $subQuery->whereDate('date', '=', $ride->date)
                    ->where('start_time', '<=', $ride->start_time)
                    ->where('end_time', '>=', $ride->start_time);
            })
            ->whereHas('car', function ($q) {
                $q->Active();
            })
            ->with('carType')->get();

        return $drivers;
    }


    // get Nearest Drivers To Pickup
    private function getNearestDriversToPickup($drivers,$ride,$kmAround=15000)
    {
        // Get start latitude and longitude from database
        $startLat = $ride->startAddress->latitude;
        $startLng = $ride->startAddress->longitude;

        $origins = [$startLat . ',' . $startLng];

        // Create destinations array from driver locations
        foreach ($drivers as $driver) {
            $destinations[] = $driver->latitude . ',' . $driver->longitude;
        }

        // Join origins and destinations into comma-separated strings
        $origins_str = implode('|', $origins);
        $destinations_str = implode('|', $destinations);

        // Google Maps Distance Matrix API endpoint
        $api_key =  env('MAP_KEY');
        $url = "https://maps.googleapis.com/maps/api/distancematrix/json?units=metric&origins=$origins_str&destinations=$destinations_str&key=$api_key";

        // Fetch the response from Google Maps API
        $response = file_get_contents($url);
        $data = json_decode($response, true);

        if ($data['status'] === 'OK') {
            $filtered_drivers = [];
            foreach ($data['rows'][0]['elements'] as $index => $element) {
                if ($element['status'] === 'OK' && $element['distance']['value'] <= $kmAround) {
                    // Add driver to filtered list if within 15 km (15000 meters)
                    $filtered_drivers[] = $drivers[$index];
                }
            }


           if (count($filtered_drivers) == 0 && $kmAround == 15000){
               return $this->getNearestDriversToPickup($drivers,$ride,$kmAround=30000);
           }
           else {
               return $filtered_drivers;
           }

        } else {
            // Handle API error
            return $this->getNearestDriversToPickupManually($drivers, $ride, $kmAround);
        }
    }

    // Manual fallback method to calculate distances using the Haversine formula
    private function getNearestDriversToPickupManually($drivers, $ride, $kmAround)
    {
        // Convert kilometers to meters
        $radius = $kmAround / 1000;

        $startLat = $ride->startAddress->latitude;
        $startLng = $ride->startAddress->longitude;

        $filtered_drivers = [];

        foreach ($drivers as $driver) {
            $driverLat = $driver->latitude;
            $driverLng = $driver->longitude;

            // Calculate the distance using the Haversine formula
            $distance = $this->haversineDistance($startLat, $startLng, $driverLat, $driverLng);

            if ($distance <= $radius) {
                $filtered_drivers[] = $driver;
            }
        }

        if (count($filtered_drivers) == 0 && $kmAround == 15000) {
            return $this->getNearestDriversToPickupManually($drivers, $ride, 30000);
        } else {
            return $filtered_drivers;
        }
    }

    // Haversine formula to calculate the distance between two points
    private function haversineDistance($lat1, $lon1, $lat2, $lon2)
    {
        $earthRadius = 6371; // Earth's radius in kilometers

        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);

        $a = sin($dLat / 2) * sin($dLat / 2) +
            cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
            sin($dLon / 2) * sin($dLon / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return $earthRadius * $c; // Distance in kilometers
    }


    // send notification to (Nearest Drivers To Pickup)
    private function sendNotificationToDrivers($drivers, $ride)
    {
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
