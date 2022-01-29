<?php
namespace bo\components\controller;

use bo\components\classes\Port;
use bo\components\classes\Company;

include '../config.php';

switch($_POST['type']) {
    case("addPort"):
        (new Port($_POST['data']))->addPort();
        break;

    case("addPortCompany"):
        if(empty($_POST['companyID'])) {
            (new Company($_POST['data']))->addCompany();
        }
        else {
            (Company::getSingleObjectByID($_POST['companyID']))->editCompany($_POST['data']);
        }
        break;
        
    case("deletePortCompany"):
        (Company::getSingleObjectByID($_POST['id']))->deleteCompany();
        break;
}
?>