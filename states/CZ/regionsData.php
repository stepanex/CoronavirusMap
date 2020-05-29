<?php
require_once('../../database.php');

$state = 'CZ';

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

$arr = [];
$arr['errorCount'] = 0;
$arr['error'] = [];
$arr['infected'] = null;
$arr['dead'] = null;
$arr['recovered'] = null;

$infectedRegion = [];
$infectedRegion['Praha'] = null;
$infectedRegion['Královéhradecký kraj'] = null;
$infectedRegion['Karlovarský kraj'] = null;
$infectedRegion['Liberecký kraj'] = null;
$infectedRegion['Moravskoslezský kraj'] = null;
$infectedRegion['Olomoucký kraj'] = null;
$infectedRegion['Pardubický kraj'] = null;
$infectedRegion['Plzeňský kraj'] = null;
$infectedRegion['Středočeský kraj'] = null;
$infectedRegion['Jihočeský kraj'] = null;
$infectedRegion['Jihomoravský kraj'] = null;
$infectedRegion['Ústecký kraj'] = null;
$infectedRegion['Kraj Vysočina'] = null;
$infectedRegion['Zlínský kraj'] = null;

$arr['infectedRegion'] = $infectedRegion;

$deadRegion =[];
$deadRegion['Praha'] = null;
$deadRegion['Královéhradecký kraj'] = null;
$deadRegion['Karlovarský kraj'] = null;
$deadRegion['Liberecký kraj'] = null;
$deadRegion['Moravskoslezský kraj'] = null;
$deadRegion['Olomoucký kraj'] = null;
$deadRegion['Pardubický kraj'] = null;
$deadRegion['Plzeňský kraj'] = null;
$deadRegion['Středočeský kraj'] = null;
$deadRegion['Jihočeský kraj'] = null;
$deadRegion['Jihomoravský kraj'] = null;
$deadRegion['Ústecký kraj'] = null;
$deadRegion['Kraj Vysočina'] = null;
$deadRegion['Zlínský kraj'] = null;

$recoveredRegion =[];
$recoveredRegion['Praha'] = null;
$recoveredRegion['Královéhradecký kraj'] = null;
$recoveredRegion['Karlovarský kraj'] = null;
$recoveredRegion['Liberecký kraj'] = null;
$recoveredRegion['Moravskoslezský kraj'] = null;
$recoveredRegion['Olomoucký kraj'] = null;
$recoveredRegion['Pardubický kraj'] = null;
$recoveredRegion['Plzeňský kraj'] = null;
$recoveredRegion['Středočeský kraj'] = null;
$recoveredRegion['Jihočeský kraj'] = null;
$recoveredRegion['Jihomoravský kraj'] = null;
$recoveredRegion['Ústecký kraj'] = null;
$recoveredRegion['Kraj Vysočina'] = null;
$recoveredRegion['Zlínský kraj'] = null;

$infectedRegionsCount = 0;
$deadRegionsCount = 0;
$recoveredRegionsCount = 0;


$apify = file_get_contents('https://api.apify.com/v2/key-value-stores/K373S4uCFR9W1K8ei/records/LATEST?disableRedirect=true');
$apifyJson = json_decode($apify, true);


if(isset($apifyJson['infectedByRegion']) && isset($apifyJson['infected']) && isset($apifyJson['recovered'])
    && isset($apifyJson['deceased'])){
    $arr['infected'] = $apifyJson['infected'];
    $arr['dead'] = $apifyJson['deceased'];
    $arr['recovered'] = $apifyJson['recovered'];

    $apifyInfectedRegions = $apifyJson['infectedByRegion'];
    foreach ($apifyInfectedRegions as $region){
		if(isset($region['name'])){
			if($region['name'] == 'Vysočina')
				$region['name'] = 'Kraj Vysočina';
			if($region['name'] == 'Hlavní město Praha')
				$region['name'] = 'Praha';
			if(array_key_exists($region['name'], $infectedRegion)){
                $infectedRegion[$region['name']] = $region['value'];
                $infectedRegionsCount++;
			}
		}
    }
    $arr['infectedRegion'] = $infectedRegion;

    if(isset($apifyJson['deceasedByRegion'])){
        $apifyDeadRegions = $apifyJson['deceasedByRegion'];
        foreach ($apifyDeadRegions as $region){
            if(isset($region['name'])){
                if($region['name'] == 'Vysočina')
                    $region['name'] = 'Kraj Vysočina';
                if($region['name'] == 'Hlavní město Praha')
                    $region['name'] = 'Praha';
                if(array_key_exists($region['name'], $deadRegion)){
                    $deadRegion[$region['name']] = $region['value'];
                    $deadRegionsCount++;
                }
            }
        }
        $arr['deadRegion'] = $deadRegion;
    }

    if(isset($apifyJson['recoveredByRegion'])){
        $apifyRecoveredRegions = $apifyJson['recoveredByRegion'];
        foreach ($apifyRecoveredRegions as $region){
            if(isset($region['name'])){
                if($region['name'] == 'Vysočina')
                    $region['name'] = 'Kraj Vysočina';
                if($region['name'] == 'Hlavní město Praha')
                    $region['name'] = 'Praha';
                if(array_key_exists($region['name'], $recoveredRegion)){
                    $recoveredRegion[$region['name']] = $region['value'];
                    $recoveredRegionsCount++;
                }
            }
        }
        $arr['recoveredRegion'] = $recoveredRegion;
    }

    if($infectedRegionsCount != 14){
        $arr['errorCount'] += 1;
        array_push($arr['error'], 'Apify didnt find all infected regions.');
    }

    if(isset($apifyJson['deceasedByRegion']) && $deadRegionsCount != 14){
        $arr['errorCount'] += 1;
        array_push($arr['error'], 'Apify didnt find all dead regions.');
    }

    if(isset($apifyJson['recoveredByRegion']) && $recoveredRegionsCount != 14){
        $arr['errorCount'] += 1;
        array_push($arr['error'], 'Apify didnt find all recovered regions.');
    }

} else {
    if(!isset($apifyJson['infectedByRegion'])){
        $arr['errorCount'] += 1;
        array_push($arr['error'], 'Apify didnt find infectedByRegion.');
    }
    if(!isset($apifyJson['infected'])){
        $arr['errorCount'] += 1;
        array_push($arr['error'], 'Apify didnt find infected.');
    }
    if(!isset($apifyJson['recovered'])){
        $arr['errorCount'] += 1;
        array_push($arr['error'], 'Apify didnt find recovered.');
    }
    if(!isset($apifyJson['deceased'])){
        $arr['errorCount'] += 1;
        array_push($arr['error'], 'Apify didnt find deceased.');
    }
}

echo json_encode($arr);