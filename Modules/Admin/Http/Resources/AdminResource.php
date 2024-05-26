<?php

namespace Modules\Admin\Http\Resources;

use App\Http\Resources\BaseResource;

class AdminResource extends BaseResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'phone_code' => $this->phone_code,
            'image' => asset($this->image ?? setting('logo')),
        ];
    }
}
