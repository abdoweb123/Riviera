<?php

namespace Modules\Ride\Http\Controllers;

use App\Functions\PushNotification;
use App\Functions\ResponseHelper;
use App\Http\Controllers\BasicController;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Modules\Driver\Http\Resources\DriverDetailsResource;
use Modules\Ride\Entities\CancellationReasons;
use Modules\Ride\Entities\Model as Ride;
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

class APIController extends BasicController
{

    /*** store ride ***/
    public function store(RideRequest $request)
    {

        $this->CheckAuth();

        // update lang of client
        $this->Client->lang = $request->lang;
        $this->Client->save();

        // If there is no $request->ride_time put current time
        if (empty($request->ride_time) || !\Carbon\Carbon::hasFormat($request->ride_time, 'H:i')) {
            $request->ride_time = \Carbon\Carbon::now()->format('H:i');
        }

        $result = $this->checkDateTime($request->ride_date, $request->ride_time);

        if ($result == 0) {
            $response = null;
            return ResponseHelper::make($response, __('trans.date_entered_in_past'), false,404);
        }


        // To delete any un-confirmed ride
        $non_confirmed_rides = Model::query()
            ->where('client_id',$this->Client->id)
            ->whereNull('driver_id')->whereNull('carType_id')->delete();

        if($request->startAddress_id){
            $checkStartAddress = Address::findOrFail($request->startAddress_id);
        }else{
            $location = $this->getLocationByLatLong($request->start_latitude,$request->start_longitude);

            $checkStartAddress = Address::create([
                'client_id'=>$this->Client->id,
                'note_ar'=>$location['ar'],
                'note_en'=>$location['en'],
                'latitude'=>$request->start_latitude,
                'longitude'=>$request->start_longitude,
                'status'=>1
            ]);
        }
        if($request->endAddress_id){
            $checkEndAddress = Address::findOrFail($request->endAddress_id);
        }else{
            $location = $this->getLocationByLatLong($request->end_latitude,$request->end_longitude);

            $checkEndAddress = Address::create([
                'client_id'=>$this->Client->id,
                'note_ar'=>$location['ar'],
                'note_en'=>$location['en'],
                'latitude'=>$request->end_latitude,
                'longitude'=>$request->end_longitude,
                'status'=>1
            ]);
        }

        // to check if client have any rides in the time he entered
        $old_ride = Model::query()
            ->where('client_id',$this->Client->id)
            ->whereDate('date', '=', $request->ride_date)
            ->where('start_time', '<=', $request->ride_time)
            ->where('end_time', '>=', $request->ride_time)
            ->where('canceled',0)
            ->first();

        if ($old_ride){
            $response = null;
            return ResponseHelper::make($response, __('trans.You_have_already_another_ride_at_this_time_please_choose_another_time'), false,404);
        }

        $origins = [$checkStartAddress->latitude . ',' . $checkStartAddress->longitude];
        $destinations = [$checkEndAddress->latitude . ',' . $checkEndAddress->longitude];

        $rideInfo = $this->rideInfo($origins, $destinations);

        $rideDistance = $rideInfo['distance']/1000; //get distance in km
        $rideDuration = $rideInfo['duration']/60;   //get duration in min


        // update ride end_time by adding ride_duration to ride->start_time
        $time = Carbon::createFromFormat('H:i', $request->ride_time);
        $additional_minutes = $rideDuration;
        $new_time = $time->addMinutes($additional_minutes);
        $end_time = $new_time->format('H:i');

        $response['ride'] = Model::create([
            'client_id'=>$this->Client->id,
            'startAddress_id'=> $checkStartAddress->id,
            'endAddress_id'=> $checkEndAddress->id,
            'date'=>$request->ride_date,
            'start_time'=>$request->ride_time,
            'end_time'=>$end_time,
            'distance'=>number_format($rideDistance,2),
        ]);

        $response['token'] = request()->bearerToken();

        return ResponseHelper::make($response, __('trans.addedSuccessfully'));
    }

