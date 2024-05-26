<?php

namespace Modules\Client\Http\Controllers;

use App\Functions\Upload;
use App\Http\Controllers\BasicController;
use Illuminate\Http\Request;
use Modules\Client\Entities\Model;

class Controller extends BasicController
{
    public function index(Request $request)
    {
        $Models = Model::get();

        return view('client::index', compact('Models'));
    }

    public function create()
    {
        return view('client::create');
    }

    public function store(Request $request)
    {
        $Model = Model::create(['phone' => str_replace(' ', '', $request->phone)] + $request->only(['name', 'email', 'phone_code', 'country_code', 'points']));
        $Model->password = bcrypt($request->password);
        if ($request->hasFile('image')) {
            $Model->image = Upload::UploadFile($request->image, 'Clients');
        }
        $Model->save();
        alert()->success(__('trans.addedSuccessfully'));

        return redirect()->back();
    }

    public function show($id)
    {
        $Model = Model::where('id', $id)->firstorfail();

        return view('client::show', compact('Model'));
    }

    public function edit($id)
    {
        $Model = Model::where('id', $id)->firstorfail();

        return view('client::edit', compact('Model'));
    }

    public function update(Request $request, $id)
    {
        $Model = Model::where('id', $id)->firstorfail();
        $Model->update(['phone' => str_replace(' ', '', $request->phone)] + $request->only(['name', 'email', 'phone_code', 'country_code', 'points']));
        if ($request->hasFile('image')) {
            $Model->image = Upload::UploadFile($request->image, 'Clients');
        }
        if ($request->password) {
            $Model->password = bcrypt($request->password);
        }
        $Model->save();
        alert()->success(__('trans.updatedSuccessfully'));

        return redirect()->back();
    }

    public function destroy($id)
    {
        $Model = Model::where('id', $id)->delete();
    }

} //end of class
