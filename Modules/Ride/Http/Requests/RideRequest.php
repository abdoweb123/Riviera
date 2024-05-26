<?php

namespace Modules\Ride\Http\Requests;

use App\Http\Requests\API\BaseRequest;

class RideRequest extends BaseRequest
{
    public function rules()
    {
        $arr = [
            'ride_date' => ['required','max:255','date_format:Y/m/d'],
        ];

        if (request('ride_time') !== null && request('ride_time') !== '') {
            $arr['ride_time'] = ['max:255', 'date_format:H:i'];
        }


        if(request('startAddress_id')){
            $arr['startAddress_id'] = ['required','numeric'];
        }else{
            $arr['start_latitude'] = ['required','max:255'];
            $arr['start_longitude'] = ['required','max:255'];
        }

        if(request('endAddress_id')){
            $arr['endAddress_id'] = ['required','numeric'];
        }else{
            $arr['end_latitude'] = ['required','max:255'];
            $arr['end_longitude'] = ['required','max:255'];
        }

        return $arr;
    }

}

