var countries=['CZ', 'SK'];
var countriesPopupTranslation={
    'CZ':{
        'SK':'Přejít na Slovenskou republiku',
        'SKurl':'http://koronamap.sk',
        'infected':'Nakažených',
        'infectedTitle':'počet nakažených',
        'deadTitle':'úmrtí',
        'recoveredTitle':'uzdravení',
        'legendTitle':'Počet nakažených lidí',
        'infoPlaceName':'Česká Republika',
        'footerRightTitle':'Zdroj dat',
        'footerRightUrl':'https://onemocneni-aktualne.mzcr.cz/covid-19'
    },
    'SK':{
        'CZ':'Prejsť na Českú Republiku',
        'CZurl':'http://koronamap.cz',
        'infected': 'Infikovaných',
        'infectedTitle':'počet infikovaných',
        'deadTitle':'úmrtí',
        'recoveredTitle':'uzdravení',
        'legendTitle':'Počet infikovaných ľudí',
        'infoPlaceName':'Slovenská republika',
        'footerRightTitle':'Zdroj údajov',
        'footerRightUrl':'https://en.wikipedia.org/wiki/2020_coronavirus_pandemic_in_Slovakia'
    }
};

var color01legend = '#0f9246';
var color02legend = '#7dbb42';
var color03legend = '#ffbf00';
var color04legend = '#f68e1f';
var color05legend = '#ef4723';
var color06legend = '#bc2026';
var popupUrl;

document.addEventListener("DOMContentLoaded", function(event) {
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
        popupVisible=false;
    }
}

function printInfo(feature, coordinate = null, from=null) {
    let deadContainer = document.getElementById('deadContainer');
    let recoveredContainer = document.getElementById('recoveredContainer');
    let stateName = undefined;
    let regionName = undefined;
    if(feature!==undefined){
        stateName = feature.get('stateName');
        regionName = feature.get('regionName');
    }
    if(from === 'click'){
        popupClose();
    }
    if(feature ===undefined || (feature!==undefined && (stateName!==undefined || regionName===undefined))){
        document.getElementById('infoPlaceName').innerText=countriesPopupTranslation[state]['infoPlaceName'];
        document.getElementById('infectedCount').innerText=infectedCount;
        deadContainer.style.display='inline-block';
        document.getElementById('deadCount').innerText=deadCount;
        recoveredContainer.style.display='inline-block';
        document.getElementById('recoveredCount').innerText=recoveredCount;
        if(highlight)
            featureOverlay.getSource().removeFeature(highlight);
        highlight = null;
    }

    if(from === 'click' && feature !== undefined && feature.get('stateName') !== undefined ){
        popupUrl = countriesPopupTranslation[state][stateName+'url'];
        popupTitle.innerHTML=countriesPopupTranslation[state][stateName];
        popupInfectedCount.innerHTML = stateCor[stateName];
        popupInfectedTitle.innerHTML = countriesPopupTranslation[state]['infected'];
        popupOverlay.setPosition(coordinate);
        popupVisible=true;
    }
    else if(regionLayer != null){
        if(feature !== undefined){
            if(feature.get('regionName') !== undefined){
                document.getElementById('infoPlaceName').innerText=regionName;
                document.getElementById('infectedCount').innerText=regionsCor[regionName];
                deadContainer.style.display='none';
                recoveredContainer.style.display='none';
                if (feature !== highlight) {
                    if (highlight) {
                        featureOverlay.getSource().removeFeature(highlight);
                    }
                    if (feature) {
                        featureOverlay.getSource().addFeature(feature);
                    }
                    highlight = feature;
                }
            }
        }
    }
}

var color01 = [15,146,70];
var color02 = [125,187,66];
var color03 = [255,191,0];
var color04 = [246,142,31];
var color05 = [239,71,35];
var color06 = [188,32,38];
var color_white = [255, 255, 255];


var styleJson = '/mapStyle.json';
var stateCenter = {
    'CZ':[15.4749126, 49.8037633],
    'SK':[19.6961, 48.6738]
    };

center = stateCenter[state];
let zoom = Math.log(window.innerWidth/5)/Math.log(2);
let czechHeight = Math.pow(2,zoom)*2.5;
let mapHeight = (window.innerHeight/100)*90;
if(mapHeight < czechHeight){
    zoom = Math.log(mapHeight/3)/Math.log(2);
}
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
    view: new ol.View({
        center: ol.proj.fromLonLat(center),
        zoom: zoom
    })
});
olms.apply(map, styleJson);

