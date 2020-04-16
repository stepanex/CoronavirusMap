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

$infectedRegionsCount = 0;
$deadRegionsCount = 0;


$apify = file_get_contents('https://api.apify.com/v2/key-value-stores/K373S4uCFR9W1K8ei/records/LATEST?disableRedirect=true');
$apifyJson = json_decode($apify, true);
if(isset($apifyJson['infectedByRegion']) && isset($apifyJson['infected']) && isset($apifyJson['recovered'])
    && isset($apifyJson['deceased'])){
    $arr['infected'] = $apifyJson['infected'];
    $arr['dead'] = $apifyJson['deceased'];
    $arr['recovered'] = $apifyJson['recovered'];

    $infectedRegions = $apifyJson['infectedByRegion'];
    foreach ($infectedRegions as $region){
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

    if(isset($apifyJson['deceasedByRegion'])){
        $deadRegions = $apifyJson['deceasedByRegion'];
        foreach ($deadRegions as $region){
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

    if($infectedRegionsCount == 14 && $deadRegionsCount == 14){
        $arr['infectedRegion'] = $infectedRegion;
        echo json_encode($arr);
        die();
    }

    if($infectedRegionsCount != 14){
        $arr['errorCount'] += 1;
        array_push($arr['error'], 'Apify didnt find all infected regions.');
    }

    if(isset($apifyJson['deceasedByRegion']) && $deadRegionsCount != 14){
        $arr['errorCount'] += 1;
        array_push($arr['error'], 'Apify didnt find all dead regions.');
    }
}

$pageid = '1570967';
$delimeter = '';
$wiki1 =  file_get_contents('https://cs.wikipedia.org/w/api.php?action=query&prop=revisions&rvprop=content&format=json&pageids='.$pageid.'&rvsection=0&rvprop=ids|content');
$wiki1Json = json_decode($wiki1, true);
$lastRevisionId = $wiki1Json['query']['pages'][$pageid]['revisions'][0]['revid'];
$cachedRevidJson = json_decode($db->getCacheLastRevision($state, 'wikiInfo'), true);

if($cachedRevidJson[0] && intval($lastRevisionId) == intval($cachedRevidJson[1]['revid'])){
    echo json_decode($db->getCache($state, 'wikiInfo'),true)[1]['data'];
}else{
    $wiki1Html =  $wiki1Json['query']['pages'][$pageid]['revisions'][0]['*'];
	$errTmp = $arr['error'];
	$errCount = $arr['errorCount'];
    $arr = addFileToArray($arr, $cachedRevidJson);
    $arr['errorCount'] = $errCount;
    $arr['error'] = $errTmp;
    //getting counts
    try{
        $delimeter = '| nakažení =';
        if (strpos($wiki1Html, $delimeter) === false) {
            $arr['errorCount'] += 1;
            array_push($arr['error'], 'Doesnt contain "'.$delimeter.'".');
            throw new Exception();
        }
        $infectedHtml = explode($delimeter,$wiki1Html)[1];

        $delimeter = '| úmrtí =';
        if (strpos($infectedHtml, $delimeter) === false) {
            $arr['errorCount'] += 1;
            array_push($arr['error'], 'Doesnt contain "'.$delimeter.'".');
            throw new Exception();
        }
        $infectedBase = explode($delimeter,$infectedHtml)[0];
        $deadHtml = explode($delimeter,$infectedHtml)[1];

        $delimeter = '| zotavení =';
        if (strpos($deadHtml, $delimeter) === false) {
            $arr['errorCount'] += 1;
            array_push($arr['error'], 'Doesnt contain "'.$delimeter.'".');
            throw new Exception();
        }
        $deadBase = explode($delimeter,$deadHtml)[0];
        $recoveredHtml = explode($delimeter,$deadHtml)[1];

        $delimeter = '| opatření =';
        if (strpos($recoveredHtml, $delimeter) === false) {
            $arr['errorCount'] += 1;
            array_push($arr['error'], 'Doesnt contain "'.$delimeter.'".');
            throw new Exception();
        }
        $recoveredBase = explode($delimeter,$recoveredHtml)[0];

        $infectedNumber = intval(explode('(', $infectedBase)[0]);
        $deadNumber = intval(explode('(', $deadBase)[0]);
        $recoveredNumber = intval(explode('(', $recoveredBase)[0]);
        $arr['infected'] = $infectedNumber;
        $arr['dead'] = $deadNumber;
        $arr['recovered'] = $recoveredNumber;
    } catch (Exception $e){
    }
    //\getting counts
    $delimeter = '| nakažení';
    if (strpos($wiki1Html, $delimeter) === false) {
        $arr['errorCount'] += 1;
        array_push($arr['error'], 'Doesnt contain "'.$delimeter.'", using older cache if available.');
        echo json_encode($arr);
        die();
    }
    $wiki1HtmlExploded = explode($delimeter,$wiki1Html)[0];
    $delimeter = '</ref>';
    if (strpos($wiki1HtmlExploded, $delimeter) === false) {
        $arr['errorCount'] += 1;
        array_push($arr['error'], 'Doesnt contain "'.$delimeter.'", using older cache if available.');
        echo json_encode($arr);
        die();
    }
    $wiki1HtmlExploded = explode($delimeter,$wiki1HtmlExploded)[1];
    $delimeter = '[[';
    if (strpos($wiki1HtmlExploded, $delimeter) === false) {
        $arr['errorCount'] += 1;
        array_push($arr['error'], 'Doesnt contain "'.$delimeter.'", using older cache if available.');
        echo json_encode($arr);
        die();
    }
    $regionsHtmlExploded =  explode($delimeter, $wiki1HtmlExploded);
    foreach ($regionsHtmlExploded as $region ){
        if (strpos($region, ']]') !== false) {
            $regionExploded = explode(']]', $region);
            $regionName = $regionExploded[0];
            $regionName = str_replace(array("\n", "\r"), '', $regionName);

            $regionCount = preg_replace("/[^0-9]/", "", $regionExploded[1]);
            if(array_key_exists($regionName, $infectedRegion) && $regionCount!== "" && intval($regionCount)>=0){
                $infectedRegion[$regionName] = $regionCount;
            }
        }
    }
    $arr['infectedRegion'] = $infectedRegion;
    echo json_encode($arr);
    $db->setCache($state, $lastRevisionId, json_encode($arr), 'wikiInfo');
}
