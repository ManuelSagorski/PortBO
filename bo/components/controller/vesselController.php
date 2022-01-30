<?php
namespace bo\components\controller;

use bo\components\classes\helper\Logger;
use bo\components\classes\Vessel;
use bo\components\classes\VesselInfo;
use bo\components\classes\VesselContact;
use bo\components\classes\VesselContactDetails;
use bo\components\types\VesselTypes;
use bo\components\classes\Forecast;
use bo\components\classes\helper\Lookup;

include '../config.php';

switch($_POST['type']) {
    case("addVessel"):
        if(empty($_POST['id'])) {
            $vessel = new Vessel($_POST['data']);
            echo json_encode($vessel->addVessel());
        }
        else {
            $vessel = new Vessel($_POST['data'], $_POST['id']);
            echo json_encode($vessel->editVessel());
        }
        break;
    
    case("getVesselData"):
        echo getData($_POST['parameter']);
        break;
        
    case("getVesselLanguages"):
        echo getInfosFromITF($_POST['parameter']);
        break;
        
    case("addVesselInfo"):
        if(empty($_POST['infoID'])) {
            VesselInfo::safeInfo($_POST['data']);
        }
        else {
            VesselInfo::editInfo($_POST['data'], $_POST['infoID']);
        }
        break;
        
    case("deleteVesselInfo"):
        VesselInfo::deleteInfo($_POST['infoID']);
        break;
        
    case("addVesselContact"):
        if(empty($_POST['contactID'])) {
            echo json_encode((new VesselContact($_POST['data']))->addContact());
        }
        else {
            echo json_encode((VesselContact::getSingleObjectByID($_POST['contactID']))->editContact($_POST['data']));
        }
        break;
        
    case("deleteVesselContact"):
        (VesselContact::getSingleObjectByID($_POST['contactID']))->deleteContact();
        break;
        
    case("addVesselContactDetail"):
        if(empty($_POST['contactDetailID'])) {
            (new VesselContactDetails($_POST['data']))->addContactDetail();
        }
        else {
            (VesselContactDetails::getSingleObjectByID($_POST['contactDetailID']))->editContactDetail($_POST['data']);
        }
        break;
        
    case("deleteVesselContactDetail"):
        (VesselContactDetails::getSingleObjectByID($_POST['contactDetailID']))->deleteContactDetail();
        break;
        
    case("forecastItemDone"):
        Forecast::forecastItemDone($_POST['id']);
        break;

    case("forecastItemReopen"):
        Forecast::forecastItemReopen($_POST['id']);
        break;

    case("forecastItemRemove"):
        Forecast::forecastItemRemove($_POST['id']);
        break;
        
    case("addForecast"):
        Forecast::addForecast($_POST['data']);
        break;
        
    case("lookupRequestInformation"):
        lookup::lookupRequestInformation($_POST['id']);
        break;
}

/*
 * Funktionen rufen eventuell bei ITF oder Vesseltracker vorhandene Informationen zum Schiff ab
 */
function getInfosFromITF($imo) {
    $itfURL = "https://itfapi20170206042825.azurewebsites.net/api/vessels/?IMONumber=" . $imo;
    $data = json_decode(get_url($itfURL));
    
    return (!empty($data[0]->VesselDetailList[0]->LatestCrewList))?$data[0]->VesselDetailList[0]->LatestCrewList:"";
}

function getData($parameter){
    $vessTrackerURL = "https://www.vesseltracker.com/en/search?term=" . $parameter;
    $data = simplexml_load_string(get_url($vessTrackerURL));

    foreach($data->item as $item) {
        $json = json_encode($item);
        $arr = json_decode($json, TRUE);
        if((strlen($parameter) == 7 && isset($arr['imo']) && $arr['imo'] == $parameter) 
            || (strlen($parameter) == 9 && isset($arr['mmsi']) && $arr['mmsi'] == $parameter)) {
                if(isset($arr['imo'])) {
                    $arr['language'] =  getInfosFromITF($arr['imo']);
                }
                if(isset($arr['shipType'])) {
                    if(isset(vesselTypes::$vesselTypeMapper[$arr['shipType']])) {
                        $arr['shipType'] = vesselTypes::$vesselTypeMapper[$arr['shipType']];
                    }
                    else {
                        Logger::writeLogError('getData', 'Noch nicht bekannter Shiffstyp: ' . $arr['shipType']);
                    }
                }
                $json = json_encode($arr);
                
                return $json;
        }
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