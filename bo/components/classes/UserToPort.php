<?php
namespace bo\components\classes;

class UserToPort extends AbstractDBObject
{
    protected static $tableName = "port_bo_userToPort";
    
    private $id;
    private $user_id;
    
    public $port_id;
    
    public function __construct()
    {}
    
    public function getID() {
        return $this->id;
    }
    public function getUserID() {
        return $this->user_id;
    }
    public function getPortID() {
        return $this->port_id;
    }
}

?>