    /*** get rides (future || past) ***/
    public function getRides()
    {
        $this->checkAuth();

        $currentDate = Carbon::now()->toDateString();
        $currentTime = Carbon::now()->toTimeString();

        $response['rides'] = Ride::where('client_id',$this->Client->id)->orderBy('date');


        if(request('type') && in_array(request('type'),['future','past']))
        {
            if(request('type')=='future')
            {
                $status = 0; //completed = 0

                $response['rides'] = $response['rides']->where('completed',$status)
                    ->where(function ($query) use ($currentDate, $currentTime) {
                        $query->where('date', '>', $currentDate)
                            ->orWhere(function ($query) use ($currentDate, $currentTime) {
                                $query->where('date', '=', $currentDate)
                                    ->where('start_time', '>', $currentTime);
                            });
                    })
                    ->get();
            }

            else if(request('type')=='past')
            {
                $status = 1; //completed = 1
                $response['rides'] = $response['rides']->where('completed',$status)->where('date','<=',$currentDate)->orderBy('id','DESC')->get();
            }

        }else{
            $response['error'] = __('trans.type_required');
            return ResponseHelper::make($response, __('trans.somethingWrong'));
        }

        $response['rides'] = RideGetResource::collection($response['rides']);

        $response['token'] = request()->bearerToken();

        return ResponseHelper::make($response, __('trans.Data_fetched_successfully'));
    }


    /*** get ride_details by id ***/
    public function getRideDetails()
    {
        $this->checkAuth();

        $validator = Validator::make(request()->all(), [
            'ride_id' => 'required|integer|exists:rides,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors(),
            ], 422);
        }

        // Continue with your API logic if validation passes

        $ride = Ride::where('client_id',$this->Client->id)->findOrFail(request('ride_id'));
        $rideDetails = new RideDetailsResource($ride);

