<?php
namespace bo\components\classes;

use bo\components\classes\helper\Query;
use bo\components\classes\helper\Logger;

class Language extends AbstractDBObject
{
    public const TABLE_NAME = "port_bo_language";
    
    private $id;
    private $name;
    
    public function __construct($data = null) {
        if(!empty($data)) {
            $this->name = trim($data['languageName']);
        }
    } 
    
    public function addLanguage() {
        global $user;
        
        $this->insertDB([
            "name" => $this->name
        ]);
        
        Logger::writeLogCreate('Language', "User: " . $user->getID() . " hat eine neue Sprache hinzugefügt: " . $this->name);
    }
    
    public function updateLanguage($data) {
        $this->updateDB([
            "name" => trim($data['languageName'])
        ], ["id" => $this->id]);
    }
    
    public static function getLanguageByID($id) {
        $language = (new Query("select"))->table(self::TABLE_NAME)->condition(["id" => $id])->fetchSingle(Language::class);
        
        if(!empty($language)) {
            return $language->getName();
        }
        else {
            return null;
        }
    }
    
    public function getID() {
        return $this->id;
    }
    public function getName() {
        return $this->name;
    }
}

