<?php
namespace bo\components\controller;

use bo\components\classes\Port;
use bo\components\classes\Company;

include '../config.php';

switch($_POST['type']) {
    case("addPort"):
        $port = new Port($_POST['data']);
        $port->addPort();
        break;

    case("addPortCompany"):
        if(empty($_POST['companyID'])) {
            $company = new Company($_POST['data']);
            $company->addCompany();
        }
        else {
            Company::editCompany($_POST['data'], $_POST['companyID']);
        }
        break;
        
    case("deletePortCompany"):
        Company::deleteCompany($_POST['id']);
        break;
}
?>