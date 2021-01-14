# covid19_API
PHP to easily access covid data from API's

# GNU GENERAL PUBLIC LICENSE

# API Source
https://api.covid19api.com

# Use
To use this class first set $searchParam to your country name to find the correct
'Slug' reference from the API (how the API names countries), then set $country to the correct
value for function calls, set $print to true to view data as an array.

The API returns data in json format - in this class all json data is decoded to arrays.

# Functions
# covidAlert::findSlug()
Slug is the name used for country specific data,
to filter set $searchParam with your desired country. Be aware names
like United Kingdom are set in [Slug] as united-kingdom therefore pass
united as $searchParam will return united-kingdom, united-states, united-arab-emirates,
set country variable for function calls once slug is found e.g. $country = united-kingdom.

# covidAlert::getRangedCovidData($from, $to, $print=NULL)
if arg not passed $from = yesterday, $to = today.
Will return all states e.g. confirmed, recovered, deaths. 

# covidAlert::getTotalCovidData($from, $to, $print=NULL)
if arg not passed $from = yesterday, $to = today.
Will return all states e.g. confirmed, recovered, deaths.

# covidAlert::covidDeclaredVector($vector)
$vector = confirmed, recovered, deaths from day 1 to now.

# covidAlert::covidLiveData($country)
live by country all states.