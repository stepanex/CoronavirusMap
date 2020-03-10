<?php
$getLastRevisionUrl = 'https://en.wikipedia.org/w/api.php?action=query&prop=revisions&pageids=63272856&rvlimit=1&rvprop=ids&format=json';
$lastRevisionId = json_decode(file_get_contents($getLastRevisionUrl), true)['query']['pages']['63272856']['revisions'][0]['revid'];
if(file_exists('./cache') && file_exists('./cacheId') && strval($lastRevisionId) == file_get_contents('./cacheId')){
    echo file_get_contents('./cache');
}else{
    $wiki1 =  file_get_contents("https://en.wikipedia.org/w/api.php?action=parse&prop=text&prop=sections&page=2020_coronavirus_outbreak_in_the_Czech_Republic&format=json");
    $wiki1Json = json_decode($wiki1, true);
    $sectionNumber = -1;
    foreach ($wiki1Json['parse']['sections'] as $section) {
        if($section['line'] == 'Cases'){
            $sectionNumber = $section['index'];
            break;
        }
    }
    if($sectionNumber == -1){
        echo 'FUCK';
        exit();
    }

    $newUrl = 'https://en.wikipedia.org/w/api.php?action=parse&section='.strval($sectionNumber).'&prop=text&page=2020_coronavirus_outbreak_in_the_Czech_Republic&format=json';
    $wiki2 = file_get_contents($newUrl);
    $wiki2Json = json_decode($wiki2, true);
    echo $wiki2;
    file_put_contents('./cache', $wiki2);
    file_put_contents('./cacheId', $lastRevisionId);
}
?>