<?php
namespace bo\components\controller;

use bo\components\classes\Vessel;
use bo\components\classes\VesselInfo;
use bo\components\classes\VesselContact;
use bo\components\classes\VesselContactDetails;
use bo\components\classes\Forecast;
use bo\components\classes\helper\Lookup;
use bo\components\types\VesselTypes;
use bo\components\classes\helper\Logger;

class VesselController
{
    public function __construct()
    {}
    
    public function getVesselData() {
        echo $this->getData($_POST['parameter']);
    }
    
    public function getVesselLanguages() {
        echo $this->getInfosFromITF($_POST['parameter']);
    }
    
    public function addVessel() {
        if(empty($_POST['id'])) {
            $vessel = new Vessel($_POST['data']);
            echo json_encode($vessel->addVessel());
        }
        else {
            $vessel = new Vessel($_POST['data'], $_POST['id']);
            echo json_encode($vessel->editVessel());
        }
    }
    
    public function addVesselInfo() {
        if(empty($_POST['infoID'])) {
            (new VesselInfo($_POST['data']))->safeInfo();
        }
        else {
            VesselInfo::editInfo($_POST['data'], $_POST['infoID']);
        }
    }
    
    public function deleteVesselInfo() {
        (VesselInfo::getSingleObjectByID($_POST['infoID']))->deleteInfo();
    }
    
    public function addVesselContact() {
        if(empty($_POST['contactID'])) {
            echo json_encode((new VesselContact($_POST['data']))->addContact());
        }
        else {
            echo json_encode((VesselContact::getSingleObjectByID($_POST['contactID']))->editContact($_POST['data']));
        }
    }
    
    public function deleteVesselContact() {
        (VesselContact::getSingleObjectByID($_POST['contactID']))->deleteContact();
    }
    
    public function addVesselContactDetail() {
        if(empty($_POST['contactDetailID'])) {
            (new VesselContactDetails($_POST['data']))->addContactDetail();
        }
        else {
            (VesselContactDetails::getSingleObjectByID($_POST['contactDetailID']))->editContactDetail($_POST['data']);
        }
    }
    
    public function deleteVesselContactDetail() {
        (VesselContactDetails::getSingleObjectByID($_POST['contactDetailID']))->deleteContactDetail();
    }
    
    public function contactDetailSupposed() {
        (VesselContactDetails::getSingleObjectByID($_POST['contactDetailID']))->toggleSupposed();
    }

    public function contactDetailInvalid() {
        (VesselContactDetails::getSingleObjectByID($_POST['contactDetailID']))->toggleInvalid();
    }
    
    public function forecastItemDone() {
        Forecast::forecastItemDone($_POST['id']);
    }
    
    public function forecastItemReopen() {
        Forecast::forecastItemReopen($_POST['id']);
    }
    
    public function forecastItemRemove() {
        Forecast::forecastItemRemove($_POST['id']);
    }
    
    public function addForecast() {
        Forecast::addForecast($_POST['data']);
    }
    
    public function lookupRequestInformation() {
        Lookup::lookupRequestInformation($_POST['id']);
    }
    
    private function getInfosFromITF($imo) {
        $itfURL = "https://itfapi20170206042825.azurewebsites.net/api/vessels/?IMONumber=" . $imo;
        $data = json_decode($this->get_url($itfURL));
        
        return (!empty($data[0]->VesselDetailList[0]->LatestCrewList))?$data[0]->VesselDetailList[0]->LatestCrewList:"";
    }
    
    private function getData($parameter) {
        $vessTrackerURL = "https://www.vesseltracker.com/en/search?term=" . $parameter;
        $data = simplexml_load_string($this->get_url($vessTrackerURL));
        
        foreach($data->item as $item) {
            $json = json_encode($item);
            $arr = json_decode($json, TRUE);
            if((strlen($parameter) == 7 && isset($arr['imo']) && $arr['imo'] == $parameter)
                || (strlen($parameter) == 9 && isset($arr['mmsi']) && $arr['mmsi'] == $parameter)) {
                    if(isset($arr['imo'])) {
                        $arr['language'] =  $this->getInfosFromITF($arr['imo']);
                    }
                    if(isset($arr['shipType'])) {
                        if(isset(VesselTypes::$vesselTypeMapper[$arr['shipType']])) {
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
    
    private function get_url($url) {
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
}

