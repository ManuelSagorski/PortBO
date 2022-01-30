<?php
namespace bo\components\controller;

use bo\components\classes\SettingsForecastLists;

include '../config.php';

switch($_POST['type']) {
    case("deleteLink"):
        (SettingsForecastLists::getSingleObjectByID($_POST['linkID']))->deleteLink();
        break;
    case("addLink"):
        if(empty($_POST['id'])) {
            (new SettingsForecastLists($_POST['data']))->addLink();
        }
        else {
            (SettingsForecastLists::getSingleObjectByID($_POST['id']))->updateLink($_POST['data']);
        }
        break;
}