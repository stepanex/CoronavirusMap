<?php
$pageid = '1570967';
$getLastRevisionUrl = 'https://cs.wikipedia.org/w/api.php?action=query&prop=revisions&pageids='.$pageid.'&rvlimit=1&rvprop=ids&format=json';
$lastRevisionId = json_decode(file_get_contents($getLastRevisionUrl), true)['query']['pages']['1570967']['revisions'][0]['revid'];
if(file_exists('./cache') && file_exists('./cacheId') && strval($lastRevisionId) == file_get_contents('./cacheId')){
    echo file_get_contents('./cache');
}else{
    $wiki1 =  file_get_contents('https://cs.wikipedia.org/w/api.php?action=parse&prop=text&prop=sections&pageid='.$pageid.'&format=json');
    $wiki1Json = json_decode($wiki1, true);
    $sectionNumber = -1;
    foreach ($wiki1Json['parse']['sections'] as $section) {
        if($section['line'] == 'Případy'){
            $sectionNumber = $section['index'];
            break;
        }
    }
    if($sectionNumber == -1){
        echo 'FUCK';
        exit();
    }

    $newUrl = 'https://cs.wikipedia.org/w/api.php?action=parse&section='.strval($sectionNumber).'&prop=text&pageid='.$pageid.'&format=json';
    $wiki2 = file_get_contents($newUrl);
    $wiki2Json = json_decode($wiki2, true);
    echo $wiki2;
    file_put_contents('./cache', $wiki2);
    file_put_contents('./cacheId', $lastRevisionId);
}
?>