<?php

namespace App\Http\Controllers\API;

use App\Functions\ResponseHelper;
use App\Http\Controllers\BasicController;
use App\Http\Requests\API\HomeRequest;
use Illuminate\Http\Request;
use Modules\Driver\Entities\Model as Driver;
use Modules\Client\Entities\Model as Client;


class HomeController extends BasicController
{
    public function home(HomeRequest $request)
    {

        return ResponseHelper::make(1);
    }


    public function updateLocation(Request $request)
    {
        $this->checkAuth();

        if( $this->Driver){
            $driver = $this->Driver->update($request->all());
            $response['token'] = request()->bearerToken();
        }
        else{
            $client = $this->Client->update($request->all());
            $response['token'] = request()->bearerToken();
        }
        return ResponseHelper::make($response, __('trans.updatedSuccessfully'));
    }

}//end of class
