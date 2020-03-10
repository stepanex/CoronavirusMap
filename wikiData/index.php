<?php
if(file_exists('./cache') && (time() - filemtime('./cache')) <= 300){
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
}
?>