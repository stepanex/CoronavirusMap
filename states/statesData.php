<?php
require_once('../database.php');
$http = 'http';
if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != null) {
    $http = 'https';
}
$address = $http . '://' . $_SERVER['SERVER_NAME'];
$result = [];

$dirs = array_filter(glob('*'), 'is_dir');
foreach ($dirs as $dir){
    $stateName = $dir;
    $url = $address.'/states/'.$stateName.'/regionsData.php';
    $file = @file_get_contents($url);
    if($file === FALSE)
        continue;
    $stateData = json_decode($file, true);
    $result[$stateName]['infected']=$stateData['infected'];
    if(isset($stateData['recovered']))
        $result[$stateName]['recovered']=$stateData['recovered'];
    if(isset($stateData['dead']))
        $result[$stateName]['dead']=$stateData['dead'];
}
echo json_encode($result);
die();