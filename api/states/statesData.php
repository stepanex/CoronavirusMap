<?php
$http = 'http';
if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != null) {
    $http = 'https';
}
$address = $http . '://' . $_SERVER['HTTP_HOST'];
$result = [];

$arrContextOptions=array(
    "ssl"=>array(
        "verify_peer"=>false,
        "verify_peer_name"=>false,
    ),
);

$dirs = ['CZ', 'SK'];
foreach ($dirs as $dir) {
    $stateName = $dir;
    $url = $address . '/states/' . $stateName . '/regionsData.php';
    $file = @file_get_contents($url, false, stream_context_create($arrContextOptions));
    if ($file === FALSE){
        continue;
	}
    $stateData = json_decode($file, true);
    $result[$stateName]['infected'] = $stateData['infected'];
    if (isset($stateData['recovered']))
        $result[$stateName]['recovered'] = $stateData['recovered'];
    if (isset($stateData['dead']))
        $result[$stateName]['dead'] = $stateData['dead'];
}
echo json_encode($result);
die();