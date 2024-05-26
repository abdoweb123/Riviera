<?php

namespace Modules\Country\Http\Controllers;

use App\Functions\Upload;
use App\Http\Controllers\BasicController;
use Illuminate\Http\Request;
use Modules\Country\Entities\Country;

class CountryController extends BasicController
{
    public function index(Request $request)
    {
        $Countries = Country::get();

        return view('country::countries.index', compact('Countries'));
    }

    public function create()
    {
        abort(404);
        $Countries = Country::get();

        return view('country::countries.create', compact('Countries'));
    }

    public function store(Request $request)
    {
        abort(404);
        $Country = Country::create($request->all());
        if ($request->hasFile('image')) {
            $Country->image = Upload::UploadFile($request->image, 'Countries');
            $Country->save();
        }
        alert()->success(__('trans.addedSuccessfully'));

        return redirect()->route(activeGuard().'.countries.index');
    }

    public function show($id)
    {
        $Country = Country::with('Regions', 'Blocks')->where('id', $id)->firstorfail();

        return view('country::countries.show', compact('Country'));
    }

    public function edit($id)
    {
        $Countries = Country::get();
        $Country = Country::where('id', $id)->firstorfail();

        return view('country::countries.edit', compact('Country', 'Countries'));
    }

    public function update(Request $request, $id)
    {
        $Country = Country::where('id', $id)->firstorfail();
        $Country->update($request->all());
        if ($request->hasFile('image')) {
            $Country->image = Upload::UploadFile($request->image, 'Countries');
            $Country->save();
        }
        alert()->success(__('trans.updatedSuccessfully'));

        return redirect()->route(activeGuard().'.countries.index');
    }

    public function destroy($id)
    {
        $Country = Country::where('id', $id)->delete();

    }
}
