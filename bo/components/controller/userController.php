<?php
namespace bo\components\controller;

use bo\components\classes\user;

include '../config.php';

switch($_POST['type']) {
    case("addUser"):
        if(empty($_POST['id'])) {
            user::addUser($_POST['data']);
        }
        else {
            user::editUser($_POST['data'], $_POST['id']);
        }
        break;
}

?>