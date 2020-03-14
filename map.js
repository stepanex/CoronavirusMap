
var map;
var infowindow;
var color_white = '#ffffff';

var color01 = '#0f9246';
var color02 = '#7dbb42';
var color03 = '#ffbf00';
var color04 = '#f68e1f';
var color05 = '#ef4723';
var color06 = '#bc2026';
var infectedCount = '0';

document.addEventListener("DOMContentLoaded", function(event) {
    let color01Elem = document.getElementById('color01');
    let color02Elem = document.getElementById('color02');
    let color03Elem = document.getElementById('color03');
    let color04Elem = document.getElementById('color04');
    let color05Elem = document.getElementById('color05');
    let color06Elem = document.getElementById('color06');
    color01Elem.style.backgroundColor = color01;
    color02Elem.style.backgroundColor = color02;
    color03Elem.style.backgroundColor = color03;
    color04Elem.style.backgroundColor = color04;
    color05Elem.style.backgroundColor = color05;
    color06Elem.style.backgroundColor = color06;
});

function printInfo(regionName, infected, dead=null, recovered=null) {
    document.getElementById('infoPlaceName').innerText=regionName;
    document.getElementById('infectedCount').innerText=infected;

    let deadContainer = document.getElementById('deadContainer');
    let recoveredContainer = document.getElementById('recoveredContainer');

    if(dead==null){
        deadContainer.style.display='none';
    } else{
        deadContainer.style.display='inline-block';
        document.getElementById('deadCount').innerText=dead;
    }
    if(recovered==null){
        recoveredContainer.style.display='none';
    } else{
        recoveredContainer.style.display='inline-block';
        document.getElementById('recoveredCount').innerText=recovered;
    }
}
function setMapListeners(infectedCount, deadCount, recoveredCount){
    map.addListener('mousemove', function () {
        printInfo('Česká Republika',infectedCount, deadCount, recoveredCount);
    });
    map.addListener('click', function () {
        printInfo('Česká Republika',infectedCount, deadCount, recoveredCount);
    });
    printInfo('Česká Republika',infectedCount, deadCount, recoveredCount);
    document.getElementById('info').style.display='block';
}

