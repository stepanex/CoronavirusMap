var countries = ['CZ', 'SK'];
var countriesPopupTranslation = {
    'CZ': {
        'SK': 'Přejít na Slovenskou republiku',
        'SKurl': 'https://koronamap.cz/?state=SK',
        'infected': 'Nakažených',
        'infectedTitle': 'počet nakažených',
        'infectedTotal': 'celkem',
        'infectedNow': 'aktuálně',
        'deadTitle': 'úmrtí',
        'recoveredTitle': 'uzdravení',
        'legendTitle': 'Počet nakažených lidí',
        'legendNoData': 'Chybí data',
        'infoPlaceName': 'Česká Republika',
        'footerRightTitle': 'Zdroj dat',
        'footerRightUrl': 'https://github.com/apify/covid-19/tree/master/czechia',
        'metInfectedRegion': 'Kraj:',
        'metPeople': 'Potkaných lidí:',
        'reproductionNumber': 'Číslo R:',
        'reproductionNumberHelp': 'Kolik lidí se průměrně nakazí od nakaženého',
        'metInfectedChance': 'Pravděpodobnost potkání alespoň jednoho nakaženého',
        'metInfectedButton': 'Pravděpodobnost<br/>potkání nakaženého',
        'metInfectedChanceHelp': 'Pravděpodobnost že je jeden nakažený = Pnak = (počet nakažených * (1 + číslo R)) / počet zdravých\nPravděpodobnost že je jeden zdravý = Pzdr = 1 - Pnak\nPravděpodobnost že je n lidí zdravých = Pnzdr = Pzdr^n\nPravděpodobnost že z n lidí je alespoň jeden nakažený = 1 - Pnzdr'
    },
    'SK': {
        'CZ': 'Prejsť na Českú Republiku',
        'CZurl': 'https://koronamap.cz',
        'infected': 'Infikovaných',
        'infectedTitle': 'počet infikovaných',
        'infectedTotal': 'celkom',
        'infectedNow': 'aktuálne',
        'deadTitle': 'úmrtí',
        'recoveredTitle': 'uzdravení',
        'legendTitle': 'Počet infikovaných ľudí',
        'legendNoData': 'Chýbajú dáta',
        'infoPlaceName': 'Slovenská republika',
        'footerRightTitle': 'Zdroj údajov',
        'footerRightUrl': 'https://github.com/apify/covid-19/tree/master/slovakia',
        'metInfectedRegion': 'Kraj:',
        'metPeople': 'Stretnutých ľudí:',
        'reproductionNumber': 'Číslo R:',
        'reproductionNumberHelp': 'Koľko ľudí sa priemerne nakazí od nakazeného',
        'metInfectedChance': 'Pravdebodobnosť stretnutie aspoň jedného nakazeného',
        'metInfectedButton': 'Pravdebodobnosť<br/>stretnutie nakazeného',
        'metInfectedChanceHelp': 'Pravdepodobnosť že je jeden nakazený = Pnak = (počet nakazených * (1 + číslo R)) / počet zdravých\nPravdepodobnosť že je jeden zdravý = Pzdr = 1 - Pnak\nPravdepodobnosť že je n ľudí zdravých = Pnzdr = Pzdr^n\nPravdepodobnosť že z n ľudí je aspoň jeden nakazený = 1 - Pnzdr'
    }
};
var regionsIndex = {
    'CZ': {
        'Jihočeský kraj': '10',
        'Jihomoravský kraj': '11',
        'Karlovarský kraj': '03',
        'Kraj Vysočina': '13',
        'Královéhradecký kraj': '02',
        'Liberecký kraj': '04',
        'Moravskoslezský kraj': '05',
        'Olomoucký kraj': '06',
        'Pardubický kraj': '07',
        'Plzeňský kraj': '08',
        'Praha': '01',
        'Středočeský kraj': '09',
        'Ústecký kraj': '12',
        'Zlínský kraj': '14'
    },
    'SK': {
        'Banskobystrický kraj': '07',
        'Bratislavský kraj': '01',
        'Košický kraj': '03',
        'Nitriansky kraj': '08',
        'Prešovský kraj': '06',
        'Trnavský kraj': '04',
        'Trenčiansky kraj': '05',
        'Žilinský kraj': '02'
    }
};
var stateIndex = {
    'CZ': '01',
    'SK': '02'
};
var regionsPopulation = {
    'CZ': {
        'Praha': 1309000,
        'Královéhradecký kraj': 551000,
        'Karlovarský kraj': 295000,
        'Liberecký kraj': 442000,
        'Moravskoslezský kraj': 1203000,
        'Olomoucký kraj': 633000,
        'Pardubický kraj': 520000,
        'Plzeňský kraj': 585000,
        'Středočeský kraj': 1369000,
        'Jihočeský kraj': 642000,
        'Jihomoravský kraj': 1189000,
        'Ústecký kraj': 821000,
        'Kraj Vysočina': 509000,
        'Zlínský kraj': 583000
    },
    'SK': {
        'Bratislavský kraj': 669592,
        'Žilinský kraj': 691509,
        'Košický kraj': 801460,
        'Trnavský kraj': 564917,
        'Trenčiansky kraj': 584569,
        'Prešovský kraj': 826244,
        'Banskobystrický kraj': 645276,
        'Nitriansky kraj': 674306
    }
}

