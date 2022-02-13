<?php
namespace bo\components\classes;

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

