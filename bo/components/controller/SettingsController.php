<?php
namespace bo\components\controller;

use bo\components\classes\SettingsExternLinks;
use bo\components\classes\Projects;
use bo\components\classes\helper\Query;

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

