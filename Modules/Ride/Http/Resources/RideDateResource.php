<?php

namespace Modules\Ride\Http\Resources;

use App\Http\Resources\BaseResource;

class RideDateResource extends BaseResource
{
    public function toArray($request)
    {
        $data = [
            'order_no' => $this->id,
            'Date' => $this->date,
            'start_time' => $this->start_time,

            'phone'=>$this->client->phone_code.$this->client->phone,
            'pickup'=>$this->startAddress['note_'.lang()],
            'Destination'=>$this->endAddress['note_'.lang()],
            'start_latitude'=>$this->startAddress->latitude,
            'start_longitude'=>$this->startAddress->longitude,
            'end_latitude'=>$this->endAddress->latitude,
            'end_longitude'=>$this->endAddress->longitude,
        ];

        if ($this->completed == 1) {
            // Convert start_time and end_time strings to DateTime objects
            $start = \DateTime::createFromFormat('H:i:s', $this->start_time);
            $end = \DateTime::createFromFormat('H:i:s', $this->end_time);

            // Calculate the difference between start and end times
            $difference = $start->diff($end);

            // Format the difference as desired (e.g., 1h 20m)
            $formatted_difference = '';
            if ($difference->h > 0) {
                $formatted_difference .= $difference->h . 'h ';
            }
            if ($difference->i > 0) {
                $formatted_difference .= $difference->i . 'm';
            }

            // Add the formatted difference to the data array
            $data['time_taken'] = $formatted_difference;
        }

        return $data;
    }
}
