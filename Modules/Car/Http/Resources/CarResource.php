<?php

namespace Modules\Car\Http\Resources;

use App\Http\Resources\BaseResource;
use Modules\Country\Entities\Country;

class CarResource extends BaseResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->car->id,
            'title' => $this->car['title_'.lang()],
            'image' => asset($this->car->image ?? setting('logo')),
            'people_number' => $this->car->number,
            'price' => $this->car->price,
            'currency' => Country()->currancy_code_en,
            'time' => 4,
        ];
    }
}
