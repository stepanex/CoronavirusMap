# Koronamap
Interactive map of coronavirus cases, now hosted at [koronamap.cz](https://koronamap.cz) and [koronamap.sk](https://koronamap.sk).

## Adding new state
It is fairly easy to add new state, create pull request when you are done.
1. Download state level 0 and level 1 KMZ from [gadm.org](https://gadm.org/download_country_v3.html)
1. Convert KMZ to GEOJSON ([ogre.adc4gis.com](https://ogre.adc4gis.com/))
1. Simplify geojson to ~7% using 'Visvalingam/weighted area' ([mapshaper.org](https://mapshaper.org/))
1. Rename file with level 0 to 'state.geojson' and file with level 1 to 'regions.geojson' and place them in 'states/[ISO 3166 of the state]/'
   1. Open regions.geojson in text editor and replace (or add) properties with '"properties":{"regionName":"[region name]"}'.
   1. Open state.geojson in text editor and replace (or add) properties with '"properties":{"stateName":"[ISO 3166 of the state]"}'.
1. Add 'regionsData.php' in 'states/[ISO 3166 of the state]/', this file should print array as json.
   * The array structure should be like this:
      * array['errorCount'] = number of errors.
      * array['error'] = array of error text.
      * array['infected'] = number of infected people.
      * array['dead'] = number of deaths.
      * array['recovered'] = number of recoveries.
      * array['infectedRegion'] = array of infections by regions. (for example "array['infectedRegion']['Praha'] = 2066")
      * array['deadRegion'] = array of deaths by regions. (for example "array['deadRegion']['Praha'] = 94")
      * array['recoveredRegion'] = array of recoveries by regions. (for example "array['recovereddRegion']['Praha'] = 1249")
   * The regions in 'infectedRegion', 'deadRegion' and 'recoveredRegion' has to be named same as in regions.geojson.
   * 'deadRegion' and 'recoveredRegion' are not required.
1. map.js add:
   1. state into variable 'countries'
   1. translation into variable 'countriesPopupTranslation'
   1. state center into variable 'stateCenter'
   1. state name into variable 'stateIndex'
   1. region names into variable 'regionsIndex'

Now your state should be visible at address/?state=[ISO 3166 of the state]
