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
                (new AgencyPortInfo($_POST['data']))->addAgencyPortInfo();
            }
            else {
                (AgencyPortInfo::getSingleObjectByID($_POST['id']))->editAgencyPortInfo($_POST['data']);
            }
            break;
            
    case("deleteAgencyPortInfo"):
        (AgencyPortInfo::getSingleObjectByID($_POST['id']))->deleteAgencyPortInfo();
        break;
}
?>