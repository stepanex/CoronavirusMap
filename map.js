
var map;
var service;
var infowindow;
var color_white = '#ffffff';

var markerHover = false;

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

function printInfo(regionName, infected, dead=null, recovered=null, fromMarker=false) {
    if((markerHover && fromMarker) || !markerHover){
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
}
function createMarkerLatlng(latlng, name, text) {
    var marker = new google.maps.Marker({
        map: map,
        position: latlng
    });

    marker.addListener('mouseover', function() {
        markerHover = true;
        printInfo(name,text,null,null, true);
    });
    marker.addListener('mouseout', function() {
        markerHover = false;
    });
    marker.addListener('click', function() {
        printInfo(name,text,null,null, true);
    });
}
function createMarkerGplace(place, text) {
    var marker = new google.maps.Marker({
        map: map,
        position: place.geometry.location
    });

    marker.addListener('mouseover', function() {
        markerHover = true;
        printInfo(name,text,null,null, true);
    });
    marker.addListener('mouseout', function() {
        markerHover = false;
    });
    marker.addListener('click', function() {
        printInfo(name,text,null,null, true);
    });
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

    service = new google.maps.places.PlacesService(map);
    var placesToPrint = [];
    var regionsCor = [['Praha',0],['Královéhradecký kraj', 0],['Karlovarský kraj',0],
        ['Liberecký kraj',0],['Moravskoslezský kraj',0],['Olomoucký kraj',0],['Pardubický kraj',0],
        ['Plzeňský kraj',0],['Středočeský kraj', 0],['Jihočeský kraj',0],
        ['Jihomoravský kraj',0],['Ústecký kraj',0],['Kraj Vysočina',0],['Zlínský kraj',0]];
    var regionsPopulation = [['Praha',1308632],['Královéhradecký kraj', 551021],['Karlovarský kraj',294896],
        ['Liberecký kraj',442356],['Moravskoslezský kraj',1203299],['Olomoucký kraj',632492],['Pardubický kraj',520316],
        ['Plzeňský kraj',584672],['Středočeský kraj', 1369332],['Jihočeský kraj',642133],
        ['Jihomoravský kraj',1187667],['Ústecký kraj',820789],['Kraj Vysočina',509274],['Zlínský kraj',582921]];
    console.log('loading wiki');
    fetch('/wikiData/').then(data => data.json()).then(data =>{
        console.log('loaded');
        var html = data.parse.text['*'];
        var htmlDom = new DOMParser().parseFromString(html, "text/xml");

        var cases = htmlDom.getElementsByClassName('wikitable')[0].children[0].children;
        var numberOfCasesOrigin = cases[cases.length-2].children[0].firstChild.textContent;
        if(numberOfCasesOrigin.includes('–')){
            let numberOfCasesSplitted = numberOfCasesOrigin.split('–');
            infectedCount = numberOfCasesSplitted[numberOfCasesSplitted.length-1];
        }
        else if(numberOfCasesOrigin.includes('-')){
            let numberOfCasesSplitted = numberOfCasesOrigin.split('-');
            infectedCount = numberOfCasesSplitted[numberOfCasesSplitted.length-1];
        }
        for(let i = 2; i < cases.length;i+=2){

            let state = cases[i].children[2].firstChild.textContent;
            if(state.toString().localeCompare('nepotvrzeno\n')===0){
                continue;
            }

            let caseNumber = cases[i].children[0].firstChild.textContent.toString();
            let countOfCases = 1;
            if(caseNumber.includes('–')){
                let caseNumberSplitted = caseNumber.split('–');
                countOfCases = parseInt(caseNumberSplitted[1]) - parseInt(caseNumberSplitted[0]) + 1;
            }
            else if(caseNumber.includes('-')){
                let caseNumberSplitted = caseNumber.split('-');
                countOfCases = parseInt(caseNumberSplitted[1]) - parseInt(caseNumberSplitted[0]) + 1;
            }

            let place = cases[i].children[5].textContent.split(',')[0].split(['\n'])[0].split(['['])[0];
            let region = cases[i].children[6].textContent.split(',')[0].split(['\n'])[0].split(['['])[0];
            let treatment = cases[i].children[7].textContent.split(',')[0].split(['\n'])[0].split(['['])[0];

            let found = false;

            if(window.innerWidth > 550){
                let queryPlace = '';
                if(place.toString().localeCompare('N/A') && place.toString().localeCompare('Praha') ||
                    (treatment.toString().localeCompare('N/A') && treatment.toString().localeCompare('domácí izolace'))){
                    if(treatment.toString().localeCompare('N/A') && treatment.toString().localeCompare('domácí izolace')){
                        queryPlace = treatment;
                    } else {
                        queryPlace = place;
                    }

                    for(let j = 0; j<placesToPrint.length;j++){
                        if(placesToPrint[j][0] === queryPlace){
                            placesToPrint[j][1]+=countOfCases;
                            found = true;
                            break;
                        }
                    }
                    if(!found){
                        placesToPrint.push([queryPlace, 1])
                    }
                }
            }

            if(region.toString().localeCompare('N/A')){
                found = false;
                for(let j = 0; j<regionsCor.length;j++){
                    if(regionsCor[j][0] === region){
                        regionsCor[j][1] += countOfCases;
                        found = true;
                        break;
                    }
                }
                if(!found){
                    console.log(region);
                    console.log('FUCK#1');
                }
            }
        }
    }).then(data => {
        if(window.innerWidth > 550){
            let k = 0;
            placesToPrint.forEach(place => {
                setTimeout(k*5);
                k++;

                let data = new FormData();
                data.append('type','findPlace');
                data.append('searchName',place[0]);
                var origin = 'http://koronamap.cz';
                fetch('/api.php', {
                    method: 'post',
                    body: data,
                    origin: origin
                }).then(response => response.json()).then(response => {
                    if(response[0] === 1 && response[1] !== null){
                        let latitude = JSON.parse(response[1]['location'])[0];
                        let longtitude = JSON.parse(response[1]['location'])[1];
                        let name = response[1]['name'];
                        let latlng = {lat: latitude, lng: longtitude};
                        createMarkerLatlng(latlng, name, place[1]);
                    } else {
                        let request = {
                            query: place[0],
                            fields: ['name', 'geometry'],
                        };

                        let success = false;
                        let tries = 0;

                        let intr = setInterval(function() {
                            service.findPlaceFromQuery(request, function(results, status) {
                                if (status === google.maps.places.PlacesServiceStatus.OK) {
                                    if(results.length){
                                        let foundPlace = results[0];

                                        let latlng = [];
                                        latlng.push(foundPlace['geometry']['location'].lat());
                                        latlng.push(foundPlace['geometry']['location'].lng());
                                        let data = new FormData();
                                        data.append('type','insertPlace');
                                        data.append('name',foundPlace['name']);
                                        data.append('searchName',place[0]);
                                        data.append('location',JSON.stringify(latlng));
                                        fetch('/api.php', {
                                            method: 'post',
                                            body: data,
                                            origin: origin
                                        }).then(response => response.json()).then(response => {console.log(response)});

                                        createMarkerGplace(results[0], foundPlace['name']);
                                    }
                                    success = true;
                                    clearInterval(intr);
                                } else {
                                    console.log(status);
                                    console.log("FUCK");
                                }
                            });
                            tries++;

                            if (success || (tries > 3)) clearInterval(intr);
                        }, 1000);
                    }
                });
                console.log(place[0]+' '+place[1]);
            });
        }
        fetch('/wikiData/regions.php').then(regionCorCount => regionCorCount.json()).then(regionCorCount =>{
            let deadCount = null;
            let recoveredCount = null;
            if(regionCorCount['infected']!==null && regionCorCount['infected'] > infectedCount){
                infectedCount = regionCorCount['infected'];
            }
            if(regionCorCount['dead']!==null){
                deadCount = regionCorCount['dead'];
            }
            if(regionCorCount['recovered']!==null){
                recoveredCount = regionCorCount['recovered'];
            }
            map.addListener('mousemove', function () {
                printInfo('Česká Republika',infectedCount, deadCount, recoveredCount);
            });
            map.addListener('click', function () {
                printInfo('Česká Republika',infectedCount, deadCount, recoveredCount);
            });
            printInfo('Česká Republika',infectedCount, deadCount, recoveredCount);
            var prague = [];
            for(let l = 1; l<15; l++){
                fetch('./regionPolygons/area'+String("0" + l).slice(-2)+'.txt').then(data => data.json()).then(data =>{
                    let regionCount = regionsCor[l-1][1];
                    if(regionCorCount[regionsCor[l-1][0]]!==null && regionCorCount[regionsCor[l-1][0]] > regionsCor[l-1][1]){
                        regionCount = regionCorCount[regionsCor[l-1][0]];
                    }
                    let color = color_white;
                    if(regionCount<1){
                        color = color01;
                    } else if(regionCount < 10){
                        color = color02;
                    } else if(regionCount < 100){
                        color = color03;
                    } else if(regionCount < 250){
                        color = color04;
                    } else if(regionCount < 500){
                        color = color05;
                    } else {
                        color = color06;
                    }

                    if(l===1){
                        prague = data;
                    }
                    else if (l===9) {
                        data = [data, prague.reverse()];
                    }

                    var bermudaTriangle = new google.maps.Polygon({
                        paths: data,
                        strokeColor: color,
                        strokeOpacity: 0.8,
                        strokeWeight: 3,
                        fillColor: color,
                        fillOpacity: 0.5
                    });
                    bermudaTriangle.setMap(map);
                    bermudaTriangle.addListener('mousemove', function () {
                        printInfo(regionsCor[l-1][0],regionCount);
                    });
                    bermudaTriangle.addListener('click', function () {
                        printInfo(regionsCor[l-1][0],regionCount);
                    });
                });
            }
            document.getElementById('colors').style.display='block';
            document.getElementById('info').style.display='block';
        });
    }).catch((error) => {
        console.error('Error:', error);
    });

}