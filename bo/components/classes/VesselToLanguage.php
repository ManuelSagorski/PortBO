<?php
namespace bo\components\classes;

class VesselToLanguage extends AbstractDBObject
{
    public const TABLE_NAME = "port_bo_vesselToLanguage";
    
    private $id;
    private $vessel_id;
    private $language_id;
    private $master;
    
    public function __construct()
    {}
    
    public function getID() {
        return $this->id;
    }
    public function getVesselID() {
        return $this->vessel_id;
    }
    public function getLanguageID() {
        return $this->language_id;
    }
    public function getMaster() {
        return $this->master;
    }
}

