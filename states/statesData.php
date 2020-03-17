<?php
require_once('../database.php');
$http = 'http';
if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != null) {
    $http = 'https';
}
$address = $http . '://' . $_SERVER['SERVER_NAME'];
$result = [];
$db = new database();
$statesResult = json_decode($db->getStates(), true);
if($statesResult[0]){
    $statesJson = $statesResult[1];
    foreach ($statesJson as $state){
        $stateName = $state['state'];
        $stateData = json_decode(file_get_contents($address.'/states/'.$stateName.'/regionsData.php'), true);
        $result[$stateName]=$stateData['infected'];
    }
    echo json_encode($result);
}