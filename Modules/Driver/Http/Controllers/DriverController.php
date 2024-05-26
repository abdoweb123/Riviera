<?php

namespace Modules\Driver\Http\Controllers;

use App\Functions\ResponseHelper;
use App\Http\Controllers\BasicController;
use Carbon\Carbon;
use http\Client\Response;
use Modules\Driver\Entities\Model as Driver;
use Modules\Car\Entities\Model as Car;
use Modules\Ride\Entities\Model as Ride;
use Modules\Driver\Http\Resources\DriverResource;
use Modules\Ride\Entities\RideStatus;
use Modules\Ride\Entities\UnlikedRideDriver;
use Modules\Ride\Http\Resources\RideResource;
use Modules\Ride\Http\Resources\RideGetResource;
use Modules\Ride\Http\Resources\RideDateResource;
use Modules\Driver\Http\Requests\CarStatusRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DriverController extends BasicController
{

    public function index()
    {
        $Drivers = Driver::get();
        $Drivers = $Drivers ? DriverResource::collection($Drivers) : [];

        return ResponseHelper::make($Drivers);
    }



    public function changeCarStatus(CarStatusRequest $request)
    {
        $this->checkAuth();

        $this->Driver->car->status = 1;

        $car = Car::where('driver_id', $this->Driver->id)->update(['status' => $request->status]);

        $response['status'] = request('status');

        $response['token'] = request()->bearerToken();

        return ResponseHelper::make($response, __('trans.updatedSuccessfully'));
    }



    public function getCarStatus()
    {
        $this->checkAuth();

        $response['status'] = Car::where('driver_id', $this->Driver->id)->value('status');

        $response['token'] = request()->bearerToken();

        return ResponseHelper::make($response, __('trans.Data_fetched_successfully'));
    }



    public function statusRide()
    {
        $this->checkAuth();

        $ride = Ride::where('id',request('ride_id'))->firstOrFail();
        $response['token'] = request()->bearerToken();
        //                                                            accept    started   start_ride  completed   canceled
        if(request('status') && in_array(request('status'),['accepted','started','confirmed','completed','canceled']))
        {
            // save status in rides table
            $ride->update([request('status')=>1]);

            // to update end_time from Google_default_time to actual end_time driver has dropped off
            if (request('status') == 'completed')
            {
                $ride->end_time = Carbon::now();
                $ride->save();
            }

            // save status in ride_statuses table
            $previousStatus = $ride->rideStatuses()->latest()->first();
            $timeTaken = 0;
            if ($previousStatus) {
                $timeTaken = $previousStatus->created_at->diffInMinutes(Carbon::now());
            }
            $ride->rideStatuses()->create([
                'name'=>request('status'),
                'time_taken' => $timeTaken
            ]);

        }else{
            return ResponseHelper::make($ride, __('trans.somethingWrong'),false,404);
        }

        return ResponseHelper::make($response, __('trans.updatedSuccessfully'));
    }


    // Get all rides
    public function getRides()
    {
        $this->checkAuth();

        $currentDate = Carbon::now()->toDateString();
        $currentTime = Carbon::now()->toTimeString();

        $response['rides'] = Ride::where('driver_id',$this->Driver->id);

        if(request('type') && in_array(request('type'),['upcoming','past']))
        {
            if(request('type')=='upcoming')
            {
                $status = 0; //completed = 0

                $response['rides']->where('completed',$status)
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


    /*** get all rides not assigned to any driver ***/
    public function getWithoutAssignedDrivers()
    {
        $this->checkAuth();

        $driverId = $this->Driver->id;

        $currentDate = Carbon::now()->toDateString();
        $currentTime = Carbon::now()->toTimeString();

        // Fetch rides without assigned drivers
        $rides = Ride::query()
            ->whereNull('driver_id')
            ->whereNotNull('carType_id')
            ->where('canceled', 0)
            ->where('completed', 0)
            ->where(function ($query) use ($currentDate, $currentTime) {
                $query->where('date', '>', $currentDate)
                    ->orWhere(function ($query) use ($currentDate, $currentTime) {
                        $query->where('date', '=', $currentDate);
                        // ->where('start_time', '<=', $currentTime);
                    });
            })
            ->whereDoesntHave('unlikedRideDrivers', function ($q){
                $q->where('driver_id', $this->Driver->id);
            })
            ->whereHas('nearestDrivers', function ($q) use ($driverId) {
                $q->where('driver_id', $driverId);
            })
            ->get();

        $authDriverRides = $this->Driver->rides()
            ->where('date','>=',$currentDate)
            ->where('start_time','>=',$currentTime)
            ->get();

        $filteredRides = $rides->filter(function ($ride) use ($authDriverRides) {
            foreach ($authDriverRides as $authDriverRide) {
                // Check for time conflict
                if ($ride->date == $authDriverRide->date && $ride->start_time >= $authDriverRide->start_time && $ride->start_time <= $authDriverRide->end_time) {
                    return false;
                }
            }
            return true;
        });


        $ride = RideGetResource::collection($filteredRides);
        return ResponseHelper::make($ride, __('trans.Data_fetched_successfully'));
    }


    /*** assign Or Unlike ride ***/
    public function assignOrUnlike(Request $request)
    {
        $this->checkAuth();

        $ride = Ride::where('id',$request->ride_id)->first();

        if (!$ride){
            $response = null;
            return ResponseHelper::make($response, __('trans.Data not found'), false, 404);
        }

        if ($request->status == 'assign')
        {
            // check if the ride doesnt have a driver
            // accepted



            Ride::where('id',$ride->id)->update([
                'accepted' => 1
            ]);

            if ($ride->driver_id == null){
                $ride->driver_id = $this->Driver->id;
                $ride->save();
            }
            else{
                $response = null;
                return ResponseHelper::make($response, __('trans.driver_has_been_selected_for_this_ride'), false, 404);
            }
        }else{
            // this means that $request->status == 'unlike'
            // canceled
            UnlikedRideDriver::create([
                'ride_id'=>$ride->id,
                'driver_id'=>$this->Driver->id,
            ]);

            Ride::where('id',$ride->id)->update([
                'canceled' => 1
            ]);
        }

        $response['token'] = request()->bearerToken();

        return ResponseHelper::make($response, __('trans.updatedSuccessfully'));
    }


    // Get current ride
    public function getCurrentRide(Request $request)
    {
        $this->checkAuth();

        // update lang of driver
        $this->Driver->lang = $request->lang;
        $this->Driver->save();

        $currentDate = Carbon::now()->toDateString();
        $currentTime = Carbon::now()->toTimeString();
        // Subtract 30 minutes from the current time
        $thirtyMinutesAgo = Carbon::now()->subMinutes(30)->toTimeString();

        $ride = Ride::where('driver_id',$this->Driver->id)
            ->where('completed',0)
            ->where('canceled',0)
            ->where('date','=',$currentDate)
            ->where('start_time', '>=', $thirtyMinutesAgo)
            ->orderBy('start_time', 'DESC')
            ->first();

        if(!$ride){
            $response = null;
            return ResponseHelper::make($response, __('trans.Data not found'), false, 404);
        }

        $ride = new RideResource($ride);
        return ResponseHelper::make($ride, __('trans.Data_fetched_successfully'));
    }


    // Get ride by id
    public function getRide(Request $request)
    {
        $this->checkAuth();

        $ride = Ride::where('id',request('ride_id'))->firstOrFail();

        $ride = new RideResource($ride);

        return ResponseHelper::make($ride, __('trans.Data_fetched_successfully'));
    }


    // Get rides by (date)
    public function getRidesDate()
    {
        $this->checkAuth();

        //validation
        $validator = Validator::make(request()->all(), [
            'date' => 'required|date_format:Y/m/d',
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $rides = Ride::where('driver_id',$this->Driver->id)->where('date',request('date'))->get();

        $response['token'] = request()->bearerToken();

        if($rides){
            $response['ride'] = RideDateResource::collection($rides);
        }

        return ResponseHelper::make($response, __('trans.Data_fetched_successfully'));
    }


    /*** get Future rides dates ***/
    public function getFutureRidesDates()
    {
        $this->checkAuth();

        $currentDate = Carbon::now()->toDateString();

        $response['rides'] = Ride::where('driver_id',$this->Driver->id)->where('completed',0)->where('date','>=',$currentDate)->orderBy('date')->pluck('date')->toArray();

        if(!$response['rides']){
            $response = null;
            return ResponseHelper::make($response, __('trans.Data not found'));
        }

        return ResponseHelper::make($response, __('trans.Data_fetched_successfully'));
    }


} //end of class
