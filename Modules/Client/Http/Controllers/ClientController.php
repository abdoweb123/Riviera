<?php

namespace Modules\Client\Http\Controllers;

use App\Functions\ResponseHelper;
use App\Http\Controllers\BasicController;
use Modules\Client\Entities\Model as Client;
use Modules\Client\Http\Resources\CarResource;

class ClientController extends BasicController
{
    public function index()
    {
        $Clients = Client::get();
        $Clients = $Clients ? CarResource::collection($Clients) : [];

        return ResponseHelper::make($Clients);
    }
}
