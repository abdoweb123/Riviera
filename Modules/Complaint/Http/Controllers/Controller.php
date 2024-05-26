<?php

namespace Modules\Complaint\Http\Controllers;

use App\Http\Controllers\BasicController;
use Illuminate\Http\Request;
use Modules\Complaint\Entities\Model;

class Controller extends BasicController
{

    public function index(Request $request)
    {
        $Models = Model::get();

        return view('complaint::index', compact('Models'));
    }


    public function show($id)
    {
        $Model = Model::where('id', $id)->firstorfail();

        return view('complaint::show', compact( 'Model'));
    }


    public function destroy($id)
    {
        $Model = Model::where('id', $id)->delete();
    }


} //end of class
