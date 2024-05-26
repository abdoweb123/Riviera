<?php

namespace Modules\Rating\Http\Controllers;

use App\Http\Controllers\BasicController;
use Illuminate\Http\Request;
use Modules\Rating\Entities\Model;

class Controller extends BasicController
{
    public function index(Request $request)
    {
        $Models = Model::latest()->get();

        return view('rating::index', compact('Models'));
    }

    public function show($id)
    {
        $Model = Model::latest()->find($id);

        return view('rating::show', compact('Model'));
    }

} //end of class
