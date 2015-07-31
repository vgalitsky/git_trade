<?php
class core_google_geo extends core_object{


    static function findCityName( $lat, $long ){
        /** @var string $city_name */
        $city_name = 'Unknown';

        $latlng = round($lat,2).",";
        $latlng .= round($long,2);
        $details_url = "http://maps.googleapis.com/maps/api/geocode/json?latlng=".$latlng."&sensor=false&language=Ru";

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $details_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $response = json_decode(curl_exec($ch), true);

        // If Status Code is ZERO_RESULTS, OVER_QUERY_LIMIT, REQUEST_DENIED or INVALID_REQUEST
        if ($response['status'] != 'OK') {
            return $city_name;
        }
        //core_debug::dump($response[]);
        foreach( $response['results'][0]['address_components'] as $component){
            if(in_array('locality',$component['types'])){
                $city_name = $component['long_name'];
            }
        }

        return $city_name;
    }

    static function findFullAddress( $lat, $long ){
        /** @var string $address */
        $address = '';

        return $address;
    }
}