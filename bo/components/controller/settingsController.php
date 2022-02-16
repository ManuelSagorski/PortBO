<?php
namespace bo\components\controller;

use bo\components\classes\SettingsExternLinks;

include '../config.php';

switch($_POST['type']) {
    case("deleteLink"):
        (SettingsExternLinks::getSingleObjectByID($_POST['linkID']))->deleteLink();
        break;
    case("addLink"):
        if(empty($_POST['id'])) {
            (new SettingsExternLinks($_POST['data']))->addLink();
        }
        else {
            (SettingsExternLinks::getSingleObjectByID($_POST['id']))->updateLink($_POST['data']);
        }
        break;
}