var color01legend = '#9d9d9d';
var color02legend = '#7dbb42';
var color03legend = '#ffbf00';
var color04legend = '#f68e1f';
var color05legend = '#ef4723';
var color06legend = '#bc2026';
var popupUrl;

document.addEventListener("DOMContentLoaded", function(event){
    let color01Elem = document.getElementById('color01');
    let color02Elem = document.getElementById('color02');
    let color03Elem = document.getElementById('color03');
    let color04Elem = document.getElementById('color04');
    let color05Elem = document.getElementById('color05');
    let color06Elem = document.getElementById('color06');
    color01Elem.style.backgroundColor = color01legend;
    color02Elem.style.backgroundColor = color02legend;
    color03Elem.style.backgroundColor = color03legend;
    color04Elem.style.backgroundColor = color04legend;
    color05Elem.style.backgroundColor = color05legend;
    color06Elem.style.backgroundColor = color06legend;
});
document.getElementById('infectedTitleSelect').addEventListener("change", function(e){
    showInfectedTotal = document.getElementById('infectedTitleSelect').value === "1";
    for(let i = 0; i < layers.length; i++){
        layers[i].setStyle(layers[i].getStyle());
    }
    printInfo(undefined);
});

document.getElementById('metInfectedChanceContainer').addEventListener("click", function(e){
    alert(countriesPopupTranslation[state]['metInfectedChanceHelp']);
});
document.getElementById('reproductionNumberTitle').addEventListener("click", function(e){
    alert(countriesPopupTranslation[state]['reproductionNumberHelp']);
});

var reproductionNumberElement = document.getElementById('reproductionNumber');
var metPeopleElement = document.getElementById('metPeople');
var metInfected = document.getElementById('metInfected');
var metInfectedButton = document.getElementById('metInfectedButton');
var metInfectedChance = document.getElementById('metInfectedChance');
var metInfectedContainer = document.getElementById('metInfectedContainer');
var metInfectedRegion = document.getElementById('metInfectedRegion');
Object.keys(regionsIndex[state]).forEach(key => {
    let option = document.createElement('option');
    option.innerText = key;
    option.value = key;
    metInfectedRegion.appendChild(option);
})
let selectedMetInfectedRegion = localStorage.getItem('selectedMetInfectedRegion');
if(selectedMetInfectedRegion !== null && Object.keys(regionsIndex[state]).includes(selectedMetInfectedRegion)){
    metInfectedRegion.value = selectedMetInfectedRegion;
}

function countMetInfectedChance(event){
    let chosenRegion = metInfectedRegion.value;
    let regionPop = regionsPopulation['CZ'][chosenRegion];
    let reproductionNumber = parseFloat(reproductionNumberElement.value);
    let infectedPop = (infectedRegion[chosenRegion] - recoveredRegion[chosenRegion] - deadRegion[chosenRegion]) * (1 + reproductionNumber);
    let metPeople = metPeopleElement.value;
    let infectedProbability = infectedPop / regionPop;
    let healthyProbability = 1 - infectedProbability;
    metInfectedChance.innerText = ((1 - Math.pow(healthyProbability, metPeople)) * 100).toFixed(1) + '%';
}