        return ResponseHelper::make($rideDetails, __('trans.Data_fetched_successfully'));
    }


    /*** get route of ride ***/
    public function getRideRoute()
    {
        if(!auth('sanctum')->check())
        {
            return ResponseHelper::make(null,'guest');
        }

        $currentDate = Carbon::now()->toDateString();
        $currentTime = Carbon::now()->toTimeString();
        // Subtract 30 minutes from the current time
        $thirtyMinutesAgo = Carbon::now()->subMinutes(30)->toTimeString();

        $this->checkAuth();

        $ride = Ride::where('client_id',$this->Client->id)
            ->where('requested',1)
            ->where('completed',0)
            ->where('canceled',0)
            ->where('date','=',$currentDate)
            ->where('start_time', '>=', $thirtyMinutesAgo)
            ->orderBy('start_time', 'DESC')
            ->first();

        if(!$ride)
        {
            $response = null;
            return ResponseHelper::make($response, __('trans.Data not found'));
        }

        if($ride->driver_id == null)
        {
            $ride = new RideRouteResource($ride);
            return ResponseHelper::make($ride, __('trans.waiting_for_approval_drivers'));
        }

        // ** Distance Calculation Logic **
        try {
            // Attempt to use Google Maps API for all users
            $httpClient = new \GuzzleHttp\Client();

            $url = 'https://maps.googleapis.com/maps/api/distancematrix/json?' . http_build_query([
                    'origins' => $ride->startAddress->latitude . ',' . $ride->startAddress->longitude,
                    'destinations' => $ride->driver->latitude . ',' . $ride->driver->longitude,
                    'key' => env('MAP_KEY'),
                ]);

            $response = $httpClient->get($url);

            // Get the response body as a string
            $responseBody = $response->getBody()->getContents();

            $data = json_decode($responseBody, true);

            if (isset($data['rows'][0]['elements'][0]['distance'])) {
                $distance = $data['rows'][0]['elements'][0]['distance'];
                $rideDistance = $distance['value'] / 1000; // Convert distance to km
            } else {
                // Fallback to manual calculation if Google Maps API fails
                $startLat = $ride->startAddress->latitude;
                $startLng = $ride->startAddress->longitude;
                $driverLat = $ride->driver->latitude;
                $driverLng = $ride->driver->longitude;

                $distance = $this->distance($startLat, $startLng, $driverLat, $driverLng);
                $rideDistance = number_format($distance, 2); // Format distance in km
            }
        } catch (RequestException $e) {
            // Fallback to manual calculation on exception
            $startLat = $ride->startAddress->latitude;
            $startLng = $ride->startAddress->longitude;
            $driverLat = $ride->driver->latitude;
            $driverLng = $ride->driver->longitude;

            $distance = $this->distance($startLat, $startLng, $driverLat, $driverLng);
            $rideDistance = number_format($distance, 2); // Format distance in km
        }

        // ** No duration calculation possible without real-time data **
        $ride->newDistance = $rideDistance;
        $ride->newDuration = null; // Set duration to null as it's unavailable

        $ride = new RideRouteResource($ride);

        return ResponseHelper::make($ride, __('trans.Data_fetched_successfully'));
    }

    // Function to calculate distance using Haversine formula (unchanged)
    function distance($lat1, $lon1, $lat2, $lon2) {
        $earthRadius = 6371; // Earth's radius in kilometers

        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);

        $a = sin($dLat/2) * sin($dLat/2) + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * sin($dLon/2) * sin($dLon/2);
        $c = 2 * atan2(sqrt($a), sqrt(1-$a));

        return $earthRadius * $c;
    }

    /*** get driver details ***/
    public function driverDetails(Request $request)
    {
        $this->checkAuth();

        $validator = Validator::make(request()->all(), [
            'driver_id' => 'required|integer|exists:drivers,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors(),
            ], 422);
        }

        $driver = Driver::where('id',$request->input('driver_id'))->firstOrFail();

        $driver = new DriverDetailsResource($driver);

        return ResponseHelper::make($driver, __('trans.Data_fetched_successfully'));
    }


    /*** Get driver location ***/
    public function getDriverLocation()
    {
        if(!auth('sanctum')->check())
        {
            return ResponseHelper::make(null,'guest');
        }

        $this->checkAuth();

        $ride = Ride::where('client_id',$this->Client->id)
            ->where('requested',1)
            ->where('completed',0)->first();

        if(!$ride)
        {
            $response = null;
            return ResponseHelper::make($response, __('trans.Data not found'));
        }

        $ride = new RideDriverResource($ride);

        return ResponseHelper::make($ride, __('trans.updatedSuccessfully'));
    }


    /*** get cancellation reasons  ***/
    public function cancellationReasons()
    {
        $this->checkAuth();
        $response['question'] = __('trans.what_went_wrong');
        $response['cancellation_reasons'] = CancellationReasons::select('id','reason_'.lang().' as reason')->get();
        return ResponseHelper::make($response, __('trans.Data_fetched_successfully'));
    }


    /*** store cancellation reason && cancel ride ***/
    public function cancellationReason(Request $request)
    {
        $this->checkAuth();

        $validator = Validator::make(request()->all(), [
            'reason_id' => 'required|integer|exists:cancellation_reasons,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors(),
            ], 422);
        }

        $currentDate = Carbon::now()->toDateString();
        $currentTime = Carbon::now()->toTimeString();

        $ride = Ride::where('client_id',$this->Client->id)
            ->where('requested',1)
            ->where('completed',0)
            ->where('canceled',0)
            ->where('date','=',$currentDate)
            ->where('start_time', '<=', $currentTime)
