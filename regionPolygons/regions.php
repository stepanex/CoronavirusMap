<?php
/**
 * Created by PhpStorm.
 * User: Stepan
 * Date: 3/9/2020
 * Time: 23:39
 */
die();
$location = [];
for($i = 1;$i<10;$i++){
    $fileArray = json_decode(file_get_contents("area0".$i.".txt"));
    $array = [];
    $last_lng = 0;
    $last_lat = 0;
    $once = 1;
    $finishedString = '{ "type": "Feature", "geometry": { "type": "Polygon", "coordinates": [[';
    foreach ($fileArray as $item){
        $finishedString.='['.strval($item->lng).','.strval($item->lat).']';
        if($item != $fileArray[count($fileArray)-1] || $once == 1){
            $once-=1;
            $finishedString.=',';
        }
    }
    $finishedString .= ']]}}';
//    print($finishedString);
    file_put_contents("area0".$i.".geojson", $finishedString);
}
for($i = 10;$i<15;$i++){
    $fileArray = json_decode(file_get_contents("area".$i.".txt"));
    $array = [];
    $last_lng = 0;
    $last_lat = 0;
    $once = 1;
    $finishedString = '{ "type": "Feature", "geometry": { "type": "Polygon", "coordinates": [[';
    foreach ($fileArray as $item){
        $finishedString.='['.strval($item->lng).','.strval($item->lat).']';
        if($item != $fileArray[count($fileArray)-1] || $once == 1){
            $once-=1;
            $finishedString.=',';
        }
    }
    $finishedString .= ']]}}';
//    print($finishedString);
    file_put_contents("area".$i.".geojson", $finishedString);
}

$finishedString = '{
"type": "FeatureCollection",
"name": "gadm36_CZE_1",
"crs": { "type": "name", "properties": { "name": "urn:ogc:def:crs:OGC:1.3:CRS84" } },
"features": [';

for($i = 1;$i<10;$i++){
    $fileString=file_get_contents("area0".$i.".geojson");
    $finishedString.=$fileString.',';
}
for($i = 10;$i<14;$i++){
    $fileString=file_get_contents("area".$i.".geojson");
    $finishedString.=$fileString.',';
}
$fileString=file_get_contents("area14.geojson");
$finishedString.=$fileString;
$finishedString.=']
}
';
file_put_contents("areaCZ.geojson", $finishedString);
