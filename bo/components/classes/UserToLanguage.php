<?php
namespace bo\components\classes;

class UserToLanguage extends AbstractDBObject
{
    protected static $tableName = "port_bo_userToLanguage";
    
    private $id;
    private $user_id;
    
    public $language_id;
    
    public function __construct()
    {}
    
    public function getID() {
        return $this->id;
    }
    public function getUserID() {
        return $this->user_id;
    }
    public function getLanguageID() {
        return $this->language_id;
    }
}

?>