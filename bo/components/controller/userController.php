<?php
namespace bo\components\controller;

use bo\components\classes\User;
use bo\components\classes\helper\Logger;

include '../config.php';

switch($_POST['type']) {
    case("addUser"):
        if(empty($_POST['id'])) {
            echo json_encode((new User($_POST['data']))->addUser());
        }
        else {
            $project = null;
            if(isset($_POST['data']['projectID']))
                $project = $_POST['data']['projectID'];
            
            echo json_encode((User::getSingleObjectByID($_POST['id'], $project))->editUser($_POST['data']));
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
        
    case("userSendMessage"):
        $user->userSendMessage($_POST['data']);
        break;
}

?>