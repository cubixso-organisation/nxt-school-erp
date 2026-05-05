<?php

namespace app\components;

use Yii;
use yii\base\Component;
use app\modules\admin\models\WebSetting;

class DrivingDistance extends Component {


    public function getDrivingDistance($lat1, $long1, $lat2, $long2)
    {
        //$dist = [ ];
        /*    $setting = new WebSetting();
        $map_key = $setting->getSettingBykey('map_key');
        $url ="https://maps.googleapis.com/maps/api/distancematrix/json?origins=".$lat1.",".$long1."&destinations=".$lat2.",".$long2."&mode=driving&key=".$map_key;
        // var_dump($url);exit;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true );
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_PROXYPORT, 3128);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        $response = curl_exec($ch);
        //var_dump($response);exit;
        curl_close($ch);
        $response_a = json_decode($response, true);
        //var_dump($response_a); exit;
        if($response_a['rows'][0]['elements'][0]['status']=='ZERO_RESULTS')
        $dist['error'] = 'ZERO_RESULTS';
        else if($response_a['rows'][0]['elements'][0]['status']=="NOT_FOUND" )
        $dist['error'] = 'NOT_FOUND';
        else if($response_a['rows'][0]['elements'][0]['status']=="MAX_ROUTE_LENGTH_EXCEEDED")
        $dist['error'] = 'MAX_ROUTE_LENGTH_EXCEEDED';
        else if($response_a['rows'][0]['elements'][0]['status']=="OK"){
        $dist['error'] = 'OK';
        $dist['km'] = $response_a['rows'][0]['elements'][0]['distance']['text'];
        $dist['meters'] = $response_a['rows'][0]['elements'][0]['distance']['value'];
        $dist['time'] = $response_a['rows'][0]['elements'][0]['duration']['text'];
        //$time = $response_a['rows'][0]['elements'][0]['duration']['text'];
        }else{
        $dist['error'] = 'OK';
        $dist['meters'] = 0;
        $dist['km'] = 0;
        }
        //$lat1,$long1,$lat2,$long2
         */
        // $theta = $long1 - $long2;
        // $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
        // $dist = acos($dist);
        // $dist = rad2deg($dist);
        // $miles = $dist * 60 * 1.1515;

        $pi80 = M_PI / 180;
        $lat1 *= $pi80;
        $long1 *= $pi80;
        $lat2 *= $pi80;
        $long2 *= $pi80;
        $r = 6372.797; // mean radius of Earth in km
        $dlat = $lat2 - $lat1;
        $dlon = $long1 - $long2;
        $a = sin($dlat / 2) * sin($dlat / 2) + cos($lat1) * cos($lat2) * sin($dlon / 2) * sin($dlon / 2);
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
        $km = $r * $c;

        $dist['error'] = 'OK';
        $dist['meters'] = $km * 1000;
        $dist['km'] = $km;
        // var_dump($dist);exit;
        // if ($unit == "K") {
        //   return ($miles * 1.609344);
        // } else if ($unit == "N") {
        //   return ($miles * 0.8684);
        // } else {
        //   return $miles;
        // }
        //return json_encode($dist, true);
        return $dist;
        // if(!empty($dist)){

        // }

    }
}


?>