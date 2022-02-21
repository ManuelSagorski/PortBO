<?php
namespace bo\components\contr;

use bo\components\classes\User;

class UserController
{
    private $actualUser;
    
    public function __construct() {
        global $user;        
        $this->actualUser = $user;
    }
    
    public function addUser() {
        if(empty($_POST['id'])) {
            echo json_encode((new User($_POST['data']))->addUser());
        }
        else {
            $project = null;
            if(isset($_POST['data']['projectID']))
                $project = $_POST['data']['projectID'];
                
                echo json_encode((User::getSingleObjectByID($_POST['id'], $project))->editUser($_POST['data']));
        }
    }
    
    public function sendInvitationMail() {
        $userToEdit = User::getSingleObjectByID($_POST['id']);
        $userToEdit->sendInvitationMail();
    }
    
    public function addUserKalender() {
        $userToEdit = User::getSingleObjectByID($_POST['id']);
        $userToEdit->addKalender($_POST['kalender']);
    }
    
    public function userChangePassword() {        
        $this->actualUser->setNewPassword($_POST['data']['secretNew1']);
    }
    
    public function userChangeMail() {
        $this->actualUser->setNewEmail($_POST['data']['emailNew']);
    }
    
    public function userChangePhone() {
        $this->actualUser->setNewPhone($_POST['data']['phoneNew']);
    }
    
    public function userSendMessage() {
        $this->actualUser->userSendMessage($_POST['data']);
    }
}