function handleChangeRegion(event){
    localStorage.setItem('selectedMetInfectedRegion', metInfectedRegion.value);
    countMetInfectedChance();
}

metPeopleElement.addEventListener('input', countMetInfectedChance);
reproductionNumberElement.addEventListener('input', countMetInfectedChance);
metInfectedRegion.addEventListener('change', handleChangeRegion);

function displayMetInfected(){
    metInfectedContainer.style.display = 'block';
    metInfectedButton.style.display = 'block';
    metInfectedButton.innerText = '✖';
    localStorage.setItem('metInfectedVisible', 'true');
}

metInfectedButton.addEventListener('click', (event) => {
    if(metInfectedContainer.style.display !== 'block'){
        displayMetInfected();
    }
    else {
        metInfectedContainer.style.display = 'none';
        metInfectedButton.style.display = 'contents';
        metInfectedButton.innerHTML = countriesPopupTranslation[state]['metInfectedButton'];
        localStorage.setItem('metInfectedVisible', 'false');
    }
});
var metInfectedVisible = localStorage.getItem('metInfectedVisible');
if(metInfectedVisible !== null && metInfectedVisible === 'true'){
    displayMetInfected();
}
else {
    metInfectedButton.innerHTML = countriesPopupTranslation[state]['metInfectedButton'];
}

var showInfectedTotal = false;
var highlight;
var regionLayer = null;
var layers = [];
var infectedCount = 0;
var deadCount = 0;
var recoveredCount = 0;
var featureOverlay = null;

function popupClose(){
    if(popupVisible){
        popupOverlay.setPosition(undefined);
        popupCloser.blur();
        popupVisible = false;
    }
}

function printInfo(feature, coordinate = null, from = null){
    let deadContainer = document.getElementById('deadContainer');
    let recoveredContainer = document.getElementById('recoveredContainer');
    let stateName = undefined;
    let regionName = undefined;
    if(feature !== undefined){
        stateName = feature.get('stateName');
        regionName = feature.get('regionName');
    }
    if(from === 'click'){
        popupClose();
    }
    if(feature === undefined || (feature !== undefined && (stateName !== undefined || regionName === undefined))){
        document.getElementById('infoPlaceName').innerText = countriesPopupTranslation[state]['infoPlaceName'];
        if(showInfectedTotal){
            document.getElementById('infectedCount').innerText = infectedCount;
        }
        else {
            document.getElementById('infectedCount').innerText = (infectedCount - recoveredCount - deadCount).toString();
        }
        document.getElementById('recoveredCount').innerText = recoveredCount;
        document.getElementById('deadCount').innerText = deadCount;
        let display = 'inline-block';
        if(window.innerWidth < 450){
            display = 'block';
        }
        document.getElementById('infectedContainer').style.display = display;
        deadContainer.style.display = display;
        recoveredContainer.style.display = display;
        if(highlight){
            featureOverlay.getSource().removeFeature(highlight);
        }
        highlight = null;
    }

    if(from === 'click' && feature !== undefined && feature.get('stateName') !== undefined){
        clickedOnce = false;
        popupUrl = countriesPopupTranslation[state][stateName + 'url'];
        popupTitle.innerHTML = countriesPopupTranslation[state][stateName];
        if(showInfectedTotal || stateCor[stateName] === undefined || stateCor[stateName]['recovered'] === undefined || stateCor[stateName] === undefined || stateCor[stateName]['dead'] === undefined){
			if(stateCor[stateName] === undefined || stateCor[stateName]['infected'] === undefined){
				popupInfectedCount.innerHTML = countriesPopupTranslation[state]['legendNoData'];
				popupInfectedTitle.innerHTML = '';
			} else {
				popupInfectedCount.innerHTML = stateCor[stateName]['infected'];
				popupInfectedTitle.innerHTML = countriesPopupTranslation[state]['infected'] + ' ' + countriesPopupTranslation[state]['infectedTotal'];
			}
        }
        else {
            popupInfectedCount.innerHTML = (stateCor[stateName]['infected'] - stateCor[stateName]['recovered'] - stateCor[stateName]['dead']).toString();
            popupInfectedTitle.innerHTML = countriesPopupTranslation[state]['infected'] + ' ' + countriesPopupTranslation[state]['infectedNow'];
        }
        popupOverlay.setPosition(coordinate);
        popupVisible = true;
    }
    else if(regionLayer != null){
        if(feature !== undefined){
            if(feature.get('regionName') !== undefined){
                document.getElementById('infoPlaceName').innerText = regionName;
                if(infectedRegion !== null && infectedRegion[regionName] !== null){
                    if(showInfectedTotal){
                        document.getElementById('infectedCount').innerText = infectedRegion[regionName];
                    }
                    else {
                        let newInfectedCount = infectedRegion[regionName] - recoveredRegion[regionName];
                        if(deadRegion !== null && deadRegion[regionName] !== null){
                            newInfectedCount -= deadRegion[regionName];
                        }
                        document.getElementById('infectedCount').innerText = (newInfectedCount).toString();
                    }
                }
                else {
                    document.getElementById('infectedContainer').style.display = 'none';
                }
                if(deadRegion !== null && deadRegion[regionName] !== null){
                    document.getElementById('deadCount').innerText = deadRegion[regionName];
                }
                else {
                    deadContainer.style.display = 'none';
                }
                if(recoveredRegion !== null && recoveredRegion[regionName] !== null){
                    document.getElementById('recoveredCount').innerText = recoveredRegion[regionName];
                }
                else {
                    recoveredContainer.style.display = 'none';
                }
                if(feature !== highlight){
                    if(highlight){
                        featureOverlay.getSource().removeFeature(highlight);
                    }
                    if(feature){
                        featureOverlay.getSource().addFeature(feature);
                    }
                    highlight = feature;
                }
            }
        }
    }
}

