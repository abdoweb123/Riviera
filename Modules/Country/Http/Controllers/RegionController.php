<?php

namespace Modules\Country\Http\Controllers;

use App\Http\Controllers\BasicController;
use Illuminate\Http\Request;
use Modules\Country\Entities\Country;
use Modules\Country\Entities\Region;

class RegionController extends BasicController
{
    public function index(Country $Country, Request $request)
    {
        $Country = $Country->with('Regions')->first();

        return view('country::regions.index', compact('Country'));
    }

    public function create(Country $Country)
    {
        return view('country::regions.create', compact('Country'));
    }

    public function store(Country $Country, Request $request)
    {
        $Region = Region::create($request->all());
        alert()->success(__('trans.addedSuccessfully'));

        return redirect()->route(activeGuard().'.countries.show', $Country);
    }

    public function show(Country $Country, Region $Region)
    {
        return view('country::regions.show', compact('Country', 'Region'));
    }

    public function edit(Country $Country, Region $Region)
    {
        return view('country::regions.edit', compact('Country', 'Region'));
    }

    public function update(Country $Country, Request $request, Region $Region)
    {
        $Region->update($request->all());
        alert()->success(__('trans.updatedSuccessfully'));

        return redirect()->route(activeGuard().'.countries.show', $Country);
    }

    public function destroy(Country $Country, Region $Region)
    {
        $Region->delete();

    }
}
