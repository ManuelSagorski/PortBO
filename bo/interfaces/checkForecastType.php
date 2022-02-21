<?php
namespace bo\cron;

use bo\components\classes\helper\Query;
use bo\components\classes\Forecast;

$independent = true;
include '../components/config.php';


$forecasts = (new Query("select"))
    ->table(Forecast::TABLE_NAME)
    ->condition(["type" => ''])
    ->conditionNot(["imo" => ''])
    ->fetchAll(Forecast::class);

foreach ($forecasts as $forecast) {
    $url = "https://www.myshiptracking.com/requests/autocomplete.php?req={{imo}}&res=all";
    $data = simplexml_load_string(get_url(str_replace('{{imo}}', $forecast->getIMO(), $url)));
    
    if(isset($data->RES->D) && stripos($data->RES->D, "TANKER") !== false) {
        (new Query("update"))->table(Forecast::TABLE_NAME)->values(["type" => "tanker"])->condition(["id" => $forecast->getID()])->execute();
    }
    else {
        (new Query("update"))->table(Forecast::TABLE_NAME)->values(["type" => "else"])->condition(["id" => $forecast->getID()])->execute();
    }
}


function get_url($url) {
    $curl = curl_init();
    $headers = array('Contect-Type:application/xml', 'Accept:application/xml');
    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($curl, CURLOPT_HEADER, 0);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_TIMEOUT, 30);
    
    $data = curl_exec($curl);
    curl_close($curl);
    
    return $data;
}
?>