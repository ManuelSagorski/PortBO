<?php
namespace bo\components\controller;

use bo\components\classes\Port;
use bo\components\classes\Company;

class PortController
{
    public function __construct() {
    }
    
    public function addPort() {
        if(empty($_POST['id'])) {
            (new Port($_POST['data']))->addPort();
        }
        else {
            (Port::getSingleObjectByID($_POST['id']))->updatePort($_POST['data']);
        } 
    }
    
    public function addPortCompany() {
        if(empty($_POST['companyID'])) {
            (new Company($_POST['data']))->addCompany();
        }
        else {
            (Company::getSingleObjectByID($_POST['companyID']))->editCompany($_POST['data']);
        }
    }
    
    public function deletePortCompany() {
        (Company::getSingleObjectByID($_POST['id']))->deleteCompany();
    }
}

