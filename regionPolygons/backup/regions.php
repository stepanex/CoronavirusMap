<?php
/**
 * Created by PhpStorm.
 * User: Stepan
 * Date: 3/9/2020
 * Time: 23:39
 */

for($i = 1;$i<10;$i++){
    $fn = fopen("area0".$i.".txt","r");
    $array = [];
    $last_lng = 0;
    $last_lat = 0;
    while(! feof($fn))  {
        $result = fgets($fn);
        $splittedString = explode(',',$result);
        $lat = explode("\n",$splittedString[1])[0];
        $lng = $splittedString[0];
        if(abs($lng-$last_lng) > 0.01 || abs($lat-$last_lat) > 0.01 ){
            $tmpArr = [];
            $tmpArr['lat'] = floatval($lat);
            $tmpArr['lng'] = floatval($lng);
            array_push($array,$tmpArr);
            $last_lng = $lng;
            $last_lat = $lat;
        }
    }
    file_put_contents("area0".$i."_json.txt", json_encode($array));
    fclose($fn);
}
for($i = 10;$i<15;$i++){
    $fn = fopen("area".$i.".txt","r");
    $array = [];
    $last_lng = 0;
    $last_lat = 0;
    while(! feof($fn))  {
        $result = fgets($fn);
        $splittedString = explode(',',$result);
        $lat = explode("\n",$splittedString[1])[0];
        $lng = $splittedString[0];
        if(abs($lng-$last_lng) > 0.01 || abs($lat-$last_lat) > 0.01 ){
            $tmpArr = [];
            $tmpArr['lat'] = floatval($lat);
            $tmpArr['lng'] = floatval($lng);
            array_push($array,$tmpArr);
            $last_lng = $lng;
            $last_lat = $lat;
        }
    }
    file_put_contents("area".$i."_json.txt", json_encode($array));
    fclose($fn);
}


