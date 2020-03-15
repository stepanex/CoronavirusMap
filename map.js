var color01legend = '#0f9246';
var color02legend = '#7dbb42';
var color03legend = '#ffbf00';
var color04legend = '#f68e1f';
var color05legend = '#ef4723';
var color06legend = '#bc2026';

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
var infectedCount = 0;
var deadCount = 0;
var recoveredCount = 0;
var featureOverlay = null;
function printInfo(feature) {
    let deadContainer = document.getElementById('deadContainer');
    let recoveredContainer = document.getElementById('recoveredContainer');
    if(regionLayer != null){
        if(feature !== undefined && feature.get('regionName') !== undefined){
            let name = feature.get('regionName');
            document.getElementById('infoPlaceName').innerText=name;
            document.getElementById('infectedCount').innerText=regionsCor[name];
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
        } else {
            document.getElementById('infoPlaceName').innerText='Česká Republika';
            document.getElementById('infectedCount').innerText=infectedCount;
            deadContainer.style.display='inline-block';
            document.getElementById('deadCount').innerText=deadCount;
            recoveredContainer.style.display='inline-block';
            document.getElementById('recoveredCount').innerText=recoveredCount;
            if(highlight)
                featureOverlay.getSource().removeFeature(highlight);
            highlight = null;
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
center = [15.4749126, 49.8037633];
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
        center: ol.proj.fromLonLat([15.4749126, 49.8037633]),
        zoom: zoom
    })
});
olms.apply(map, styleJson);

var regionsIndex = {
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
};
var regionsCor = null;
console.log('loading wiki');
    fetch('/wikiData/regions.php').then(regionCorCount => regionCorCount.json()).then(regionCorCount =>{
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
        printInfo('Česká Republika',infectedCount, deadCount, recoveredCount);

        function styleFunction(feature, resolution) {
            let name = feature.get('regionName');
            let regionCount=regionCorCount[name];
            let color = color_white;
            if (regionCount < 1) {
                color = color01;
            } else if (regionCount < 10) {
                color = color02;
            } else if (regionCount < 100) {
                color = color03;
            } else if (regionCount < 250) {
                color = color04;
            } else if (regionCount < 500) {
                color = color05;
            } else {
                color = color06;
            }
            return new ol.style.Style({
                fill: new ol.style.Fill({
                    color: [color[0], color[1], color[2], 0.6]
                }),
                stroke: new ol.style.Stroke({
                    color: [color[0], color[1], color[2], 1],
                    width: 1,
                    lineCap: 'round'
                })
            });
        }
        var regionsData = new ol.source.Vector({
            url: '/regionPolygons/area.geojson',
            format: new ol.format.GeoJSON()
        });
        regionLayer = new ol.layer.Vector({
            source: regionsData,
            style: styleFunction,
            zIndex: 10
        });
        map.addLayer(regionLayer);
        document.getElementById('colors').style.display='block';
        document.getElementById('info').style.display='block';
        printInfo(undefined);
    }).catch((error) => {
    console.error('Error:', error);
});
if(window.innerWidth > 550) {
    map.on('pointermove', function (e) {
        if (e.dragging) {
            return;
        }
        var feature = map.forEachFeatureAtPixel(e.pixel, function (feature, layer) {
            return feature;
        }, 12, function (layer) {
            return layer == regionLayer
        });
        printInfo(feature);
    });
}

map.on('click', function(e) {
    var feature = map.forEachFeatureAtPixel(e.pixel, function(feature, layer) {
        return feature;
    }, 12, function(layer) {
        return layer == regionLayer
    });
    printInfo(feature);
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