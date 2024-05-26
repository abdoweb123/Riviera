<?php

namespace App\Http\Requests\API;

class CouponFavouriteRequest extends BaseRequest
{
    public function rules()
    {
        return [
            'coupon_id' => 'required|exists:coupons,id',
        ];
    }
}
