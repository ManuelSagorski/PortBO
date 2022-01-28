<?php
namespace bo\components\controller;

use bo\components\classes\Agency;
use bo\components\classes\AgencyPortInfo;

include '../config.php';

switch($_POST['type']) {    
    case("getAgencyInfo"):
        $agencyPortInfo = AgencyPortInfo::getMultipleObjects(Array("agency_id" => Agency::getAgentID($_POST['agency']), "port_id" => $_POST['port']));
        echo json_encode($agencyPortInfo);
        break;
        
    case("addAgency"):
        if(empty($_POST['id'])) {
            $agency = new Agency($_POST['data']);
            echo json_encode($agency->addAgency());
        }
        else {
            $agency = new Agency($_POST['data'], $_POST['id']);
            echo json_encode($agency->editAgency());
        }
        break;
        
    case("addAgencyPortInfo"):
            if(empty($_POST['id'])) {
                $agencyPortInfo = new AgencyPortInfo($_POST['data']);
                $agencyPortInfo->addAgencyPortInfo();
                
                AgencyPortInfo::addAgencyPortInfo($_POST['data']);
            }
            else {
                AgencyPortInfo::editAgencyPortInfo($_POST['data'], $_POST['id']);
            }
            break;
            
    case("deleteAgencyPortInfo"):
        AgencyPortInfo::deleteAgencyPortInfo($_POST['id']);
        break;
}
?>