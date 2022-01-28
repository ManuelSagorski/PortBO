<?php
namespace bo\components\controller;

use bo\components\classes\User;

include '../config.php';

switch($_POST['type']) {
    case("addUser"):
        if(empty($_POST['id'])) {
            User::addUser($_POST['data']);
        }
        else {
            User::editUser($_POST['data'], $_POST['id']);
        }
        break;

    case("sendInvitationMail"):
        $userToEdit = User::getSingleObjectByID($_POST['id']);
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
        $userToEdit = User::getSingleObjectByID($_POST['id']);
        $userToEdit->addKalender($_POST['kalender']);
        break;
}

?>