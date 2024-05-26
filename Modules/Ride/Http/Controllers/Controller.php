<?php

namespace Modules\Ride\Http\Controllers;

use App\Http\Controllers\BasicController;
use Illuminate\Http\Request;
use Modules\Ride\Entities\Model;

class Controller extends BasicController
{

    public function index(Request $request)
    {
        $Models = Model::whereNotNull('driver_id')->latest()->get();
        return view('ride::index', compact('Models'));
    }


    public function show(Model $ride)
    {
        return view('ride::show', compact('ride'));
    }


} //end of class
