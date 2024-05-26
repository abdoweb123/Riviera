<?php

namespace Modules\Rating\Http\Controllers;

use App\Functions\ResponseHelper;
use App\Http\Controllers\BasicController;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Modules\Rating\Entities\Model;
use Modules\Rating\Http\Requests\RatingRequest;
use Modules\Driver\Entities\Model as Driver;
use Modules\Ride\Entities\Model as Ride;

class APIController extends BasicController
{
    public function store(RatingRequest $request)
    {
        $this->checkAuth();

        $Model = Model::create([
            'driver_id'=>$request->driver_id,
            'rating'=>$request->rating,
            'client_id'=>$this->Client->id,
            'comment'=>$request->comment,
        ]);

        $currentDate = Carbon::now()->toDateString();
        $currentTime = Carbon::now()->toTimeString();

        $ride = Ride::where('client_id',$this->Client->id)
            ->where('requested',1)
            ->where('completed',0)
            ->where('canceled',0)
            ->where('date','=',$currentDate)
            ->where('start_time', '<=', $currentTime)
            ->where('end_time', '>=', $currentTime)
            ->orderBy('start_time', 'DESC')
            ->first();
        if($ride){
            $ride->rating = $request->rating;
            $ride->save();
        }


        return ResponseHelper::make($Model, __('trans.addedSuccessfully'));
    }


    public function getTotalRating(Request $request)
    {
        $this->checkAuth();
        $driver = Driver::findOrFail($request->id);

        $totalRate = $driver->totalRating();

        $data = [
            'rating' => $totalRate
        ];
        return ResponseHelper::make($data, __('trans.Data_fetched_successfully'));
    }

} //end of class
