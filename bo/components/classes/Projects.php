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
    
    public function __construct()
    {}

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
}

