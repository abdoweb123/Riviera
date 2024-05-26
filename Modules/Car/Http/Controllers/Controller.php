<?php

namespace Modules\Car\Http\Controllers;

use App\Functions\Upload;
use App\Http\Controllers\BasicController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Modules\Car\Entities\CarType;
use Modules\Car\Entities\Model;
use Modules\Driver\Entities\Model as Driver;

class Controller extends BasicController
{

    public function index(Request $request)
    {
        $Models = Model::get();

        return view('car::index', compact('Models'));
    }


    public function create()
    {
        $drivers = Driver::Active()->get();
        $car_types = CarType::Active()->get();
        return view('car::create', compact('drivers','car_types'));
    }


    public function store(Request $request)
    {
        $Model = Model::query()->create($request->all());

        if ($request->hasFile('image')) {
            $Model->image = Upload::UploadFile($request->image, 'Cars');
            $Model->save();
        }

        alert()->success(__('trans.addedSuccessfully'));

        return redirect()->back();
    }


    public function show($id)
    {
        $Model = Model::where('id', $id)->firstorfail();

        return view('car::show', compact('Model'));
    }


    public function edit($driver_id)
    {
        $driver = Driver::where('id', $driver_id)->firstOrFail();
        $Model = Model::where('driver_id', $driver_id)->first();
        if (!$Model){
            alert()->error(__('trans.no_car_for_driver'));
            return redirect()->back();
        }

        return view('car::edit', compact('Model','driver'));
    }


    public function update(Request $request, $id)
    {
        $Model = Model::where('id', $id)->firstorfail();
        $Model->update($request->only('title_ar','title_en','number','price','status','color','license_no'));

        if ($request->hasFile('image')) {

            $old_image = public_path($Model->image);
                if (File::exists($old_image)) {
                    File::delete($old_image);
                }

            $Model->image = Upload::UploadFile($request->image, 'Cars');
        }
        $Model->save();
        alert()->success(__('trans.updatedSuccessfully'));

        return redirect()->back();
    }


    public function destroy($id)
    {
        $Model = Model::where('id', $id)->delete();
    }


}
