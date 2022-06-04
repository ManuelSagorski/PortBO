<?php
namespace bo\components\controller;

use bo\components\classes\SettingsExternLinks;
use bo\components\classes\Projects;
use bo\components\classes\helper\Query;
use bo\components\classes\Invitation;
use bo\components\classes\helper\SendMail;

class SettingsController
{
    public function __construct()
    {}
    
    /**
     * Speichert bzw. ändert einen externen Link
     */
    public function addExternLink() {
        if(empty($_POST['id'])) {
            (new SettingsExternLinks($_POST['data']))->addLink();
        }
        else {
            (SettingsExternLinks::getSingleObjectByID($_POST['id']))->updateLink($_POST['data']);
        }
    }
    
    /**
     * Löscht einen externen Link
     */
    public function deleteExternLink() {
        (SettingsExternLinks::getSingleObjectByID($_POST['linkID']))->deleteLink();
    }
    
    /**
     * Aktiviert bzw. deaktiviert ein Modul für ein bestimmtes Projekt
     */
    public function safeModuleSetting() {
        Projects::toggleModule($_POST['module'], $_POST['value'], $_POST['projectID']);
    }
    
    /**
     * Schickt eine Einladung zur Registrierung per Email
     */
    public function inviteUser() {
        $invitationKey = (new Invitation())->generateInvitationKey($_POST['projectID']);
        
        $mail = new SendMail();
        $mail->mail->addAddress($_POST['data']['email']);
        $mail->mail->Subject = "Hafendienst-Backoffice - Registrierung";
        $mail->applyTemplate('_newUserInvitation_' . $_POST['data']['mailLanguage'], array("LinkAdresse" => MAIN_PATH_WITH_HOST . "index.php?register=true&language=" . $_POST['data']['mailLanguage'] . "&code=" . $invitationKey));
        
        $mail->mail->send();
    }
    
    /**
     * Kann für den übergebenen User ein Kalender angelegt werden?
     * 
     * @param Object $userToEdit (User::class)
     */
    public static function canGetCalender($userToEdit, $projectID) {
        if(!empty($projectID))
            $project = Projects::getSingleObjectByID($projectID);
        else
            $project = Projects::getSingleObjectByID($_SESSION['project']);
            
        
        if(empty($userToEdit->getPlanningID()) && $userToEdit->getLevel() > 3 && $project->getModPlanning() && !empty($project->getModPlanningProject())) {
            return true;
        }
        else {
            return false;
        }
    }
}

