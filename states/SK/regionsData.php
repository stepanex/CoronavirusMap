<?php
require_once('../../database.php');

$state = 'SK';

$db = new database();
$dbStatus = $db->getStatusArr();
if(!($dbStatus[0])){
    $arr['errorCount'] +=1;
    array_push($arr['error'], $dbStatus[1]);
    echo json_encode($arr);
    die();
}

function addFileToArray($array, $cachedRevidJson){
    global $state;
    global $db;
    if($cachedRevidJson[0]){
        $fileJson = json_decode(json_decode($db->getCache($state, 'wikiInfo'),true)[1]['data']);
        if($fileJson!=null){
            foreach ($fileJson as $region=>$count){
                if(array_key_exists($region, $array)){
                    $array[$region] = $count;
                }
            }
        }
    }
    return $array;
}
$pageid = '63417582';
$arr = [];
$arr['errorCount'] = 0;
$arr['error'] = [];
$arr['infected'] = null;
$arr['dead'] = null;
$arr['recovered'] = null;

$infectedRegion = [];
$infectedRegion['Bratislavský kraj'] = null;
$infectedRegion['Žilinský kraj'] = null;
$infectedRegion['Košický kraj'] = null;
$infectedRegion['Trnavský kraj'] = null;
$infectedRegion['Trenčiansky kraj'] = null;
$infectedRegion['Prešovský kraj'] = null;
$infectedRegion['Banskobystrický kraj'] = null;
$infectedRegion['Nitriansky kraj'] = null;

$infectedRegionsCount = 0;

$apify = file_get_contents('https://api.apify.com/v2/key-value-stores/GlTLAdXAuOz6bLAIO/records/LATEST?disableRedirect=true');
$apifyJson = json_decode($apify, true);

if(isset($apifyJson['infectedPCR'])){
    $arr['infected'] = $apifyJson['infectedPCR'];
}
else if(isset($apifyJson['infected'])){
    $arr['infected'] = $apifyJson['infected'];
} else {
    $arr['errorCount'] += 1;
    array_push($arr['error'], 'Apify didnt find infected count.');
}
if(isset($apifyJson['recovered'])){
    $arr['recovered'] = $apifyJson['recovered'];
} else {
    $arr['errorCount'] += 1;
    array_push($arr['error'], 'Apify didnt find recovered.');
}
if(isset($apifyJson['deceased'])){
    $arr['dead'] = $apifyJson['deceased'];
} else {
    $arr['errorCount'] += 1;
    array_push($arr['error'], 'Apify didnt find deceased.');
}
if(isset($apifyJson['regionsData'])){
	$apifyInfectedRegions = $apifyJson['regionsData'];
    foreach ($apifyInfectedRegions as $region){
		if(isset($region['region'])){
			if(array_key_exists($region['region'], $infectedRegion)){
                $infectedRegion[$region['region']] = (int)filter_var($region['totalInfected'], FILTER_SANITIZE_NUMBER_INT);;
                $infectedRegionsCount++;
			}
		}
    }
    $arr['infectedRegion'] = $infectedRegion;
	if($infectedRegionsCount != 8){
        $arr['errorCount'] += 1;
        array_push($arr['error'], 'Apify didnt find all infected regions.');
    }
} else {
    $arr['errorCount'] += 1;
    array_push($arr['error'], 'Apify didnt find regionsData.');
}
echo json_encode($arr);