<?php

namespace Modules\Ride\Http\Resources;

use App\Http\Resources\BaseResource;
use Carbon\Carbon;

class RideResource extends BaseResource
{
    public function toArray($request)
    {
        $data = [
            'order_no' => $this->id,
            'phone'=>$this->client->phone_code.$this->client->phone,
            'pickup'=>$this->startAddress['note_'.lang()],
            'Destination'=>$this->endAddress['note_'.lang()],
            'Date'=>$this->date,
            'start_time'=>$this->start_time,
            'payment_image'=> asset( $this->payment['image'] ?? setting('logo')),
            'payment_type'=> $this->payment['title_'.lang()],
            'start_latitude'=>$this->startAddress->latitude,
            'start_longitude'=>$this->startAddress->longitude,
            'end_latitude'=>$this->endAddress->latitude,
            'end_longitude'=>$this->endAddress->longitude,
            'price'=>number_format($this->cost * Country()->currancy_value),
            'currency'=>Country()->currancy_code_en,
        ];


        if ($this->canceled == 1) {
            $data['status'] = 'canceled';
        }
        if ($this->completed == 1) {
            $data['status'] = 'completed';
        }
        elseif ($this->confirmed == 1){
            $data['status'] = 'confirmed';
        }
        elseif ($this->started == 1) {
            $data['status'] = 'started';
        }
        elseif ($this->accepted == 1) {
            $data['status'] = 'accepted';
        }
        else{
            $data['status'] = 'not accepted yet';
        }


        if ($this->completed == 1)
        {
            // For rides table
            $data['distance'] = $this->distance. 'KM';

            // Convert start_time and end_time strings to DateTime objects
            $start = Carbon::createFromFormat('H:i:s', $this->start_time);
            $end = Carbon::createFromFormat('H:i:s', $this->end_time);

            if ($start !== false && $end !== false) {
                // Calculate the difference between start and end times
                $difference = $start->diff($end);
                $data['duration'] = $this->format($difference->h * 60 + $difference->i);
            } else {
                // Handle the case where Carbon objects couldn't be created
                $data['duration'] = 'Unknown';
            }


            // for ride_statuses table
            $confirmedTime = $this->rideStatuses()->where('name', 'accepted')->first();
            $startedTime = $this->rideStatuses()->where('name', 'confirmed')->first();
            $dropOffTime = $this->rideStatuses()->where('name', 'completed')->first();
            $canceledTime = $this->rideStatuses()->where('name', 'canceled')->first();

            $data['ride_details'] = [];

            if ($this->accepted == 1) {
                $data['ride_details']['confirmed_ride'] = [
                    'duration' => $this->format($confirmedTime->time_taken),
                    'ride_time' => $confirmedTime->created_at->format('H:i:s'),
                ];
            }

            if ($this->confirmed == 1) {
                $data['ride_details']['started_ride'] = [
                    'duration' =>  $this->format($startedTime->time_taken),
                    'ride_time' => $startedTime->created_at->format('H:i:s'),
                ];
            }

            if ($this->completed == 1) {
                $data['ride_details']['drop_off'] = [
                    'duration' => $this->format($dropOffTime->time_taken),
                    'ride_time' => $dropOffTime->created_at->format('H:i:s'),
                ];
            }

            if ($this->canceled == 1) {
                $data['ride_details']['canceled'] = [
                    'duration' => $this->format($canceledTime->time_taken),
                    'ride_time' => $canceledTime->created_at->format('H:i:s'),
                ];
            }
        }

        return $data;
    }


    public function format($difference)
    {
        $hours = floor($difference / 60);
        $minutes = $difference % 60;

        $formatted_difference = '';
        if ($hours > 0) {
            $formatted_difference .= $hours . 'h ';
        }
        if ($minutes >= 0) {
            $formatted_difference .= $minutes . 'm';
        }

        return $formatted_difference;
    }

} //end of class