//            ->where('end_time', '>=', $currentTime)
            ->orderBy('start_time', 'DESC')
            ->first();

        if(!$ride)
        {
            $response = null;
            return ResponseHelper::make($response, __('trans.Data not found'), false,404);
        }
        $ride->update(['canceled' => 1]);

        if ($ride->driver){
            $this->sendNotificationCancel($ride->id);
        }

        $cancellationReason = RideCancellation::query()->updateOrCreate
        (
            ['ride_id'=>$ride->id,],
            ['reason_id'=>$request->reason_id]
        );

        $response['token'] = request()->bearerToken();
        return ResponseHelper::make($response, __('trans.canceledSuccessfully'));
    }


    // Get location by lat,long
    function getLocationByLatLong($latitude, $longitude)
    {
        // Validate latitude and longitude ranges
        if (!is_numeric($latitude) || !is_numeric($longitude) ||
            $latitude < -90 || $latitude > 90 ||
            $longitude < -180 || $longitude > 180) {
            return 'Invalid latitude or longitude';
        }

        // Google Maps API endpoint
        $apiEndpoint = 'https://maps.googleapis.com/maps/api/geocode/json';

        // Google Maps API key (optional)
        $apiKey = env('MAP_KEY');

        // Language parameters
        $languages = ['ar', 'en']; // Arabic and English

        $locationNames = array();

        // Make separate requests for each language
        foreach ($languages as $language) {
            // Build the request URL
            $url = $apiEndpoint . '?latlng=' . $latitude . ',' . $longitude . '&key=' . $apiKey . '&language=' . $language;

            // Send a GET request to Google Maps API
            $response = file_get_contents($url);

            // Decode the JSON response
            $data = json_decode($response, true);

            // Check if the response contains results
            if ($data['status'] == 'OK' && isset($data['results'][0])) {
                // Extract formatted address from the first result
                $formattedAddress = $data['results'][0]['formatted_address'];
                $locationNames[$language] = $formattedAddress;
            } else {
                $locationNames[$language] = 'Location not found';
            }
        }

        return $locationNames;
    }


    // used in store function
    function rideInfo($origins, $destinations)
    {
        try {
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
                // Return null if distance information is not available
                return $this->fallbackRideInfo($origins, $destinations);
            }
        } catch (RequestException $e) {
            // Handle request exceptions (e.g., network issues, API errors)
            // Log or handle the error as needed
            return $this->fallbackRideInfo($origins, $destinations);
        }
    }

    // Fallback mechanism for rideInfo
    function fallbackRideInfo($origins, $destinations)
    {
        // Extract lat/lng from origins and destinations
        [$originLat, $originLng] = explode(',', $origins[0]);
        [$destLat, $destLng] = explode(',', $destinations[0]);

        // Calculate distance using Haversine formula
        $earthRadius = 6371000; // Earth radius in meters

        $dLat = deg2rad($destLat - $originLat);
        $dLng = deg2rad($destLng - $originLng);

        $a = sin($dLat / 2) * sin($dLat / 2) +
            cos(deg2rad($originLat)) * cos(deg2rad($destLat)) *
            sin($dLng / 2) * sin($dLng / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        $distance = $earthRadius * $c; // Distance in meters

        // Estimate duration based on average speed (e.g., 40 km/h = 11.11 m/s)
        $averageSpeed = 11.11; // Average speed in meters/second
        $duration = $distance / $averageSpeed; // Duration in seconds

        return ['distance' => $distance, 'duration' => $duration];
    }


    //check if request of time and date are lass than now (store function)
    function checkDateTime($date, $time)
    {
        $check = 1;

        $requestDate = \Carbon\Carbon::createFromFormat('Y/m/d', $date)->format('Y/m/d');
        $currentDate = \Carbon\Carbon::now()->format('Y/m/d');

        // Compare dates
        if ($requestDate < $currentDate) {
            $check = 0;
        }

        // If the request date is the same as the current date, compare times
        if ($requestDate == $currentDate) {
            $requestTime = \Carbon\Carbon::createFromFormat('H:i', $time)->format('H:i');
            $currentTime = \Carbon\Carbon::now()->format('H:i');

            if ($requestTime < $currentTime) {
                $check = 0;
            }
        }

        return $check;
    }


    // To send notifications
    function sendNotificationCancel($ride_id)
    {
        $ride = Ride::where('id',$ride_id)->firstOrFail();

        $Notification = Notification::create([
            'title_ar' => 'تم إلغاء الرحلة',
            'title_en' =>  'The ride has been cancelled',
            'type' => 'public',
            'ride_id' => $ride_id,
            'driver_id' => $ride->driver_id,
            'created_at' => now(),
        ]);
        PushNotification::send($ride->driver->lang == 'ar' ? $Notification->title_ar : $Notification->title_en, ['type' => 'public'], $Notification->driver_id,'Driver');
    }





} //end of class
