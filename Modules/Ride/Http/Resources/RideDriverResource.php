<?php

namespace Modules\Ride\Http\Resources;

use App\Http\Resources\BaseResource;
use Carbon\Carbon;

class RideDriverResource extends BaseResource
{
    public function toArray($request)
    {
        $data = [
            'id'=>$this->driver_id,
            'latitude'=>$this->driver->latitude,
            'longitude'=>$this->driver->longitude,
        ];

        if ($this->confirmed == 1)
        {
            $data['status'] = 'confirmed';
            $data['message'] = __('trans.driving_to_destination');
        }
        elseif ($this->accepted == 1)
        {
            if ($this->newDistance > 1) // if distance more than 1 km
            {
                $data['status'] = 'arriving';
                $data['message'] = __('trans.arriving_in').' '.$this->newDuration.  __('trans.minute');
            }
            else{
                $data['status'] = 'arrived';
                $data['message'] = __('trans.driver_has_arrived');
            }
        }
        elseif ($this->accepted == 0)
        {
            $data['status'] = 'waiting';
            $data['message'] = __('trans.please_wait_until_driver_start_ride');
        }


        return $data;
    }


}
