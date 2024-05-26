<?php

namespace Modules\Notification\Http\Controllers;

use App\Functions\PushNotification;
use App\Http\Controllers\BasicController;
use Illuminate\Http\Request;
use Modules\Admin\Entities\Model as Admin;
use Modules\Client\Entities\Model as Client;
use Modules\Driver\Entities\Model as Driver;
use Modules\Notification\Entities\Model;

class Controller extends BasicController
{
    public function index(Request $request)
    {
        $Models = Model::get();

        return view('notification::index', compact('Models'));
    }

    public function create()
    {
        $Clients = Client::get();
        $drivers = Driver::get();

        return view('notification::create', compact('Clients','drivers'));
    }

    public function store(Request $request)
    {
        if ($request->clients && count($request->clients) > 0) {
            foreach (Client::whereIn('id', $request->clients)->get() as $Model) {

                if (isset($request->branches) && count($request->branches)) {
                    foreach ($request->branches as $branch_id) {
                        Model::create([
                            'title_ar' => $request->title_ar,
                            'title_en' => $request->title_en,
                            'type' => 'branch',
                            'client_id' => $Model->id,
                            'created_at' => now(),
                        ]);
                        PushNotification::send($Model->lang == 'ar' ? $request->title_ar : $request->title_en, ['type' => 'branch', 'branch_id' => $branch_id], $Model->id, 'Client');
                    }
                } else {
                    Model::create([
                        'title_ar' => $request->title_ar,
                        'title_en' => $request->title_en,
                        'type' => 'public',
                        'client_id' => $Model->id,
                        'created_at' => now(),
                    ]);
                    PushNotification::send($Model->lang == 'ar' ? $request->title_ar : $request->title_en, ['type' => 'public'], $Model->id);
                }
            }
        }
        if ($request->drivers && count($request->drivers) > 0) {
            foreach (Driver::whereIn('id', $request->drivers)->get() as $Model) {

                if (isset($request->branches) && count($request->branches)) {
                    foreach ($request->branches as $branch_id) {
                        Model::create([
                            'title_ar' => $request->title_ar,
                            'title_en' => $request->title_en,
                            'type' => 'branch',
                            'driver_id' => $Model->id,
                            'created_at' => now(),
                        ]);
                        PushNotification::send($Model->lang == 'ar' ? $request->title_ar : $request->title_en, ['type' => 'branch', 'branch_id' => $branch_id], $Model->id, 'Driver');
                    }
                } else {
                    Model::create([
                        'title_ar' => $request->title_ar,
                        'title_en' => $request->title_en,
                        'type' => 'public',
                        'driver_id' => $Model->id,
                        'created_at' => now(),
                    ]);
                    PushNotification::send($Model->lang == 'ar' ? $request->title_ar : $request->title_en, ['type' => 'public'], $Model->id,'Driver');
                }

            }
        }
        alert()->success(__('trans.addedSuccessfully'));

        return redirect()->route(activeGuard().'.notifications.create');
    }


    public function show($id)
    {
        $Model = Model::where('id', $id)->firstorfail();

        return view('notification::show', compact('Model'));
    }


    public function edit($id)
    {
        $Model = Model::where('id', $id)->firstorfail();

        return view('notification::edit', compact('Model'));
    }


    public function update(Request $request, $id)
    {
        $Model = Model::where('id', $id)->firstorfail();
        $Model->update($request->only(['title_ar', 'title_en',  'status']));
        $Model->save();
        alert()->success(__('trans.updatedSuccessfully'));

        return redirect()->route(activeGuard().'.notifications.index');
    }


    public function destroy($id)
    {
        Model::where('id', $id)->delete();
    }


} //end of class
