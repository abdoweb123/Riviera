<?php

namespace Modules\Ride\Http\Resources;

use App\Http\Resources\BaseResource;
use Carbon\Carbon;

class RideDetailsResource extends BaseResource
{
    public function toArray($request)
    {
        $data = [
            'order_no' => $this->id,
            'pickup'=>$this->startAddress['note_'.lang()],
            'Destination'=>$this->endAddress['note_'.lang()],
            'start_latitude'=>$this->startAddress->latitude,
            'start_longitude'=>$this->startAddress->longitude,
            'end_latitude'=>$this->endAddress->latitude,
            'end_longitude'=>$this->endAddress->longitude,
        ];

        if ($this->completed == 1)
        {
            $start = Carbon::createFromFormat('H:i:s', $this->start_time);
            $end = Carbon::createFromFormat('H:i:s', $this->end_time);
            // Calculate the difference between start and end times
            $differenceInMinutes = (string)$start->diffInMinutes($end);

            $data['driver_name'] = $this->driver->name;
            $data['driver_image'] = asset($this->driver->image ?? setting('logo'));
            $data['Date'] = $this->date;
            $data['distance'] = $this->distance . __('trans.km_d');
            $data['time_taken'] = $differenceInMinutes.__('trans.m');
            $data['start_time'] = $this->start_time;
            $data['end_time'] = $this->end_time;
            $data['payment_image'] = asset($this->payment['image'] ?? setting('logo'));
            $data['payment_type'] = $this->payment['title_'.lang()];
            $data['price'] = format_number($this->cost * Country()->currancy_value);
            $data['currency'] = Country()->currancy_code_en;
            $data['status'] = 'completed';
        }


        return $data;
    }


}
