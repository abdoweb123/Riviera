<?php

namespace Modules\Ride\Http\Resources;

use App\Http\Resources\BaseResource;
use Carbon\Carbon;

class RideRouteResource extends BaseResource
{
    public function toArray($request)
    {
        $data = [
            'id' => $this->id,
            'driver_name' => $this->driver?->name,
            'driver_image' => $this->driver_id == null ? 'null' : asset($this->driver->image ?? setting('logo')),
            'driver_phone'=>$this->driver_id == null ? 'null' : $this->driver->phone_code.$this->driver->phone,
            'car_type' => ucfirst(strtolower($this->driver?->car->carType['title_'.lang()])),
            'car_title' => $this->driver?->car['title_'.lang()],
            'car_license_no' => $this->driver?->car->license_no,
            'car_color' => $this->driver?->car->color,
            'rating' => $this->driver?->totalRating(),
            'payment_type' => $this->payment['title_'.lang()],
            'payment_image' => asset($this->payment['image'] ?? setting('logo')),
            'price' => format_number($this->cost * Country()->currancy_value),
            'currency' => Country()->currancy_code_en,
            'pickup'=>$this->startAddress['note_'.lang()],
            'Destination'=>$this->endAddress['note_'.lang()],
            'start_latitude'=>$this->startAddress->latitude,
            'start_longitude'=>$this->startAddress->longitude,
            'end_latitude'=>$this->endAddress->latitude,
            'end_longitude'=>$this->endAddress->longitude,
        ];

        $start_time = Carbon::createFromFormat('H:i:s', $this->start_time);
        $end_time = Carbon::createFromFormat('H:i:s', $this->end_time);

        $data['start_time'] = $start_time->format('h:i A');
        $data['end_time'] = $end_time->format('h:i A');


        return $data;
    }


}
