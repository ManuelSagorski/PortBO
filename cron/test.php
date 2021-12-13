<?php
namespace cron;

$independent = true;
include '../bo/components/config.php';
include '../bo/components/libs/simple_html_dom.php';


// $url = 'https://www.fleetmon.com/ports/kiel_dekel_5484/?#tab-scheduled-arrivals';
$url = 'https://www.mauser-regaltechnik.de/produkte/gemaeldeauszugsdepot/';

$getSite = curl_init();

$agent = 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/95.0.4638.54 Safari/537.36';

curl_setopt($getSite, CURLOPT_URL, $url);
curl_setopt($getSite, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($getSite, CURLOPT_FOLLOWLOCATION, true);

curl_setopt($getSite, CURLOPT_USERAGENT, $agent);

if(($data = curl_exec($getSite)) === false){
    echo curl_getinfo($getSite, CURLINFO_HTTP_CODE) . " - " . $url;
}
elseif(($statuscode=curl_getinfo($getSite, CURLINFO_HTTP_CODE)) == 200){
    $retVal = str_get_html($data);
}
else{
    echo curl_getinfo($getSite, CURLINFO_HTTP_CODE) . " - " . $url;
}

curl_close($getSite);
echo $retVal;

?>