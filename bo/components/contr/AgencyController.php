<?php
namespace bo\components\contr;

use bo\components\classes\AgencyPortInfo;
use bo\components\classes\Agency;

class AgencyController
{
    public function __construct()
    {}
    
    public function getAgencyInfo() {
        $agencyPortInfo = AgencyPortInfo::getMultipleObjects(Array("agency_id" => Agency::getAgentID($_POST['agency']), "port_id" => $_POST['port']));
        echo json_encode($agencyPortInfo);
    }
    
    public function addAgency() {
        if(empty($_POST['id'])) {
            $agency = new Agency($_POST['data']);
            echo json_encode($agency->addAgency());
        }
        else {
            $agency = new Agency($_POST['data'], $_POST['id']);
            echo json_encode($agency->editAgency());
        }
    }
    
    public function addAgencyPortInfo() {
        if(empty($_POST['id'])) {
            (new AgencyPortInfo($_POST['data']))->addAgencyPortInfo();
        }
        else {
            (AgencyPortInfo::getSingleObjectByID($_POST['id']))->editAgencyPortInfo($_POST['data']);
        }
    }
    
    public function deleteAgencyPortInfo() {
        (AgencyPortInfo::getSingleObjectByID($_POST['id']))->deleteAgencyPortInfo();
    }
}

