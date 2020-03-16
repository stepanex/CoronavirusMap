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
$arr['Praha'] = null;
$arr['Královéhradecký kraj'] = null;
$arr['Karlovarský kraj'] = null;
$arr['Liberecký kraj'] = null;
$arr['Moravskoslezský kraj'] = null;
$arr['Olomoucký kraj'] = null;
$arr['Pardubický kraj'] = null;
$arr['Plzeňský kraj'] = null;
$arr['Středočeský kraj'] = null;
$arr['Jihočeský kraj'] = null;
$arr['Jihomoravský kraj'] = null;
$arr['Ústecký kraj'] = null;
$arr['Kraj Vysočina'] = null;
$arr['Zlínský kraj'] = null;

$regionsCount = 0;

$apify = file_get_contents('https://api.apify.com/v2/key-value-stores/K373S4uCFR9W1K8ei/records/LATEST?disableRedirect=true');
$apifyJson = json_decode($apify, true);
if(isset($apifyJson['infectedByRegion']) && isset($apifyJson['infected'])){
    $arr['infected'] = $apifyJson['infected'];
    $regions = $apifyJson['infectedByRegion'];
    foreach ($regions as $region){
        if($region['region'] == 'Vysočina')
            $region['region'] = 'Kraj Vysočina';
        if(array_key_exists($region['region'], $arr)){
            $arr[$region['region']] = $region['value'];
            $regionsCount++;
        }
    }

    if($regionsCount == 14){
        $arr['dead'] = 0;
        $arr['recovered'] = 0;
        echo json_encode($arr);
        die();
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
    $arr = addFileToArray($arr, $cachedRevidJson);

    $arr['errorCount'] = 0;
    $arr['error'] = [];
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
            if(array_key_exists($regionName, $arr) && $regionCount!== "" && intval($regionCount)>=0){
                $arr[$regionName] = $regionCount;
            }
        }
    }
    echo json_encode($arr);
    $db->setCache($state, $lastRevisionId, json_encode($arr), 'wikiInfo');
}
