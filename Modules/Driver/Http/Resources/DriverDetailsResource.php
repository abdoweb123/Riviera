<?php

namespace Modules\Driver\Http\Resources;

use App\Http\Resources\BaseResource;
use Carbon\Carbon;

class DriverDetailsResource extends BaseResource
{
    public function toArray($request)
    {
        $data = [
            'id' => $this->id,
            'name' => $this->name,
            'image' => asset($this->image ?? setting('logo')),
            'car_title' => $this->car['title_'.lang()],
            'rating_title' => __('trans.rating'),
            'rating' => $this->totalRating(),
            'rides_title' => __('trans.rides'),
            'rides' => $this->formatRides($this->rides()->count()),
        ];

        $start = Carbon::parse($this->created_at);
        $end = Carbon::now();

        [$value, $format] = formatTimeDifference($start, $end);
        $data['duration_type'] = $format;
        $data['duration'] = (string)$value;

        return $data;
    }



} //end of class