var regionsIndex = {
    'CZ':{
        'Praha':'01',
        'Královéhradecký kraj':'02',
        'Karlovarský kraj':'03',
        'Liberecký kraj':'04',
        'Moravskoslezský kraj':'05',
        'Olomoucký kraj':'06',
        'Pardubický kraj':'07',
        'Plzeňský kraj':'08',
        'Středočeský kraj':'09',
        'Jihočeský kraj':'10',
        'Jihomoravský kraj':'11',
        'Ústecký kraj':'12',
        'Vysočina':'13',
        'Zlínský kraj':'14'
    },
    'SK':{
        'Bratislava':'01',
        'Žilina':'02',
        'Košice':'03',
        'Trnava':'04',
        'Trenčín':'05',
        'Prešov':'06',
        'Banská Bystrica':'07',
        'Nitra':'08',
        'Martin':'09'
    }
};
var stateIndex = {
    'CZ': '01',
    'SK': '02'
};

function styleFunction(feature, resolution) {
    let name = null;
    let styleInfectedCount=0;

    let regionName = feature.get('regionName');
    let stateName = feature.get('stateName');
    if(regionName !== undefined){
        name = feature.get('regionName');
        styleInfectedCount=parseInt(regionsCor[name]);
    }
    else if(stateName !== undefined){
        name = feature.get('stateName');
        styleInfectedCount=parseInt(stateCor[name]);
    }
    let color = color_white;
    if (styleInfectedCount < 1) {
        color = color01;
    } else if (styleInfectedCount < 10) {
        color = color02;
    } else if (styleInfectedCount < 100) {
        color = color03;
    } else if (styleInfectedCount < 500) {
        color = color04;
    } else if (styleInfectedCount < 5000) {
        color = color05;
    } else if(styleInfectedCount >= 5000) {
        color = color06;
    }

    let strokeColor = [color[0], color[1], color[2], 1];
    if(stateName !== undefined){
        strokeColor = [0,0,0, 1];
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
var stateCor = {'SK':0};
console.log('loading wiki');
    fetch('/states/'+state+'/regionsData.php').then(regionCorCount => regionCorCount.json()).then(regionCorCount =>{
        console.log('loaded');
        regionsCor = regionCorCount;

        infectedCount = regionCorCount['infected'];
        deadCount = null;
        recoveredCount = null;
        if(regionCorCount['dead']!==null){
            deadCount = regionCorCount['dead'];
        }
        if(regionCorCount['recovered']!==null){
            recoveredCount = regionCorCount['recovered'];
        }
        printInfo(undefined);
        var regionsData = new ol.source.Vector({
            url: '/states/'+state+'/regions.geojson?v=1.0.2',
            format: new ol.format.GeoJSON()
        });
        regionLayer = new ol.layer.Vector({
            source: regionsData,
            style: styleFunction,
            zIndex: 10
        });
        map.addLayer(regionLayer);
        layers.push(regionLayer);
        document.getElementById('colors').style.display='block';
        document.getElementById('info').style.display='block';
        printInfo(undefined);
    }).catch((error) => {
    console.error('Error:', error);
});
fetch('/states/statesData.php').then(statesCorCount => statesCorCount.json()).then(statesCorCount =>{
    stateCor = statesCorCount;
    countries.forEach(country => {
        if(country !== state){
            console.log(country);
            let stateData = new ol.source.Vector({
                url: '/states/'+country+'/state.geojson?v=1.0.1',
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

document.getElementById('colorTitle').innerText=countriesPopupTranslation[state]['legendTitle'];
document.getElementById('infectedTitle').innerText=countriesPopupTranslation[state]['infectedTitle'];
document.getElementById('deadTitle').innerText=countriesPopupTranslation[state]['deadTitle'];
document.getElementById('recoveredTitle').innerText=countriesPopupTranslation[state]['recoveredTitle'];
document.getElementById('infoPlaceName').innerText=countriesPopupTranslation[state]['infoPlaceName'];

let footerRight = document.getElementById('footerRightUrl');
footerRight.href = countriesPopupTranslation[state]['footerRightUrl'];
footerRight.innerText = countriesPopupTranslation[state]['footerRightTitle'];

if(window.innerWidth > 550) {
    map.on('pointermove', function (e) {
        if (e.dragging) {
            popupClose();
            return;
        }
        var feature = map.forEachFeatureAtPixel(e.pixel, function (feature, layer) {
            return feature;
        }, {hitTolerance: 1, layerFilter: function (layer) {
            return layer === regionLayer
        }});
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

popupContainer.onclick = function(){
    window.location.href = popupUrl;
};
popupCloser.onclick = function() {
    popupOverlay.setPosition(undefined);
    popupCloser.blur();
    return false;
};

map.on('click', function(e) {
    for(let i = 0; i<layers.length; i++){
        var feature = map.forEachFeatureAtPixel(e.pixel, function(feature, layer) {
            return feature;
        }, {hitTolerance: 1, layerFilter: function (layer) {
                return layer === layers[i]
            }});
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
        color: [200,200,200,0.5]
    })
});

featureOverlay = new ol.layer.Vector({
    source: new ol.source.Vector(),
    map: map,
    style: function(feature) {
        return highlightStyle;
    },
    zIndex: 10
});