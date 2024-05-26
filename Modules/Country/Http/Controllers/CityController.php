<?php

namespace Modules\Country\Http\Controllers;

use App\Http\Controllers\BasicController;
use Illuminate\Http\Request;
use Modules\Country\Entities\City;
use Modules\Country\Entities\Country;
use Modules\Country\Entities\Region;

class CityController extends BasicController
{
    public function index(Country $Country, Region $Region, Request $request)
    {
        $Region = $Region->with('Cities')->first();

        return view('country::cities.index', compact('Country', 'Region'));
    }

    public function create(Country $Country, Region $Region)
    {
        return view('country::cities.create', compact('Country', 'Region'));
    }

    public function store(Country $Country, Region $Region, Request $request)
    {
        $Region = Region::create($request->all());
        alert()->success(__('trans.addedSuccessfully'));

        return redirect()->route(activeGuard().'.countries.show', $Country);
    }

    public function show(Country $Country, Region $Region, City $City)
    {
        return view('country::cities.show', compact('Country', 'Region', 'City'));
    }

    public function edit(Country $Country, Region $Region, City $City)
    {
        return view('country::cities.edit', compact('Country', 'Region', 'City'));
    }

    public function update(Country $Country, Region $Region, Request $request, City $City)
    {
        $Region->update($request->all());
        alert()->success(__('trans.updatedSuccessfully'));

        return redirect()->route(activeGuard().'.countries.show', $Country);
    }

    public function destroy(Country $Country, Region $Region, City $City)
    {
        $Region->delete();

    }
}
