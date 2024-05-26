<?php

namespace Modules\Payment\Http\Resources;

use App\Http\Resources\BaseResource;

class PaymentResource extends BaseResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title' => $this->title(),
            'image' => asset($this->image),
        ];
    }
}