var color_gray = [157, 157, 157];
var color02 = [125, 187, 66];
var color03 = [255, 191, 0];
var color04 = [246, 142, 31];
var color05 = [239, 71, 35];
var color06 = [188, 32, 38];

var stateCenter = {
    'CZ': [15.4749126, 49.8037633],
    'SK': [19.6961, 48.6738]
};

center = stateCenter[state];
let zoom = Math.log(window.innerWidth / 5) / Math.log(2);
let czechHeight = Math.pow(2, zoom) * 2.5;
let mapHeight = (window.innerHeight / 100) * 90;
if(mapHeight < czechHeight){
    zoom = Math.log(mapHeight / 3) / Math.log(2);
}

var carto = new ol.layer.Tile({
    source: new ol.source.XYZ({
        url: 'https://{1-4}.basemaps.cartocdn.com/rastertiles/dark_nolabels/{z}/{x}/{y}.png',
    })
});
var cartoLabels = new ol.layer.Tile({
    source: new ol.source.XYZ({
        url: 'https://{1-4}.basemaps.cartocdn.com/rastertiles/dark_only_labels/{z}/{x}/{y}@3x.png',
        tilePixelRatio: 3
    }),
    zIndex: 20
});
var map = new ol.Map({
    target: 'map',
    interactions: ol.interaction.defaults({
        doubleClickZoom: true,
        dragAndDrop: false,
        dragPan: true,
        keyboardPan: true,
        keyboardZoom: true,
        mouseWheelZoom: true,
        pointer: true,
        select: false,
        rotate: false
    }),
    controls: ol.control.defaults({
        zoom: true,
        attribution: false,
        rotate: false
    }),
    layers: [
        carto,
        cartoLabels
    ],
    view: new ol.View({
        center: ol.proj.fromLonLat(center),
        zoom: zoom
    })
});