function initMap() {

    var sydney = new google.maps.LatLng(49.8037633, 15.4749126);

    infowindow = new google.maps.InfoWindow();
    let zoom = Math.log(window.innerWidth/5)/Math.log(2);
    let czechHeight = Math.pow(2,zoom)*2.5;
    let mapHeight = (window.innerHeight/100)*90;
    if(mapHeight < czechHeight){
        zoom = Math.log(mapHeight/3)/Math.log(2);
    }

    map = new google.maps.Map(
        document.getElementById('map'), {center: sydney,
            zoom: zoom,
            mapTypeControl : false,
            fullscreenControl : false,
            streetViewControl: false,
            styles: [
                {
                    "elementType": "geometry",
                    "stylers": [
                        {
                            "color": "#212121"
                        }
                    ]
                },
                {
                    "elementType": "labels.text.fill",
                    "stylers": [
                        {
                            "color": "#757575"
                        }
                    ]
                },
                {
                    "elementType": "labels.text.stroke",
                    "stylers": [
                        {
                            "color": "#212121"
                        }
                    ]
                },
                {
                    "featureType": "administrative",
                    "elementType": "geometry",
                    "stylers": [
                        {
                            "color": "#757575"
                        }
                    ]
                },
                {
                    "featureType": "administrative.country",
                    "elementType": "labels.text.fill",
                    "stylers": [
                        {
                            "color": "#9e9e9e"
                        }
                    ]
                },
                {
                    "featureType": "administrative.locality",
                    "elementType": "labels.text.fill",
                    "stylers": [
                        {
                            "color": "#bdbdbd"
                        }
                    ]
                },
                {
                    "featureType": "road",
                    "elementType": "geometry.fill",
                    "stylers": [
                        {
                            "color": "#2c2c2c"
                        }
                    ]
                },
                {
                    "featureType": "road",
                    "elementType": "labels.text.fill",
                    "stylers": [
                        {
                            "color": "#8a8a8a"
                        }
                    ]
                },
                {
                    "featureType": "road.arterial",
                    "stylers": [
                        {
                            "visibility": "off"
                        }
                    ]
                },
                {
                    "featureType": "road.arterial",
                    "elementType": "geometry",
                    "stylers": [
                        {
                            "color": "#373737"
                        }
                    ]
                },
                {
                    "featureType": "road.highway",
                    "elementType": "geometry",
                    "stylers": [
                        {
                            "color": "#3c3c3c"
                        }
                    ]
                },
                {
                    "featureType": "road.highway",
                    "elementType": "labels",
                    "stylers": [
                        {
                            "visibility": "off"
                        }
                    ]
                },
                {
                    "featureType": "road.highway.controlled_access",
                    "elementType": "geometry",
                    "stylers": [
                        {
                            "color": "#4e4e4e"
                        }
                    ]
                },
                {
                    "featureType": "road.local",
                    "stylers": [
                        {
                            "visibility": "off"
                        }
                    ]
                },
                {
                    "featureType": "road.local",
                    "elementType": "labels.text.fill",
                    "stylers": [
                        {
                            "color": "#616161"
                        }
                    ]
                },
                {
                    "featureType": "transit",
                    "elementType": "labels.text.fill",
                    "stylers": [
                        {
                            "color": "#757575"
                        }
                    ]
                },
                {
                    "featureType": "water",
                    "elementType": "geometry",
                    "stylers": [
                        {
                            "color": "#000000"
                        }
                    ]
                },
                {
                    "featureType": "water",
                    "elementType": "labels.text.fill",
                    "stylers": [
                        {
                            "color": "#3d3d3d"
                        }
                    ]
                },
                {
                    "featureType": 'landscape',
                    "elementType": "labels",
                    "stylers": [
                        {
                            "visibility": "off"
                        }
                    ]
                },
                {
                    "featureType": 'poi',
                    "elementType": "labels",
                    "stylers": [
                        {
                            "visibility": "off"
                        }
                    ]
                },
                {
                    "featureType": 'transit',
                    "elementType": "labels",
                    "stylers": [
                        {
                            "visibility": "off"
                        }
                    ]
                }
            ]});

    var legend = document.getElementById('colors');
    var anchor = google.maps.ControlPosition.RIGHT_BOTTOM;
    if(window.innerWidth < 550){
        anchor = google.maps.ControlPosition.RIGHT_TOP;
    }
    map.controls[anchor].push(legend);
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
    console.log('Loading data');
    let loaded = false;
    let infectedCount = 0;
    fetch('https://api.apify.com/v2/key-value-stores/K373S4uCFR9W1K8ei/records/LATEST?disableRedirect=true')
        .then(data => data.json()).then(data => {
        console.log('Loaded data');
        let regionData = data['infectedByRegion'];
        infectedCount = data['infected'];
        let deadCount = 0;
        let recoveredCount = 0;

        if(data['dead'] !== undefined && data['dead'] !== null){
            deadCount = data['dead'];
        }
        if(data['recovered'] !== undefined && data['recovered'] !== null){
            recoveredCount = data['recovered'];
        }
        if(loaded){
            setMapListeners(infectedCount, recoveredCount, deadCount);
        } else {
            loaded = true;
        }

        var prague = [];
        regionData.forEach(function (item) {
            let regionName = item['region'];
            let regionInfected = item['value'];
            let regionDead = item['dead'];
            let regionRecovered = item['recovered'];

            console.log('Loading region polygon');
            fetch('./regionPolygons/area'+regionsIndex[regionName]+'.txt').then(data => data.json()).then(regionPolygonData =>{
                console.log('Loaded region polygon');

                let color = color_white;
                if(regionInfected<1){
                    color = color01;
                } else if(regionInfected < 10){
                    color = color02;
                } else if(regionInfected < 100){
                    color = color03;
                } else if(regionInfected < 250){
                    color = color04;
                } else if(regionInfected < 500){
                    color = color05;
                } else {
                    color = color06;
                }

                if(regionName === 'Praha'){
                    prague = regionPolygonData;
                }
                else if (regionName === 'Středočeský kraj') {
                    regionPolygonData = [regionPolygonData, prague.reverse()];
                }

                var regionPolygon = new google.maps.Polygon({
                    paths: regionPolygonData,
                    strokeColor: color,
                    strokeOpacity: 0.8,
                    strokeWeight: 3,
                    fillColor: color,
                    fillOpacity: 0.5
                });
                regionPolygon.setMap(map);
                regionPolygon.addListener('mousemove', function () {
                    printInfo(regionName,regionInfected, regionDead, regionRecovered);
                });
                regionPolygon.addListener('click', function () {
                    printInfo(regionName,regionInfected, regionDead, regionRecovered);
                });
            });
            // console.log(regionName+":"+regionInfected);
        });
        document.getElementById('colors').style.display='block';
    }).catch((error) => {
        console.error('Error:', error);
    });

    console.log('Loading wiki data');
    fetch('/wikiData/regions.php').then(wikiInfoData => wikiInfoData.json()).then(wikiInfoData =>{
        console.log('Loaded wiki data');
        let deadCount = 0;
        let recoveredCount = 0;
        if(wikiInfoData['dead']!==null){
            deadCount = wikiInfoData['dead'];
        }
        if(wikiInfoData['recovered']!==null){
            recoveredCount = wikiInfoData['recovered'];
        }
        if(loaded){
            setMapListeners(infectedCount, recoveredCount, deadCount);
        } else {
            loaded = true;
        }
    }).catch((error) => {
        console.error('Error:', error);
    });
}