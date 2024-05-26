<?php

namespace Modules\Slider\Http\Resources;

use App\Http\Resources\BaseResource;

class SliderResource extends BaseResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title' => $this['title_'.lang()],
            'desc' => $this['desc_'.lang()],
            'image' => asset($this->image ?? setting('logo')),
        ];
    }
    
}