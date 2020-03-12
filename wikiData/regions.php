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
$arr = [];
$cacheFilename = './regionsCache';
$cacheRevIdFilename = './regionsCacheId';
$wiki1 =  file_get_contents("https://cs.wikipedia.org/w/api.php?action=query&prop=revisions&rvprop=content&format=json&titles=Epidemie_koronaviru_SARS-CoV-2_v_%C4%8Cesku&rvsection=0&rvprop=ids|content");
$wiki1Json = json_decode($wiki1, true);
$lastRevisionId = $wiki1Json['query']['pages']['1570967']['revisions'][0]['revid'];
$lastRevisionId=14;
if(file_exists($cacheFilename) && file_exists($cacheRevIdFilename) && strval($lastRevisionId) == file_get_contents($cacheRevIdFilename)){
    echo file_get_contents($cacheFilename, true);
}else{
    $wiki1Html =  $wiki1Json['query']['pages']['1570967']['revisions'][0]['*'];
    if (strpos($wiki1Html, '| rozšíření = ') === false) {
        $arr = addFileToArray($arr, $cacheFilename);
        $arr['errorCount'] = 1;
        $arr['error'] = 'Doesnt contain "| rozšíření = ", using older cache if available.';
        echo json_encode($arr);
        die();
    }
    $wiki1HtmlExploded = explode('| rozšíření = ',$wiki1Html)[1];
    if (strpos($wiki1HtmlExploded, '{{Citace elektronické') === false) {
        $arr = addFileToArray($arr, $cacheFilename);
        $arr['errorCount'] = 1;
        $arr['error'] = 'Doesnt contain "{{Citace elektronické", using older cache if available.';
        echo json_encode($arr);
        die();
    }
    $regionsHtml = explode('{{Citace elektronické', $wiki1HtmlExploded)[0];
    if (strpos($wiki1HtmlExploded, '[[') === false) {
        $arr = addFileToArray($arr, $cacheFilename);
        $arr['errorCount'] = 1;
        $arr['error'] = 'Doesnt contain "[[", using older cache if available.';
        echo json_encode($arr);
        die();
    }
    $regionsHtmlExploded =  explode('[[', $regionsHtml);
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

