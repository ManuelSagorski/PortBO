<?php
namespace bo\components\contr;

use bo\components\classes\SettingsExternLinks;

class SettingsController
{
    public function __construct()
    {}
    
    public function addExternLink() {
        if(empty($_POST['id'])) {
            (new SettingsExternLinks($_POST['data']))->addLink();
        }
        else {
            (SettingsExternLinks::getSingleObjectByID($_POST['id']))->updateLink($_POST['data']);
        }
    }
    
    public function deleteExternLink() {
        (SettingsExternLinks::getSingleObjectByID($_POST['linkID']))->deleteLink();
    }
}

