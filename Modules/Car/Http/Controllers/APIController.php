<?php

namespace Modules\Car\Http\Controllers;

use App\Functions\ResponseHelper;
use App\Http\Controllers\BasicController;
use Modules\Car\Entities\Model as Car;
use Modules\Car\Entities\CarType;
use Modules\Car\Http\Resources\CarResource;
use Modules\Driver\Entities\Model as Driver;
use Google\Maps\DistanceMatrix\DistanceMatrixClient;
use Modules\Ride\Entities\Model as Ride;
use GuzzleHttp\Client;
use Illuminate\Support\Collection;
use Carbon\Carbon;

class APIController extends BasicController
{

    /*** Start get cars around ***/
    public function index()
    {
        $this->checkAuth();

        $ride = Ride::where('client_id', $this->Client->id)->where('completed', 0) ->orderBy('id', 'DESC') ->first();

        if(!$ride)
        {
            $response = null;
            return ResponseHelper::make($response, __('trans.Data not found'));
        }

        // Get start latitude and longitude from database
        $startLat = $ride->startAddress->latitude;
        $startLng = $ride->startAddress->longitude;

        $origins = [$startLat . ',' . $startLng];

        // Get ride info
        $rideInfo = $this->rideInfo($origins, $ride);

        $rideDistance = $rideInfo['distance']/1000; //get distance in km
        $rideDuration = $rideInfo['duration']/60;   //get duration in min

        $car_types = CarType::query()->Active()->get();

        $formattedResponse = [];

        // Loop through each element in the response array
        foreach ($car_types as $car_type) {
            // Extract relevant information
            $formattedCarTypes = [
                'id' => $car_type['id'],
                'title' => ucfirst(strtolower($car_type['title_' . lang()])),
                'image' => asset( $car_type['image'] ?? setting('logo')),
                'people_number' =>$car_type['number'],
                'cost' => number_format($car_type['price'] * Country()->currancy_value * $rideDistance,2),
                'currency' => Country()->currancy_code_en,
            ];

            // Push the formatted driver data into the formatted response array
            $formattedResponse[] = $formattedCarTypes;
        }


        // update ride end_time by adding ride_duration to ride->start_time
        $time = Carbon::createFromFormat('H:i:s', $ride->start_time);
        $additional_minutes = $rideDuration;
        $new_time = $time->addMinutes($additional_minutes);
        $end_time = $new_time->format('H:i:s');

        $ride->end_time = $end_time;
        $ride->distance = number_format($rideDistance,2);
        $ride->save();

        $rideDistance = round($rideDistance);

        $responseWithPickup = [
            'drivers' => $formattedResponse,
            'pickup' => [
                'startLat' => $startLat,
                'startLong' => $startLng,
            ],
            'ride_id'=>$ride->id,
            'distance'=>(string) $rideDistance, // from start to destination
        ];

        return ResponseHelper::make($responseWithPickup);
    }



    function rideInfo($origins, $ride)
    {
        try {
            // Get destination latitude and longitude from the database
            $endLat = $ride->endAddress->latitude;
            $endLng = $ride->endAddress->longitude;

            $destinations[] = $endLat . ',' . $endLng;

            // Use Guzzle for HTTP request to Google Maps API
            $httpClient = new \GuzzleHttp\Client();

            $url = 'https://maps.googleapis.com/maps/api/distancematrix/json?' . http_build_query([
                    'origins' => implode('|', $origins),
                    'destinations' => implode('|', $destinations),
                    'key' => env('MAP_KEY'),
                ]);

            $response = $httpClient->get($url);

            // Get the response body as a string
            $responseBody = $response->getBody()->getContents();

            $data = json_decode($responseBody, true);

            if (isset($data['rows'][0]['elements'][0]['distance'])) {
                $distance = $data['rows'][0]['elements'][0]['distance'];
                $duration = $data['rows'][0]['elements'][0]['duration'];
                // Now $distance contains the "distance" information
                // Access the "text" and "value" fields as needed
                $distanceText = $distance['text']; // "12.1 km"
                $distanceValue = $distance['value']; // 12107

                $durationText = $duration['text']; // "25 mins"
                $durationValue = $duration['value']; // 1500 (in seconds)

                return ['distance' => $distanceValue, 'duration' => $durationValue];

            } else {
                // Return calculateDistanceManually if distance information is not available
                return $this->calculateDistanceManually($origins[0], $destinations[0]);
            }
        } catch (RequestException $e) {
            // Handle request exceptions (e.g., network issues, API errors)
            // Log or handle the error as needed
            return $this->calculateDistanceManually($origins[0], $destinations[0]);
        }
    }

    // Fallback manual distance calculation using Haversine formula
    private function calculateDistanceManually($origin, $destination)
    {
        list($startLat, $startLng) = explode(',', $origin);
        list($endLat, $endLng) = explode(',', $destination);

        $earthRadius = 6371000; // Radius of the Earth in meters

        $latFrom = deg2rad($startLat);
        $lonFrom = deg2rad($startLng);
        $latTo = deg2rad($endLat);
        $lonTo = deg2rad($endLng);

        $latDelta = $latTo - $latFrom;
        $lonDelta = $lonTo - $lonFrom;

        $angle = 2 * asin(sqrt(pow(sin($latDelta / 2), 2) +
                cos($latFrom) * cos($latTo) * pow(sin($lonDelta / 2), 2)));

        $distance = $angle * $earthRadius; // Distance in meters

        // Assuming average speed of 40 km/h (11.11 m/s) to estimate duration
        $averageSpeed = 11.11; // Average speed in meters per second
        $duration = $distance / $averageSpeed; // Duration in seconds

        return ['distance' => $distance, 'duration' => $duration];
    }
    /*** End get cars around ***/






}//end of class
