<?php
namespace bo\components\classes;

use bo\components\classes\helper\DBConnect;

abstract class AbstractDBObject
{
    /**
     * insertDB
     * 
     * Fügt einen Eintrag der Datenbank hinzu
     * 
     * @param Array $fields - Array von Key-Value Paren ("name" => value)
     */
    public function insertDB($fields) {
        $sqlstrg = "insert into " . static::$tableName . " ({{names}}) values ({{values}})";
        $names = "";
        $values = "";
        $parameter = [];
        
        foreach($fields as $name => $value) {
            if($name !== array_key_first($fields)) {
                $names .= ", ";
                $values .= ", ";
            }
            $names .= $name;
            $values .= "?";
            array_push($parameter, $value);
        }
        
        DBConnect::execute(str_replace(Array("{{names}}", "{{values}}"), Array($names, $values), $sqlstrg), $parameter);
    }
    
    /**
     * updateDB
     * 
     * Update von Elementen in der Datenbank
     * 
     * @param Array $fields - Zu aktualisierende Werte: Array von Key-Value Paren ("name" => value)
     * @param Array $conditions - Array von Key-Value Paren ("name" => value)
     */
    public function updateDB($fields, $conditions) {
        $sqlstrg = "update " . static::$tableName . " set ";
        $parameter = [];
        
        foreach($fields as $name => $value) {
            if($name !== array_key_first($fields)) {
                $sqlstrg .= ", ";
            }
            $sqlstrg .= $name . " = ?";
            array_push($parameter, $value);
        }
        
        foreach ($conditions as $name => $value) {
            if($name === array_key_first($conditions)) {
                $sqlstrg .= " where ";
            }
            else {
                $sqlstrg .= " and ";
            }
            
            $sqlstrg .= $name . " = ?";
            
            array_push($parameter, $value);
        }
        
        DBConnect::execute($sqlstrg, $parameter);
    }
    
    /**
     * getSingleObjectByID
     * 
     * Liefert als Objekt eine row aus der Datenbank zurück die der angegebenen ID entspricht
     * 
     * @param int $id
     * @return Object
     */
    public static function getSingleObjectByID($id) {
        $sqlstrg = "select * from " . static::$tableName . " where id = ?";
        return DBConnect::fetchSingle($sqlstrg, static::class, array($id));
    }
    
    /**
     * getSingleObjectByCondition
     * 
     * Liefert als Objekt eine row aus der Datenbank zurück die den angegebenen Konditionen entspricht
     * 
     * @param array $conditions - Array von Key-Value Paren ("name" => value)
     * @param String $orderSequence - optional: order by ...
     * @return Object
     */
    public static function getSingleObjectByCondition($conditions = [], $orderSequence = null) {
        $query = self::prepareQuerySelect($conditions, $orderSequence);        
        return DBConnect::fetchSingle($query["sqlstrg"], static::class, $query["parameter"]);
    }
    
    /**
     * getMultipleObjects
     * 
     * Liefert als Objekte mehrere rows aus der Datenbank zurück die den angegebenen Konditionen entsprechen
     * 
     * @param array $conditions - Array von Key-Value Paren ("name" => value)
     * @param String $orderSequence - optional: order by ...
     * @return Array of Objects
     */
    public static function getMultipleObjects($conditions = [], $orderSequence = null) {
        $query = self::prepareQuerySelect($conditions, $orderSequence);        
        return DBConnect::fetchAll($query["sqlstrg"], static::class, $query["parameter"]);
    }
    
    private static function prepareQuerySelect($conditions, $orderSequence) {
        $sqlstrg = "select * from " . static::$tableName;
        $parameter = [];
        
        foreach ($conditions as $name => $value) {
            if($name === array_key_first($conditions)) {
                $sqlstrg .= " where ";
            }
            else {
                $sqlstrg .= " and ";
            }
            
            $sqlstrg .= $name . " = ?";
            
            array_push($parameter, $value);
        }
        
        if(!empty($orderSequence)) {
            $sqlstrg .= " order by " . $orderSequence;
        }
        
        return Array("sqlstrg" => $sqlstrg, "parameter" => $parameter);
    }
}

