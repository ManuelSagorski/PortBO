<?php
namespace bo\components\controller;

use bo\components\classes\user;
use bo\components\classes\helper\dbConnect;

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

    case("sendInvitationMail"):
        $userToEdit = dbConnect::fetchSingle("select * from port_bo_user where id = ?", user::class, Array($_POST['id']));
        $userToEdit->sendInvitationMail();
        break;
        
    case("userChangePassword"):
        $user->setNewPassword($_POST['data']['secretNew1']);
        break;
        
    case("userChangeMail"):
        $user->setNewEmail($_POST['data']['emailNew']);
        break;
        
    case("userChangePhone"):
        $user->setNewPhone($_POST['data']['phoneNew']);
        break;
        
    case("addUserKalender"):
        $userToEdit = dbConnect::fetchSingle("select * from port_bo_user where id = ?", user::class, Array($_POST['id']));
        $userToEdit->addKalender($_POST['kalender']);
        break;
}

?>