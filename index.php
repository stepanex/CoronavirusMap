<!DOCTYPE html>
<html>
<head>
	<base target="_parent" />
    <script type="text/javascript" src="https://cdn.jsdelivr.net/gh/openlayers/openlayers.github.io@master/en/v6.2.1/build/ol.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/openlayers/openlayers.github.io@master/en/v6.2.1/css/ol.css" type="text/css">

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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Interaktivní mapa počtu nakažení, úmrtí a uzdravení na koronavir v České Republice podle krajů.">
    <meta name="og:description" content="Interaktivní mapa počtu nakažení, úmrtí a uzdravení na koronavir v České Republice podle krajů.">
    <meta name="keywords" content="mapa koronaviru, mapa, koronavirus, coronavirus, počet, nakažených, aktuálně, online,
    regiony, česko, zprávy, koronavirus česko, koronavirus cesko, koronavirus počet nakažených,koronavirus mapa,
    koronavirus dnes, koronavirus zpravy, brno koronavirus, brno, praha, středočeský kraj, kraj, cesko,
    koronavirus v cesku, koronavirus v česku, koronamap, corona, corona map, coronamap, korona map, koronamap.cz,
    koronamapa.cz, coronamap.cz, coronamapa.cz, Coronavirus, Koronavirus, koronavirus česká republika, koronavir,
    coronavirus map, korona mapa, Czech, Czechia, Czech Republic, Česká Republika, České Republice, Čechách">
	<meta name="author" content="Štěpán Štrba">
    <meta name="robots" content="index" />
    <meta name="googlebot" content="index" />
    <link href="https://fonts.googleapis.com/css2?family=Oswald:wght@400;500;700&display=swap" rel="stylesheet">
    <link href="/style.css?v=1.4" rel="stylesheet">
	<link rel="icon" href="favicon.ico" type="image/x-icon"/>
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
    echo json_encode($state)
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
<script>

</script>
<div class="logo">
    <div class="logoLeft">Korona</div>
    <div class="logoRight">Map</div>
</div>
<form id="donate" action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
    <input type="hidden" name="cmd" value="_s-xclick" />
    <input type="hidden" name="hosted_button_id" value="XRZQ6HRLU3DGE" />
    <input type="image" src="/donate_icon_50w.png" name="submit" title="Přispějte na chod webu pomocí služby Paypal" alt="Přispějte na chod webu pomocí služby Paypal" />
</form>
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
                <div class="infectedTitleText" id="infectedTitleText">
                    -
                </div>
                <select class="infectedTitleSelect" id="infectedTitleSelect">
                    <option id="infectedTitleSelectNow" value="0">
                        -
                    </option>
                    <option id="infectedTitleSelectTotal" value="1">
                        -
                    </option>
                </select>
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
        <div class="colorText" id="color01Text">No data</div>
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
        <div class="colorText">500 - 5000</div>
    </div>
    <div class="colorContainer">
        <div class="color" id="color06"></div>
        <div class="colorText">5000 < </div>
    </div>
</div>
<div id="map" class="map"></div>
    <div class="footer footer_left">© 2020
        <a href="https://www.linkedin.com/in/stepan-strba/">Štěpán Štrba</a>,
        design:
        <a href="https://duong.cz/">
            David Duong
        </a>
    </div>
    <div class="footer footer_right">
		<div class="contributions">
			<div id='infoButton' class='infoButton'>i</div>
			<div id='infoText' class='infoText' style='display: none'>
				<a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors <a href="https://carto.com/attributions">CARTO</a>
				<div id='infoTextClose' class='infoTextClose'>✖</div>
			</div>
			<script>
				let infoButton = document.getElementById('infoButton');
				let infoText = document.getElementById('infoText');
				let infoTextClose = document.getElementById('infoTextClose');
				let displayed = 'button';
				
				infoButton.addEventListener('click', function(event){
					if(displayed === 'button'){
						infoButton.style.display = 'none';
						infoText.style.display = 'inline-block';
						displayed = 'text';
					}
				});
				infoTextClose.addEventListener('click', function(event){
					if(displayed === 'text'){
						infoButton.style.display = 'block';
						infoText.style.display = 'none';
						displayed = 'button';
					}
				});
			</script>
		</div>&nbsp;&nbsp;
        <a href="https://github.com/stepanex/CoronavirusMap">Github</a>&nbsp;&nbsp;
        <a href="https://cs.wikipedia.org/wiki/Epidemie_koronaviru_SARS-CoV-2_v_%C4%8Cesku" id="footerRightUrl">Zdroj dat</a>
    </div>
    
</body>
<script src="/map.js?v=1.4.2"></script>
</html>