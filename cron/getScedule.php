<?php
namespace cron;

use components\classes\logger;

$independent = true;
include '../components/config.php';
include '../components/libs/simple_html_dom.php';

const URL_EUROGATE_SEGELLISTE = "https://www.eurogate.de/segelliste/state/show?_state=7i1bpj8n1j8g&_unique=1sof6jwexsptz&_transition=start&period=1&internal=false&languageNo=30&locationCode=HAM&order=+0";
// const URL_EUROGATE_SEGELLISTE = "http://www.port-mission.de/resultlist.html";

$expectedVessels = Array();

$rowsForArrivalDate = 0;
$rowsForArrivalTime = 0;
$rowsForLeavingDateTime = 0;
$rowsForVesselName = 0;
$rowsForAgency = 0;

$vesselKomplete = false;

if($html = getSite(URL_EUROGATE_SEGELLISTE)) {
    $resultlist = $html->find('.resultlist');
    
    foreach($resultlist[0]->find('tr') as $key => $tr) {
        $tds = $tr->find('td');

        /* Ankunft Datum */
        if($key == (2 + $rowsForArrivalDate)) {
            $vessel['arrivalDate'] = $tds[0]->plaintext;
            $rowsForArrivalDate = $rowsForArrivalDate + $tds[0]->rowspan;
            $timeTD = 1;
        }
        else { $timeTD = 0; }
        
        /* Ankuft Uhrzeit */
        if($key == (2 + $rowsForArrivalTime)) {
            $vessel['arrivalTime'] = $tds[$timeTD]->plaintext;
            $rowsForArrivalTime = $rowsForArrivalTime + $tds[$timeTD]->rowspan;
            $leavingTD = $timeTD + 1;
        }
        else { $leavingTD = 0; }
        
        /* Abfahrt Datum und Uhrzeit */
        if($key == (2 + $rowsForLeavingDateTime)) {
            $vessel['leavingDateTime'] = $tds[$leavingTD]->plaintext;
            $rowsForLeavingDateTime = $rowsForLeavingDateTime + $tds[$leavingTD]->rowspan;
            $nameTD = $leavingTD + 1;
        }
        else { $nameTD = $leavingTD + 0; }
        
        /* Name */
        if($key == (2 + $rowsForVesselName)) {
            $vessel['name'] = $tds[$nameTD]->plaintext;
            $rowsForVesselName = $rowsForVesselName + $tds[$nameTD]->rowspan;
            $agencyTD = $nameTD + 9;
            $vesselKomplete = true;
        }
        else { $agencyTD = 8; }
        
        /* Agentur */
        if($key == (2 + $rowsForAgency)) {
            $vessel['agency'] = $tds[$agencyTD]->plaintext;
            $rowsForAgency = $rowsForAgency + $tds[$agencyTD]->rowspan;
        }
        
        if($vesselKomplete) {
            $expectedVessels[] = $vessel;
            $vesselKomplete = false;
        }
    }
}

echo "<table>";
foreach ($expectedVessels as $vessel) {
    echo "<tr><td>" . $vessel['arrivalDate'] . "</td><td>" . $vessel['arrivalTime'] . "</td><td>" . $vessel['leavingDateTime'] . "</td><td>" . $vessel['name'] . "</td><td>" . $vessel['agency'] . "</td></tr>";
}
echo "</table>";





function getSite($url) {
    $getSite = curl_init();
    
    curl_setopt($getSite, CURLOPT_URL, $url);
    curl_setopt($getSite, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($getSite, CURLOPT_FOLLOWLOCATION, true);
        
    if(($data = curl_exec($getSite)) === false){
        $message = curl_error($getSite) . " - " . $url;
        logger::writeLogError('Curl', $message);
        $retVal = false;
    }
    elseif(($statuscode=curl_getinfo($getSite, CURLINFO_HTTP_CODE)) == 200){
        $retVal = str_get_html($data);
    }
    else{
        $message = curl_error($getSite) . " - " . $url;
        logger::writeLogError('Curl', $message);
        $retVal = false;
    }
    
    curl_close($getSite); 
    return $retVal;
}
?>