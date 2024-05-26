<?php

namespace Modules\Address\Http\Resources;

use App\Http\Resources\BaseResource;

class AddressResource extends BaseResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'client_id' => $this->client_id,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'note' => $this['note_'.lang()],
        ];
    }

}//end of class
