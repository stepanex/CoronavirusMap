<?php
$state = 'CZ';

$arr = [];
$arr['errorCount'] = 0;
$arr['error'] = [];
$arr['infected'] = null;
$arr['dead'] = null;
$arr['recovered'] = null;
$arr['reproduction'] = null;

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

$apifyReproduction = file_get_contents('https://api.apify.com/v2/key-value-stores/DO0Mg4d1cPbWhtPSD/records/LATEST?disableRedirect=true');
$apifyReproductionJson = json_decode($apifyReproduction, true);
if(isset($apifyReproductionJson['data']) && isset($apifyReproductionJson['data'][0]) && isset($apifyReproductionJson['data'][0][3]))
    $arr['reproduction'] = $apifyReproductionJson['data'][0][3];

$apify = file_get_contents('https://api.apify.com/v2/key-value-stores/K373S4uCFR9W1K8ei/records/LATEST?disableRedirect=true');
$apifyJson = json_decode($apify, true);

if(isset($apifyJson['infected'])){
    $arr['infected'] = $apifyJson['infected'];
} else {
    $arr['errorCount'] += 1;
    array_push($arr['error'], 'Apify didnt find infected.');
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
if(isset($apifyJson['infectedByRegion'])){
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
	if($infectedRegionsCount != 14){
        $arr['errorCount'] += 1;
        array_push($arr['error'], 'Apify didnt find all infected regions.');
    }
} else {
    $arr['errorCount'] += 1;
    array_push($arr['error'], 'Apify didnt find infectedByRegion.');
}
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
	if($deadRegionsCount != 14){
        $arr['errorCount'] += 1;
        array_push($arr['error'], 'Apify didnt find all dead regions.');
    }
} else {
    $arr['errorCount'] += 1;
    array_push($arr['error'], 'Apify didnt find deceasedByRegion.');
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
	if($recoveredRegionsCount != 14){
        $arr['errorCount'] += 1;
        array_push($arr['error'], 'Apify didnt find all recovered regions.');
    }
} else {
    $arr['errorCount'] += 1;
    array_push($arr['error'], 'Apify didnt find recoveredByRegion.');
}

echo json_encode($arr);