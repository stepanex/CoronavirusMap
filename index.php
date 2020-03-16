<!DOCTYPE html>
<html>
<head>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/gh/openlayers/openlayers.github.io@master/en/v6.2.1/build/ol.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/openlayers/openlayers.github.io@master/en/v6.2.1/css/ol.css" type="text/css">

    <script src="https://cdn.maptiler.com/ol-mapbox-style/v5.0.2/olms.js"></script>
    <link rel="stylesheet" href="https://cdn.maptiler.com/ol/v6.0.0/ol.css">

    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-160373551-1"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());

        gtag('config', 'UA-160373551-1');
    </script>
    <title>Mapa koronaviru v České Republice</title>
    <meta charset="utf-8">
	<meta name="description" content="Interaktivní mapa koronaviru v České Republice">
    <meta name="keywords" content="mapa, koronavirus, coronavirus, počet, nakažených, aktuálně, online, regiony, česko, zprávy, koronavirus česko, koronavirus cesko,
        koronavirus počet nakažených,koronavirus dnes, koronavirus zpravy, brno koronavirus, brno, praha, středočeský kraj, kraj,
        cesko, koronavirus v cesku, koronavirus v česku, koronamap, corona, corona map, coronamap, korona map,
        koronamap.cz,koronamapa.cz, coronamap.cz, coronamapa.cz, Coronavirus, Koronavirus, koronavirus česká republika, koronavir,
        mapa koronaviru, coronavirus map, korona mapa, Czech, Czechia, Czech Republic, Česká Republika,
        České Republice, Čechách">
    <meta name="robots" content="index" />
    <meta name="googlebot" content="index" />
	<meta name="author" content="Štěpán Štrba">
    <link href="https://fonts.googleapis.com/css2?family=Oswald:wght@400;500;700&display=swap" rel="stylesheet">
    <link href="/style.css?v=1.1.2" rel="stylesheet">
    <!--<script data-ad-client="ca-pub-8503799930198018" async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>-->
</head>
<body>
<script>
    var state=
    <?php
    $state = "CZ";
    if(isset($_GET['state'])){
        $state = $_GET['state'];
    }
    echo '"'.$state.'"';
    ?>;
</script>
<div id="popup">
    <a href="#" id="popup-closer"></a>
    <div id="popup-content">
        <div id="popup-content">
            <div id="popupTitle"></div>
            <div id="popupCasesContainer">
                <div id="popupInfectedCount"></div>
                <div id="popupInfectedTitle"></div>
            </div>
        </div>
    </div>
</div>
<div class="logo">
    <div class="logoLeft">Korona</div>
    <div class="logoRight">Map</div>
</div>
<div class="info" id="info">
    <div class="infoPlaceName" id="infoPlaceName">
        -
    </div>
    <div class="infoCases">
        <div class="infectedContainer casesContainer" id="infectedContainer">
            <div class="infectedCount" id="infectedCount">
                -
            </div>
            <div class="infectedTitle" id="infectedTitle">
                -
            </div>
        </div>
        <div class="casesCasesContainer">
            <div class="deadContainer casesContainer" id="deadContainer">
                <div class="deadCount" id="deadCount">
                    -
                </div>
                <div class="deadTitle" id="deadTitle">
                    -
                </div>
            </div>
            <div class="recoveredContainer casesContainer" id="recoveredContainer">
                <div class="recoveredCount" id="recoveredCount">
                    -
                </div>
                <div class="recoveredTitle" id="recoveredTitle">
                    -
                </div>
            </div>
        </div>
    </div>
</div>
<div id="colors">
    <div class="colorTitle" id="colorTitle">
    </div>
    <div class="colorContainer">
        <div class="color" id="color01"></div>
        <div class="colorText">0</div>
    </div>
    <div class="colorContainer">
        <div class="color" id="color02"></div>
        <div class="colorText">0 - 10</div>
    </div>
    <div class="colorContainer">
        <div class="color" id="color03"></div>
        <div class="colorText">10 - 100</div>
    </div>
    <div class="colorContainer">
        <div class="color" id="color04"></div>
        <div class="colorText">100 - 500</div>
    </div>
    <div class="colorContainer">
        <div class="color" id="color05"></div>
        <div class="colorText">500 - 1000</div>
    </div>
    <div class="colorContainer">
        <div class="color" id="color06"></div>
        <div class="colorText">1000 < </div>
    </div>
</div>
<div id="map" class="map"></div>
<script src="/map.js?v=1.1.3"></script>
    <div class="footer footer_left">© 2020
        <a href="mailto:stepan.strba@gmail.com">Štěpán Štrba</a>,
        design:
        <a href="mailto:david@duong.cz">
            David Duong
        </a>
    </div>
    <div class="footer footer_right">
        <a href="https://cs.wikipedia.org/wiki/Epidemie_koronaviru_SARS-CoV-2_v_%C4%8Cesku">Zdroj dat</a>
    </div>
    <div class="footer_small">
        <div class="text"><a href="https://www.maptiler.com/copyright/" target="_blank">© MapTiler</a> <a href="https://www.openstreetmap.org/copyright" target="_blank">© OpenStreetMap contributors</a></div>
    </div>
</body>
</html>