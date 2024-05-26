<?php

namespace Modules\Address\Http\Controllers;

use App\Functions\ResponseHelper;
use App\Http\Controllers\BasicController;
use Illuminate\Http\Request;
use Modules\Address\Entities\Model;
use Modules\Address\Http\Requests\AddressRequest;
use Modules\Client\Http\Resources\ClientResource;
use Modules\Address\Http\Resources\AddressResource;

class APIController extends BasicController
{
    public function get()
    {
        $this->CheckAuth();

        $Model = Model::where('client_id', $this->Client->id)
            ->whereNotNull('note_ar')
            ->whereNotNull('note_en')
            ->selectRaw('*, COUNT(DISTINCT note_ar, note_en) as count')
            ->groupBy('note_ar', 'note_en')
            ->get();

        $addresses = AddressResource::collection($Model);

        return ResponseHelper::make($addresses, __('trans.Data_fetched_successfully'));
    }



    public function store(AddressRequest $request)
    {

        // return $request;
        $this->CheckAuth();

        // To get locations(ar,en) of lat,long
        $location = $this->getLocationByLatLong($request->latitude,$request->longitude);


        $Model = Model::create([
            'client_id'=>$this->Client->id,
            'status'=>1,
            'note_ar'=>$location['ar'],
            'note_en'=>$location['en'],
            'latitude'=>$request->latitude,
            'longitude'=>$request->longitude,
        ]);

        $addressResource = new AddressResource($Model);

        return ResponseHelper::make($addressResource, __('trans.addedSuccessfully'));
    }



    public function delete($lang, $id)
    {
        $this->CheckAuth();
        $address = Model::where('id',$id)->where('client_id',$this->Client->id)->delete();
        $response['token'] = request()->bearerToken();

        return ResponseHelper::make($response, __('trans.DeletedSuccessfully'));
    }



    // Get location by lat,long
    function getLocationByLatLong($latitude, $longitude)
    {
        // Validate latitude and longitude ranges
        if (!is_numeric($latitude) || !is_numeric($longitude) ||
            $latitude < -90 || $latitude > 90 ||
            $longitude < -180 || $longitude > 180) {
            return 'Invalid latitude or longitude';
        }

        // Google Maps API endpoint
        $apiEndpoint = 'https://maps.googleapis.com/maps/api/geocode/json';

        // Google Maps API key (optional)
        $apiKey = env('MAP_KEY');

        // Language parameters
        $languages = ['ar', 'en']; // Arabic and English

        $locationNames = array();

        // Make separate requests for each language
        foreach ($languages as $language) {
            // Build the request URL
            $url = $apiEndpoint . '?latlng=' . $latitude . ',' . $longitude . '&key=' . $apiKey . '&language=' . $language;

            // Send a GET request to Google Maps API
            $response = file_get_contents($url);

            // Decode the JSON response
            $data = json_decode($response, true);

            // Check if the response contains results
            if ($data['status'] == 'OK' && isset($data['results'][0])) {
                // Extract formatted address from the first result
                $formattedAddress = $data['results'][0]['formatted_address'];
                $locationNames[$language] = $formattedAddress;
            } else {
                $locationNames[$language] = 'Location not found';
            }
        }

        return $locationNames;
    }





} //end of class
