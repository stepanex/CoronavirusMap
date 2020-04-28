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
$infectedRegion['Bratislava'] = null;
$infectedRegion['Žilina'] = null;
$infectedRegion['Košice'] = null;
$infectedRegion['Trnava'] = null;
$infectedRegion['Trenčín'] = null;
$infectedRegion['Prešov'] = null;
$infectedRegion['Banská Bystrica'] = null;
$infectedRegion['Nitra'] = null;

$wiki =  file_get_contents('https://en.wikipedia.org/w/api.php?action=query&prop=revisions&rvprop=content&format=json&pageids='.$pageid.'&rvprop=ids|content');
$wikiJson = json_decode($wiki, true);
$lastRevisionId = $wikiJson['query']['pages'][$pageid]['revisions'][0]['revid'];
$cachedRevidJson = json_decode($db->getCacheLastRevision($state, 'wikiInfo'), true);

function get_inner_html( $node ) {
    $innerHTML= '';
    $children = $node->childNodes;
    foreach ($children as $child) {
        $innerHTML .= $child->ownerDocument->saveXML( $child );
    }

    return $innerHTML;
}

if($cachedRevidJson[0] && intval($lastRevisionId) == intval($cachedRevidJson[1]['revid'])){
    echo json_decode($db->getCache($state, 'wikiInfo'),true)[1]['data'];
}else {
    $arr = addFileToArray($arr, $cachedRevidJson);
    $arr['errorCount'] = 0;
    $arr['error']=[];
    $newUrl = 'https://en.wikipedia.org/w/api.php?action=parse&prop=text&pageid=' . $pageid . '&format=json';
    $wiki = file_get_contents($newUrl);
    $wikiJson = json_decode($wiki, true);
    $wikiData = $wikiJson['parse']['text']['*'];

    $dom = new DomDocument();
    $dom->loadHTML($wikiData);
    $finder = new DomXPath($dom);
    $classname = "wikitable mw-collapsible";
    $nodes = $finder->query("//table[contains(@class, '$classname')]");
    if(count($nodes) != 1){
        $arr['errorCount'] += 1;
        array_push($arr['error'], 'Count of found tables != 1.');
        echo json_encode($arr);
        die();
    }
    // for some reason there are different indexes at server than on local
    // these here are the good ones for server

    $wikiTable = $nodes[0]->childNodes[2];
    $tableLength = $wikiTable->childNodes->length;
    $wikiTableHeader = $wikiTable->firstChild;
    $wikiTableFooter = $wikiTable->lastChild;
    for ($i = 2; $i < 18; $i += 2) {
        $regionName = $wikiTableHeader->childNodes[$i]->nodeValue;
        $regionName = str_replace(array("\n", "\r"), '', $regionName);
        $regionName = mb_convert_encoding($regionName, 'iso-8859-1','utf-8');
        $regionCount = $wikiTableFooter->childNodes[$i]->nodeValue;
        if(array_key_exists($regionName, $infectedRegion)){
            $infectedRegion[$regionName] = intval($regionCount);
        }
    }
    if(strpos($wikiTableHeader->childNodes[18]->nodeValue, 'Confirmed') !== false){
        $arr['infected'] = intval($wikiTableFooter->childNodes[20]->nodeValue);
		if($arr['infected']===0){
			$arr['errorCount']+=1;
			array_push($arr['error'], 'Confirmed number is 0.');
		}
    } else {
        $arr['errorCount']+=1;
        array_push($arr['error'], 'Confirmed number not at usual place.');
    }
    if(strpos($wikiTableHeader->childNodes[20]->nodeValue, 'Deaths') !== false){
        $arr['dead'] = intval($wikiTableFooter->childNodes[24]->nodeValue);
		if($arr['dead']===0){
			$arr['errorCount']+=1;
			array_push($arr['error'], 'Dead number is 0.');
		}
    } else {
        $arr['errorCount']+=1;
        array_push($arr['error'], 'Dead number not at usual place.');
    }
    if(strpos($wikiTableHeader->childNodes[22]->nodeValue, 'Recoveries') !== false){
        $arr['recovered'] = intval($wikiTableFooter->childNodes[28]->nodeValue);
		if($arr['recovered']===0){
			$arr['errorCount']+=1;
			array_push($arr['error'], 'Recovered number is 0.');
		}
    } else {
        $arr['errorCount']+=1;
        array_push($arr['error'], 'Recovered number not at usual place.');
    }

    $arr['infectedRegion'] = $infectedRegion;
    echo json_encode($arr);
    $db->setCache($state, $lastRevisionId, json_encode($arr), 'wikiInfo');
    $db->setStateInfected($state, $arr['infected']);
}

