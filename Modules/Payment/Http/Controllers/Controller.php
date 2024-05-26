<?php

namespace Modules\Payment\Http\Controllers;

use App\Functions\Upload;
use App\Http\Controllers\BasicController;
use Illuminate\Http\Request;
use Modules\Payment\Entities\Model;

class Controller extends BasicController
{
    public function index(Request $request)
    {
        $Models = Model::get();

        return view('payment::index', compact('Models'));
    }

    public function create()
    {
        return view('payment::create');
    }

    public function store(Request $request)
    {
        $Model = Model::create($request->only(['title_ar', 'title_en', 'status']));
        if ($request->hasFile('image')) {
            $Model->image = Upload::UploadFile($request->image, 'Payments');
        }
        $Model->save();
        alert()->success(__('trans.addedSuccessfully'));

        return redirect()->route(activeGuard().'.payments.index');
    }

    public function show($id)
    {
        $Model = Model::where('id', $id)->firstorfail();

        return view('payment::show', compact('Model'));
    }

    public function edit($id)
    {
        $Model = Model::where('id', $id)->firstorfail();

        return view('payment::edit', compact('Model'));
    }

    public function update(Request $request, $id)
    {
        $Model = Model::where('id', $id)->firstorfail();
        $Model->update($request->only(['title_ar', 'title_en',  'status']));
        if ($request->hasFile('image')) {
            $Model->image = Upload::UploadFile($request->image, 'Payments');
        }
        $Model->save();
        alert()->success(__('trans.updatedSuccessfully'));

        return redirect()->route(activeGuard().'.payments.index');
    }

    public function destroy($id)
    {
        Model::where('id', $id)->delete();
    }
}
