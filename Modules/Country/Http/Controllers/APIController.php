<?php

namespace Modules\Country\Http\Controllers;

use App\Functions\ResponseHelper;
use App\Http\Controllers\BasicController;
use Modules\Country\Entities\Country as Model;

class APIController extends BasicController
{
    public function index()
    {
        return ResponseHelper::make(Model::select('id', 'title_'.lang().' As title', 'phone_code', 'country_code', 'length', 'image')->orderByRaw('FIELD(id, 2,1,3,4,5,6,7)')->where('id',1)->get()->each(function ($item, $key) {
            $item->image = asset($item->image);
        }));
    }
}
