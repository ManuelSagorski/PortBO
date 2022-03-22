<?php
namespace bo\components\classes;

use bo\components\classes\helper\Query;

class Projects extends AbstractDBObject
{
    public const TABLE_NAME = "port_bo_projects";
    
    private $id;
    private $name;
    private $short;
    
    private $mod_forecast;
    private $mod_planning;
    private $mod_planning_project;
    private $mod_externLinks;
    private $mod_foreignPort;
    private $mod_contactDetails;
    
    public function __construct()
    {}

    public function getProjectAdmins() {
        return (new Query("select"))
            ->table(User::TABLE_NAME)
            ->conditionGreater(["level" => 7])
            ->project($this->id)
            ->fetchAll(User::class);
    }
    
    public function getProjectForeignPortUser() {
        return (new Query("select"))
        ->table(User::TABLE_NAME)
        ->condition(["level" => 2])
        ->project($this->id)
        ->fetchAll(User::class);
    }
    
    public static function getProjectName($id) {
        $row = (new Query("select"))
        ->table(self::TABLE_NAME)
        ->condition(["id" => $id])
        ->execute()
        ->fetch();
        
        return $row['name'] ?? '';
    }
    
    public static function getProjectShort($id) {
        $row = (new Query("select"))
            ->table(self::TABLE_NAME)
            ->condition(["id" => $id])
            ->execute()
            ->fetch();
        
        return $row['short'] ?? '';
    }
    
    public static function toggleModule($module, $value, $projectID) {
        $query = (new Query("update"))
            ->table(self::TABLE_NAME)
            ->values([$module => intval(filter_var($value, FILTER_VALIDATE_BOOLEAN))])
            ->condition(["id" => $projectID])
            ->execute();
    }
    
    public function getID() {
        return $this->id;
    }
    public function getName() {
        return $this->name;
    }
    public function getShort() {
        return $this->short;
    }
    
    public function getModForecast() {
        return $this->mod_forecast;
    }
    public function getModPlanning() {
        return $this->mod_planning;
    }
    public function getModPlanningProject() {
        return $this->mod_planning_project;
    }
    public function getModExternLinks() {
        return $this->mod_externLinks;
    }
    public function getModForeignPort() {
        return $this->mod_foreignPort;
    }
    public function getModContactDetails() {
        return $this->mod_contactDetails;
    }
}

