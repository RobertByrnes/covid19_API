<?php

/**
 * @author Robert Byrnes
 * @created 12/01/2020
 * @source https://api.covid19api.com
 * 
 * 
 * covidAlert::findSlug() Slug is the name used for country specific data,
 *      to filter set $searchParam with your desired country. Be aware names
 *      like United Kingdom are set in [Slug] as united-kingdom therefore pass
 *      united as $searchParam will return united-kingdom, united-states, united-arab-emirates,
 *      set country variable for function calls once slug is found e.g. $country = united-kingdom.
 * 
 * covidAlert::getRangedCovidData($from, $to, $print=NULL),
 *      if arg not passed $from = yesterday, $to = today.
 *      Will return all states e.g. confirmed, recovered, deaths. 
 * 
 * covidAlert::getTotalCovidData($from, $to, $print=NULL),
 *      if arg not passed $from = yesterday, $to = today.
 *      Will return all states e.g. confirmed, recovered, deaths.
 * 
 * covidAlert::covidDeclaredVector($vector),
 *      $vector = confirmed, recovered, deaths from day 1 to now.
 * 
 * covidAlert::covidLiveData($country),
 *      live by country all states.
 * 
 * To use this class first set $searchParam to your country name to find the correct
 * 'Slug' reference from the API (how the API names countries), then set $country to the correct
 * value for function calls, set $print to true to view data as an array.
 * 
 * The API returns data in json format - in this class all json data is decoded to arrays.
 * 
 **/


// Example Arguments
$from = '2021-01-13';
$to = '2021-01-14';
$print = true;
$vectorA = 'confirmed';
$vectorB = 'recovered';
$vectorC = 'deaths';
$country = 'united-kingdom';
$searchParam = 'united';

// Example Function Calls
// covidAlert::findSlug($searchParam, $print);
// covidAlert::getTotalCovidData($country, $from, $to, $print);
// covidAlert::getRangedCovidData($country, $from, $to, $print);
// covidAlert::covidDeclaredVector($country, $vectorC, $print);
// covidAlert::covidLiveData($country, $print);
// covidALert::getDailyNew();

Class covidAlert {

    public static function findSlug($searchParam, $print=NULL)
    {
        $funcName = 'findSlug';
        $url = 'https://api.covid19api.com/countries';
        $content =  file_get_contents($url);
        $countries  = json_decode($content);
        $matches = array();

        foreach ($countries as $country) {
            if (preg_match("/$searchParam/", $country->Slug)) {
                $matches[] = $country->Slug;  
            }
        }

        if ($print) {
            self::printR($matches, $funcName);
        } 
        return $matches;
    }

    
    public static function getRangedCovidData($country, $from, $to, $print=NULL)
    {
        $funcName = 'getRangedCovidData';
        $url = 'https://api.covid19api.com/country/'.$country.'?from='.$from.'T00:00:00Z&to='.$to.'T00:00:00Z';
        $content =  file_get_contents($url);
        $covidRange  = json_decode($content);
        if ($print) {
            self::printR($covidRange, $funcName);
        } 
        return $covidRange;
    }


    public static function getTotalCovidData($country, $from, $to, $print=NULL)
    {
        $funcName = 'getTotalCovidData';
        $url = 'https://api.covid19api.com/total/country/'.$country.'?from='.$from.'T00:00:00Z&to='.$to.'T00:00:00Z';
        $content =  file_get_contents($url);
        $covidTotal = json_decode($content);
        if ($print) {
            self::printR($covidTotal, $funcName);
        } 
        return $covidTotal; 
    }


    public static function covidDeclaredVector($country, $vector, $print=NULL)
    {
        $funcName = 'covidDeclaredVector';
        $url = 'https://api.covid19api.com/dayone/country/'.$country.'/status/'.$vector.'/live';
        $content =  file_get_contents($url);
        $covidVector  = json_decode($content);       
        if ($print) {
            self::printR($covidVector, $funcName);
        } 
        return $covidLive;
    }


    public static function covidLiveData($country, $print=NULL)
    {
        $funcName = 'covidLiveData';
        $url = 'https://api.covid19api.com/live/country/'.$country;
        $content =  file_get_contents($url);
        $covidLive  = json_decode($content);       
        if ($print) {
            self::printR($covidLive, $funcName);
        }
        return $covidLive;
    }

    public static function getDailyNew()
    {
        $funcName = 'getDailyNew';
        $print = true;
        $country = 'united-kingdom';
        $to = date('Y-m-d');
        $from = date('Y-m-d', strtotime($to . " -2 days"));
        $newTotal = self::getTotalCovidData($country, $from, $to);
        $dailyNew = array(
            'from'      => $newTotal[0]->Date,
            'to'        => $newTotal[1]->Date,
            'Confirmed' => $newTotal[1]->Confirmed - $newTotal[0]->Confirmed,
            'Deaths'    => $newTotal[1]->Deaths - $newTotal[0]->Deaths,
            'Recovered' => $newTotal[1]->Recovered - $newTotal[0]->Recovered,
            'Active'    => $newTotal[1]->Active - $newTotal[0]->Active
        );
        self::printR($dailyNew, $funcName);
    }

    private static function printR($data, $funcName)
    {
        if (preg_match('/wamp64|repositories/i', __DIR__) || !empty($_REQUEST['debug'])) {
            echo '<pre><b>'.str_repeat('=', 64)."\nCOVID ALERT:\n".str_repeat('=', 64)."\nFILE: ".__FILE__."\nLINE: ".__LINE__."\n"."FUNCTION: ".$funcName."()</b>\n".str_repeat('=', 64)."\n" 
            .print_r($data, true).'</pre>';
        }
    }
}