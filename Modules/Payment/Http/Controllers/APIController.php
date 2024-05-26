<?php

namespace Modules\Payment\Http\Controllers;

use App\Functions\ResponseHelper;
use App\Http\Controllers\BasicController;
use Modules\Payment\Entities\Model;
use Modules\Payment\Http\Resources\PaymentResource;
use Illuminate\Http\Request;

class APIController extends BasicController
{

    public function index(Request $request)
    {
        $this->CheckAuth();

//        $Model = Model::query();

        $Model = Model::query()->whereNull('tap_src'); // To get cash only

        // If the type is "android", exclude records with tap_src as "src_apple_pay"
        if ($request->type == 'android') {
            $Model->where('tap_src', '!=', 'src_apple_pay')->orWhereNull('tap_src');;
        }

        $payments = $Model->get();
        return ResponseHelper::make(PaymentResource::collection($payments));
    }


} //end of class
