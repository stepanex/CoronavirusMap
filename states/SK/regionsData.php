<?php
require_once('../../database.php');

$state = 'SK';

function addFileToArray($array, $cachedRevidJson){
    global $state;
    $db = new database();
    if($cachedRevidJson[0]!=-1){
        $fileJson = json_decode($db->getCache($state, 'wikiInfo'),true)[1]['data'];
        if($fileJson!=null){
            foreach ($fileJson as $region=>$count){
                $array[$region] = $count;
            }
        }
    }
    return $array;
}

$db = new database();
$pageid = '63300608';
$arr = [];
$arr['error'] = [];
$delimeter = '';

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

if($cachedRevidJson[0] && intval($lastRevisionId) <= intval($cachedRevidJson[1]['revid'])){
    echo json_decode($db->getCache($state, 'wikiInfo'),true)[1]['data'];
}else {
    $newUrl = 'https://en.wikipedia.org/w/api.php?action=parse&section=' . strval($sectionNumber) . '&prop=text&pageid=' . $pageid . '&format=json';
    $wiki = file_get_contents($newUrl);
    $wikiJson = json_decode($wiki, true);
    $wikiData = $wikiJson['parse']['text']['*'];

    $dom = new DomDocument();
    $dom->loadHTML($wikiData);
    $finder = new DomXPath($dom);
    $classname = "wikitable mw-collapsible";
    $nodes = $finder->query("//table[contains(@class, '$classname')]");

    $wikiTable = $nodes[0]->childNodes[3];
    $wikiTableHeader = $wikiTable->childNodes[0];
    $wikiTableFooter = $wikiTable->childNodes[26];
    for ($i = 3; $i < 18; $i += 2) {
        $regionName = $wikiTableHeader->childNodes[$i]->nodeValue;
        $regionName = str_replace(array("\n", "\r"), '', $regionName);
        $regionName = mb_convert_encoding($regionName, 'iso-8859-1','utf-8');
        $regionCount = $wikiTableFooter->childNodes[$i]->nodeValue;
        $arr[$regionName] = intval($regionCount);
    }

    $arr['infected'] = intval($wikiTableFooter->childNodes[21]->nodeValue);
    $arr['dead'] = intval($wikiTableFooter->childNodes[23]->nodeValue);
    $arr['recovered'] = intval($wikiTableFooter->childNodes[25]->nodeValue);
    echo json_encode($arr);
    $db->setCache($state, $lastRevisionId, json_encode($arr), 'wikiInfo');
    $db->setStateInfected($state, $arr['infected']);
}

