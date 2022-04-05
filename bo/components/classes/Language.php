<?php
namespace bo\components\classes;

use bo\components\classes\helper\Query;

class Language extends AbstractDBObject
{
    public const TABLE_NAME = "port_bo_language";
    
    private $id;
    private $name;
    
    public function __construct()
    {}
    
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

