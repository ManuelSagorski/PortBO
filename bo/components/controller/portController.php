<?php
namespace bo\components\controller;

use bo\components\classes\port;
use bo\components\classes\company;

include '../config.php';

switch($_POST['type']) {
    case("addPort"):
        $port = new port($_POST['data']);
        $port->addPort();
        break;

    case("addPortCompany"):
        if(empty($_POST['companyID'])) {
            $company = new company($_POST['data']);
            $company->addCompany();
        }
        else {
            company::editCompany($_POST['data'], $_POST['companyID']);
        }
        break;
        
    case("deletePortCompany"):
        company::deleteCompany($_POST['id']);
        break;
}
?>