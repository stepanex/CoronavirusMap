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

$pageid = '63300608';
$arr = [];
$arr['errorCount'] = 0;
$arr['error'] = [];
$arr['infected'] = null;
$arr['dead'] = null;
$arr['recovered'] = null;
$arr['Bratislava'] = null;
$arr['Žilina'] = null;
$arr['Košice'] = null;
$arr['Trnava'] = null;
$arr['Trenčín'] = null;
$arr['Prešov'] = null;
$arr['Banská Bystrica'] = null;
$arr['Nitra'] = null;

$wiki =  file_get_contents('https://en.wikipedia.org/w/api.php?action=parse&prop=text&prop=sections&pageid='.$pageid.'&format=json');
$wikiJson = json_decode($wiki, true);
$sectionNumber = -1;
foreach ($wikiJson['parse']['sections'] as $section) {
    if($section['line'] == 'Cases'){
        $sectionNumber = $section['index'];
        break;
    }
}
if($sectionNumber == -1){
    $arr['errorCount'] += 1;
    array_push($arr['error'], 'Didnt find section number');
    echo json_encode($arr);
    die();
}
$wiki =  file_get_contents('https://en.wikipedia.org/w/api.php?action=query&prop=revisions&rvprop=content&format=json&pageids='.$pageid.'&rvsection='.$sectionNumber.'&rvprop=ids|content');
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

if($cachedRevidJson[0] && intval($lastRevisionId) == intval($cachedRevidJson[1]['revid']) && false){
    echo json_decode($db->getCache($state, 'wikiInfo'),true)[1]['data'];
}else {
    $arr = addFileToArray($arr, $cachedRevidJson);
    $newUrl = 'https://en.wikipedia.org/w/api.php?action=parse&section=' . strval($sectionNumber) . '&prop=text&pageid=' . $pageid . '&format=json';
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
        array_push($arr['error'], 'Count of found tables != 1, using older cache if available.');
        echo json_encode($arr);
        die();
    }

    $wikiTable = $nodes[0]->childNodes[3];
    $tableLength = $wikiTable->childNodes->length;
    $wikiTableHeader = $wikiTable->childNodes[0];
    $wikiTableFooter = $wikiTable->childNodes[$tableLength - 1];
    for ($i = 3; $i < 18; $i += 2) {
        $regionName = $wikiTableHeader->childNodes[$i]->nodeValue;
        $regionName = str_replace(array("\n", "\r"), '', $regionName);
        $regionName = mb_convert_encoding($regionName, 'iso-8859-1','utf-8');
        $regionCount = $wikiTableFooter->childNodes[$i]->nodeValue;
        if(array_key_exists($regionName, $arr)){
            $arr[$regionName] = intval($regionCount);
        }
    }
    if(strpos($wikiTableHeader->childNodes[19]->nodeValue, 'Confirmed') !== false){
        $arr['infected'] = intval($wikiTableFooter->childNodes[19]->nodeValue);
    }
    if(strpos($wikiTableHeader->childNodes[21]->nodeValue, 'Deaths') !== false){
        $arr['dead'] = intval($wikiTableFooter->childNodes[21]->nodeValue);
    }
    if(strpos($wikiTableHeader->childNodes[23]->nodeValue, 'Confirmed') !== false){
        $arr['recovered'] = intval($wikiTableFooter->childNodes[23]->nodeValue);
    }
    echo json_encode($arr);
    $db->setCache($state, $lastRevisionId, json_encode($arr), 'wikiInfo');
    $db->setStateInfected($state, $arr['infected']);
}

