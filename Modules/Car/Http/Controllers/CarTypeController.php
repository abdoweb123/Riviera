<?php

namespace Modules\Car\Http\Controllers;

use App\Functions\Upload;
use App\Http\Controllers\BasicController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Modules\Car\Entities\CarType;
use Modules\Driver\Entities\Model as Driver;

class CarTypeController extends BasicController
{

    public function index(Request $request)
    {
        $Models = CarType::get();

        return view('car::carType.index', compact('Models'));
    }


    public function create()
    {
        $drivers = Driver::Active()->get();
        return view('car::carType.create', compact('drivers'));
    }


    public function store(Request $request)
    {
        $Model = CarType::query()->create($request->all());

        if ($request->hasFile('image')) {
            $Model->image = Upload::UploadFile($request->image, 'CarTypes');
            $Model->save();
        }

        alert()->success(__('trans.addedSuccessfully'));

        return redirect()->back();
    }


    public function show($id)
    {
        $Model = CarType::where('id', $id)->firstorfail();

        return view('car::carType.show', compact('Model'));
    }


    public function edit($id)
    {
        $Model = CarType::where('id', $id)->first();
        return view('car::carType.edit', compact('Model'));
    }


    public function update(Request $request, $id)
    {
        $Model = CarType::where('id', $id)->firstorfail();
        $Model->update($request->only('title_ar','title_en','number','price','status'));

        if ($request->hasFile('image')) {

            $old_image = public_path($Model->image);
                if (File::exists($old_image)) {
                    File::delete($old_image);
                }

            $Model->image = Upload::UploadFile($request->image, 'CarTypes');
        }
        $Model->save();
        alert()->success(__('trans.updatedSuccessfully'));

        return redirect()->back();
    }


    public function destroy($id)
    {
        $Model = CarType::where('id', $id)->delete();
    }


} //edn of class
