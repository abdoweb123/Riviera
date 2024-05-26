<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BasicController;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\Client\Entities\Model as Client;
use Modules\Driver\Entities\Model as Driver;
use Modules\Ride\Entities\Model as Ride;
use Modules\Car\Entities\CarType;
use Modules\Car\Entities\Model as Car;
use Modules\Complaint\Entities\Model as Complaint;

class HomeController extends BasicController
{
    public function home(Request $request)
    {
        $currentDate = Carbon::now()->toDateString();
        $currentTime = Carbon::now()->toTimeString();

        $chartRides = DB::table('rides')->whereMonth('created_at', '>=', Carbon::now()->subMonth()->month)->select([DB::raw('DATE(created_at) AS label'), DB::raw('(COUNT(*)) as y')])->groupBy('label')->get()->toarray();
        $chartChanges = DB::table('rides')->select(DB::raw('sum(cost) as y'), DB::raw("DATE_FORMAT(created_at,'%M %Y') as label"))->groupBy('label')->orderBy('created_at')->get()->toarray();
        $chartClients = Client::whereNotNull('created_at')->whereMonth('created_at', '>=', Carbon::now()->subMonth()->month)->select([DB::raw('DATE(created_at) AS label'), DB::raw('(COUNT(*)) as y')])->groupBy('label')->get()->toarray();

        $clientsCount = Client::count();
        $driversCount = Driver::count();
        $carTypesCount = CarType::count();
        $carsCount = Car::count();
        $complaintsCount = Complaint::count();


        $futureRidesCount = DB::table('rides')
            ->where(function ($query) use ($currentDate, $currentTime) {
                $query->where('date', '>', $currentDate)
                    ->orWhere(function ($query) use ($currentDate, $currentTime) {
                        $query->where('date', '=', $currentDate)
                            ->where('start_time', '>', $currentTime);
                    });
            })
            ->count();


        $previousRidesCount = DB::table('rides')
            ->where(function ($query) use ($currentDate, $currentTime) {
                $query->where('date', '<', $currentDate)
                    ->orWhere(function ($query) use ($currentDate, $currentTime) {
                        $query->where('date', '=', $currentDate)
                            ->where('start_time', '<', $currentTime);
                    });
            })
            ->count();

        return view('Admin.home', compact(

            'clientsCount',
            'driversCount',
            'carTypesCount',
            'carsCount',
            'complaintsCount',
            'futureRidesCount',
            'previousRidesCount',
            'chartRides',
            'chartClients',
            'chartChanges'
        ));
    }


    // future and past rides
    public function getRides($type)
    {
        $currentDate = Carbon::now()->toDateString();
        $currentTime = Carbon::now()->toTimeString();

        if ($type == 'previous'){
            $Models = Ride::where(function ($query) use ($currentDate, $currentTime) {
                    $query->where('date', '<', $currentDate)
                        ->orWhere(function ($query) use ($currentDate, $currentTime) {
                            $query->where('date', '=', $currentDate)
                                ->where('start_time', '<', $currentTime);
                        });
                })
                ->get();
        }
         else{
             $Models = Ride::where(function ($query) use ($currentDate, $currentTime) {
                     $query->where('date', '>', $currentDate)
                         ->orWhere(function ($query) use ($currentDate, $currentTime) {
                             $query->where('date', '=', $currentDate)
                                 ->where('start_time', '>', $currentTime);
                         });
                 })
                 ->get();
         }

        return view('ride::index', compact('Models'));
    }


} //end of class
