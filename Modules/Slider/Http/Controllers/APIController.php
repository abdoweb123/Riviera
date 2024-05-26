<?php

namespace Modules\Slider\Http\Controllers;

use App\Functions\ResponseHelper;
use App\Http\Controllers\BasicController;
use Modules\Slider\Entities\Model as Slider;
use Modules\Slider\Http\Resources\SliderResource;

class APIController extends BasicController
{
    public function index()
    {
        $Sliders = Slider::Active()->get();
        return ResponseHelper::make(SliderResource::collection($Sliders));
    }
}
