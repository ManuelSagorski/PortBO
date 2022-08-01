<?php
namespace bo\components\controller;

use bo\components\classes\SettingsExternLinks;
use bo\components\classes\Projects;
use bo\components\classes\helper\Query;
use bo\components\classes\Invitation;
use bo\components\classes\helper\SendMail;
use bo\components\classes\helper\Security;
use bo\components\classes\User;

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
        if(isset($_POST['data']['mailProject'])) {
            $projectID = $_POST['data']['mailProject'];
        }
        else {
            $projectID = $_POST['projectID'];
        }
        
        $invitationKey = (new Invitation())->generateInvitationKey($projectID);
        
        $mail = new SendMail();
        $mail->mail->addAddress($_POST['data']['email']);
        $mail->mail->Subject = "Hafendienst-Backoffice - Registrierung";
        $mail->applyTemplate('_newUserInvitation_' . $_POST['data']['mailLanguage'], array("LinkAdresse" => MAIN_PATH_WITH_HOST . "index.php?register=true&language=" . $_POST['data']['mailLanguage'] . "&code=" . $invitationKey));
        
        $mail->mail->send();
    }

    /**
     * Speichert ein neues Projekt in der Datenbank
     */
    public function safeProject() {
        (new Projects($_POST['data']))
            ->safeProject();
    }

    /**
     * Transferiert einen Benutzer in ein anderen Projekt
     */
    public function transferUser() {
        (new Query('update'))
            ->table(User::TABLE_NAME)
            ->values(['project_id' => $_POST['data']['newProject']])
            ->condition(['id' => $_POST['userID']])
            ->execute();
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