function styleFunction(feature, resolution){
    let name = null;
    let styleInfectedCount = 0;

    let regionName = feature.get('regionName');
    let stateName = feature.get('stateName');
    if(regionName !== undefined){
        name = feature.get('regionName');
        if(infectedRegion[name] == undefined){
            styleInfectedCount = null;
        }
        else if(showInfectedTotal){
            styleInfectedCount = infectedRegion[name];
        }
        else {
            styleInfectedCount = infectedRegion[name] - recoveredRegion[name] - deadRegion[name];
        }
    }
    else if(stateName !== undefined){
        name = feature.get('stateName');
        if(stateCor[name] == undefined || stateCor[name]['infected'] == undefined){
            styleInfectedCount = null;
        }
        else if(showInfectedTotal || stateCor[name]['recovered'] == undefined || stateCor[name]['dead'] == undefined){
            styleInfectedCount = stateCor[name]['infected'];
        }
        else {
            styleInfectedCount = stateCor[name]['infected'] - stateCor[name]['recovered'] - stateCor[name]['dead'];
        }
    }
    let color = color_gray;
    if(styleInfectedCount == undefined){
        color = color_gray;
    }
    else if(styleInfectedCount < 10){
        color = color02;
    }
    else if(styleInfectedCount < 100){
        color = color03;
    }
    else if(styleInfectedCount < 500){
        color = color04;
    }
    else if(styleInfectedCount < 5000){
        color = color05;
    }
    else if(styleInfectedCount >= 5000){
        color = color06;
    }

    let strokeColor = [color[0], color[1], color[2], 1];
    if(stateName !== undefined){
        strokeColor = [0, 0, 0, 1];
    }

    return new ol.style.Style({
        fill: new ol.style.Fill({
            color: [color[0], color[1], color[2], 0.6]
        }),
        stroke: new ol.style.Stroke({
            color: strokeColor,
            width: 1,
            lineCap: 'round'
        })
    });
}

var regionsCor = null;
var infectedRegion = null;
var deadRegion = null;
var recoveredRegion = null;
var stateCor = {'SK': 0};
var reproductionNumber = null;
console.log('loading data');
fetch('/states/' + state + '/regionsData.php').then(regionCorCount => regionCorCount.json()).then(regionCorCount => {
    console.log('loaded');
    regionsCor = regionCorCount;
    infectedRegion = regionsCor['infectedRegion'];

    if('deadRegion' in regionsCor){
        console.log('deadRegion exists');
        deadRegion = regionsCor['deadRegion'];
    }

    if('recoveredRegion' in regionsCor){
        console.log('recoveredRegion exists');
        recoveredRegion = regionsCor['recoveredRegion'];
    }

    if('deadRegion' in regionsCor && 'recoveredRegion' in regionsCor){
        metInfected.style.display = 'block';
    }

    if('reproduction' in regionsCor){
        reproductionNumber = regionsCor['reproduction'];
        reproductionNumberElement.value = parseFloat(reproductionNumber);
    }

    infectedCount = regionCorCount['infected'];
    deadCount = null;
    recoveredCount = null;
    if(regionCorCount['dead'] !== null){
        deadCount = regionCorCount['dead'];
    }
    if(regionCorCount['recovered'] !== null){
        recoveredCount = regionCorCount['recovered'];
    }
    if(recoveredRegion === null || infectedRegion.length !== recoveredRegion.length){
        document.getElementById('infectedTitleSelect').removeChild(document.getElementById('infectedTitleSelectNow'));
        showInfectedTotal = true;
    }
    printInfo(undefined);
    var regionsData = new ol.source.Vector({
        url: '/states/' + state + '/regions.geojson?v=1.0.3',
        format: new ol.format.GeoJSON()
    });
    regionLayer = new ol.layer.Vector({
        source: regionsData,
        style: styleFunction,
        zIndex: 10
    });
    map.addLayer(regionLayer);
    layers.push(regionLayer);
    document.getElementById('colors').style.display = 'block';
    document.getElementById('info').style.display = 'block';
    printInfo(undefined);
}).catch((error) => {
    console.error('Error:', error.message);
});
fetch('/states/statesData.php').then(statesCorCount => statesCorCount.json()).then(statesCorCount => {
    stateCor = statesCorCount;
    countries.forEach(country => {
        if(country !== state){
            console.log(country);
            let stateData = new ol.source.Vector({
                url: '/states/' + country + '/state.geojson?v=1.0.1',
                format: new ol.format.GeoJSON()
            });
            let stateLayer = new ol.layer.Vector({
                source: stateData,
                style: styleFunction,
                zIndex: 10
            });
            map.addLayer(stateLayer);
            layers.push(stateLayer);
        }
    });
});

