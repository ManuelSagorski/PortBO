<?php
namespace bo\components\controller;

use bo\components\classes\User;
use bo\components\classes\helper\Security;
use bo\components\classes\helper\Query;

class UserController
{
    private $actualUser;
    
    public function __construct() {
        global $user;        
        $this->actualUser = $user;
    }
    
    public function addUser() {
        Security::grantAccess(8);
        
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
    
    public function deleteUser() {
        Security::grantAccess(8);
        
        User::getSingleObjectByID($_POST['id'])->deleteUser();
    }
    
    public function sendInvitationMail() {
        $userToEdit = User::getSingleObjectByID($_POST['id'], 0);
        $userToEdit->sendInvitationMail();
    }
    
    public function addUserKalender() {
        if(!empty($_POST['projectID'])) {
            $projectID = $_POST['projectID'];
        }
        else {
            $projectID = $_SESSION['project']; 
        }
        
        $userToEdit = User::getSingleObjectByID($_POST['id'], $_POST['projectID']);
        $userToEdit->addKalender($_POST['kalender'], $projectID);
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

