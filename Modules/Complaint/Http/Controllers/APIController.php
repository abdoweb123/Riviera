<?php

namespace Modules\Complaint\Http\Controllers;

use App\Functions\ResponseHelper;
use App\Http\Controllers\BasicController;
use Illuminate\Http\Request;
use Modules\Complaint\Entities\Model;
use Modules\Complaint\Http\Requests\ComplaintRequest;

class APIController extends BasicController
{
    public function store(ComplaintRequest $request)
    {
        $this->checkAuth();

        $response['complaint'] = Model::query()->create([
            'title'=>$request->title,
            'full_name'=>$request->full_name,
            'email'=>$request->email,
            'complaint'=>$request->complaint,
        ]);

        $response['token'] = request()->bearerToken();

        return ResponseHelper::make($response, __('trans.addedSuccessfully'));
    }


} //end of class
