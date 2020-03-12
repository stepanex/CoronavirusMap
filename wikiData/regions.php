<?php
function addFileToArray($array, $filename){
    if(file_exists($filename)){
        $fileJson = json_decode(file_get_contents($filename, true));
        if($fileJson!=null){
            foreach ($fileJson as $region=>$count){
                $array[$region] = $count;
            }
        }
    }
    return $array;
}
$pageid = '1570967';
$arr = [];
$arr['casesCount'] = 0;
$arr['error'] = [];
$delimeter = '';
$cacheFilename = './regionsCache';
$cacheRevIdFilename = './regionsCacheId';
$wiki1 =  file_get_contents('https://cs.wikipedia.org/w/api.php?action=query&prop=revisions&rvprop=content&format=json&pageids='.$pageid.'&rvsection=0&rvprop=ids|content');
$wiki1Json = json_decode($wiki1, true);
$lastRevisionId = $wiki1Json['query']['pages'][$pageid]['revisions'][0]['revid'];
if(file_exists($cacheFilename) && file_exists($cacheRevIdFilename) && strval($lastRevisionId) == file_get_contents($cacheRevIdFilename)){
    echo file_get_contents($cacheFilename, true);
}else{
    $wiki1Html =  $wiki1Json['query']['pages'][$pageid]['revisions'][0]['*'];
    //getting counts
    try{
        $delimeter = '| nakažení = ';
        if (strpos($wiki1Html, $delimeter) === false) {
            $arr = addFileToArray($arr, $cacheFilename);
            $arr['errorCount'] = 1;
            array_push($arr['error'], 'Doesnt contain "'.$delimeter.'".');
            throw new Exception();
        }
        $infectedHtml = explode($delimeter,$wiki1Html)[1];

        $delimeter = '| úmrtí = ';
        if (strpos($infectedHtml, $delimeter) === false) {
            $arr = addFileToArray($arr, $cacheFilename);
            $arr['errorCount'] = 1;
            array_push($arr['error'], 'Doesnt contain "'.$delimeter.'".');
            throw new Exception();
        }
        $infectedBase = explode($delimeter,$infectedHtml)[0];
        $deadHtml = explode($delimeter,$infectedHtml)[1];

        $delimeter = '| zotavení = ';
        if (strpos($deadHtml, $delimeter) === false) {
            $arr = addFileToArray($arr, $cacheFilename);
            $arr['errorCount'] = 1;
            array_push($arr['error'], 'Doesnt contain "'.$delimeter.'".');
            throw new Exception();
        }
        $deadBase = explode($delimeter,$deadHtml)[0];
        $recoveredHtml = explode($delimeter,$deadHtml)[1];

        $delimeter = '| opatření = ';
        if (strpos($recoveredHtml, $delimeter) === false) {
            $arr = addFileToArray($arr, $cacheFilename);
            $arr['errorCount'] = 1;
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
    $delimeter = '| rozšíření = ';
    if (strpos($wiki1Html, $delimeter) === false) {
        $arr = addFileToArray($arr, $cacheFilename);
        $arr['errorCount'] = 1;
        array_push($arr['error'], 'Doesnt contain "'.$delimeter.'", using older cache if available.');
        echo json_encode($arr);
        die();
    }
    $wiki1HtmlExploded = explode($delimeter,$wiki1Html)[1];
    $delimeter = '{{Citace elektronické';
    if (strpos($wiki1HtmlExploded, $delimeter) === false) {
        $arr = addFileToArray($arr, $cacheFilename);
        $arr['errorCount'] = 1;
        array_push($arr['error'], 'Doesnt contain "'.$delimeter.'", using older cache if available.');
        echo json_encode($arr);
        die();
    }
    $regionsHtml = explode($delimeter, $wiki1HtmlExploded)[0];
    $delimeter = '[[';
    if (strpos($wiki1HtmlExploded, $delimeter) === false) {
        $arr = addFileToArray($arr, $cacheFilename);
        $arr['errorCount'] = 1;
        array_push($arr['error'], 'Doesnt contain "'.$delimeter.'", using older cache if available.');
        echo json_encode($arr);
        die();
    }
    $regionsHtmlExploded =  explode($delimeter, $regionsHtml);
    $arr['errorCount'] = 0;
    foreach ($regionsHtmlExploded as $region ){
        if (strpos($region, ']]') !== false) {
            $regionExploded = explode(']]', $region);
            $regionCount = $res = preg_replace("/[^0-9]/", "", $regionExploded[1]);
            $arr[$regionExploded[0]] = $regionCount;
        }
    }
    echo json_encode($arr);
    file_put_contents($cacheFilename, json_encode($arr));
    file_put_contents($cacheRevIdFilename, $lastRevisionId);
}

