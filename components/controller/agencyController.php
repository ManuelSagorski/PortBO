<?php
namespace components\controller;

use components\classes\agency;
use components\classes\agencyPortInfo;
use components\classes\dbConnect;

include '../config.php';

switch($_POST['type']) {    
    case("getAgencyInfo"):
        $sqlstrg = "select * from port_bo_agencyPortInfo where agency_id = ? and port_id = ?";
        $agencyPortInfo = dbConnect::fetchAll($sqlstrg, agencyPortInfo::class, array(agency::getAgentID($_POST['agency']), $_POST['port']));
        echo json_encode($agencyPortInfo);
        break;
        
    case("addAgency"):
        if(empty($_POST['id'])) {
            $agency = new agency($_POST['data']);
            echo json_encode($agency->addAgency());
        }
        else {
            $agency = new agency($_POST['data'], $_POST['id']);
            echo json_encode($agency->editAgency());
        }
        break;
        
    case("addAgencyPortInfo"):
            if(empty($_POST['id'])) {
                agencyPortInfo::addAgencyPortInfo($_POST['data']);
            }
            else {
                agencyPortInfo::editAgencyPortInfo($_POST['data'], $_POST['id']);
            }
            break;
            
    case("deleteAgencyPortInfo"):
        agencyPortInfo::deleteAgencyPortInfo($_POST['id']);
        break;
}
?>