document.getElementById('colorTitle').innerText = countriesPopupTranslation[state]['legendTitle'];
document.getElementById('color01Text').innerText = countriesPopupTranslation[state]['legendNoData'];
document.getElementById('infectedTitleText').innerText = countriesPopupTranslation[state]['infectedTitle'];
document.getElementById('infectedTitleSelectTotal').innerText = countriesPopupTranslation[state]['infectedTotal'];
document.getElementById('infectedTitleSelectNow').innerText = countriesPopupTranslation[state]['infectedNow'];
document.getElementById('deadTitle').innerText = countriesPopupTranslation[state]['deadTitle'];
document.getElementById('recoveredTitle').innerText = countriesPopupTranslation[state]['recoveredTitle'];
document.getElementById('infoPlaceName').innerText = countriesPopupTranslation[state]['infoPlaceName'];
document.getElementById('metInfectedRegionText').innerText = countriesPopupTranslation[state]['metInfectedRegion'];
document.getElementById('metPeopleText').innerText = countriesPopupTranslation[state]['metPeople'];
document.getElementById('reproductionNumberText').innerText = countriesPopupTranslation[state]['reproductionNumber'];
document.getElementById('reproductionNumberTitle').title = countriesPopupTranslation[state]['reproductionNumberHelp'];
document.getElementById('metInfectedChanceText').innerText = countriesPopupTranslation[state]['metInfectedChance'];
document.getElementById('metInfectedChanceContainer').title = countriesPopupTranslation[state]['metInfectedChanceHelp'];
document.getElementById('metInfectedChance').title = countriesPopupTranslation[state]['metInfectedChanceHelp'];

let footerRight = document.getElementById('footerRightUrl');
footerRight.href = countriesPopupTranslation[state]['footerRightUrl'];
footerRight.innerText = countriesPopupTranslation[state]['footerRightTitle'];

if(window.innerWidth > 550){
    map.on('pointermove', function(e){
        if(e.dragging){
            popupClose();
            return;
        }
        var feature = map.forEachFeatureAtPixel(e.pixel, function(feature, layer){
            return feature;
        }, {
            hitTolerance: 1, layerFilter: function(layer){
                return layer === regionLayer
            }
        });
        printInfo(feature);
    });
}

var popupContainer = document.getElementById('popup');
var popupCloser = document.getElementById('popup-closer');
var popupTitle = document.getElementById('popupTitle');
var popupInfectedCount = document.getElementById('popupInfectedCount');
var popupInfectedTitle = document.getElementById('popupInfectedTitle');
var popupVisible = false;
var popupOverlay = new ol.Overlay({
    element: popupContainer,
    autoPan: true,
    autoPanAnimation: {
        duration: 250
    }
});
map.addOverlay(popupOverlay);

var clickedOnce = false;
popupContainer.onclick = function(){
    if(!clickedOnce && window.innerWidth <= 550){
        clickedOnce = true;
    }
    else {
        window.top.location.href = popupUrl;
    }
};
popupCloser.onclick = function(){
    popupOverlay.setPosition(undefined);
    popupCloser.blur();
    return false;
};

map.on('click', function(e){
    for(let i = 0; i < layers.length; i++){
        var feature = map.forEachFeatureAtPixel(e.pixel, function(feature, layer){
            return feature;
        }, {
            hitTolerance: 1, layerFilter: function(layer){
                return layer === layers[i]
            }
        });
        if(feature !== undefined){
            break;
        }
    }
    printInfo(feature, e.coordinate, 'click');
});

var highlightStyle = new ol.style.Style({
    stroke: new ol.style.Stroke({
        color: '#ffffff',
        width: 1
    }),
    fill: new ol.style.Fill({
        color: [200, 200, 200, 0.5]
    })
});

featureOverlay = new ol.layer.Vector({
    source: new ol.source.Vector(),
    map: map,
    style: function(feature){
        return highlightStyle;
    },
    zIndex: 10
});