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
}
?>