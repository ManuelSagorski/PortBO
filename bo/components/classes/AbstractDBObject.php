<?php
namespace bo\components\classes;

use bo\components\classes\helper\DBConnect;
use bo\components\classes\helper\Query;

abstract class AbstractDBObject
{
    /**
     * getSingleObjectByID
     *
     * Liefert als Objekt eine row aus der Datenbank zurück die der angegebenen ID entspricht
     *
     * @param int $id
     * @return Object
     */
    public static function getSingleObjectByID($id) {        
        $query = (new Query("select"))
            ->table(static::$tableName)
            ->condition(Array("id" => $id))
            ->build();
        return DBConnect::fetchSingle($query->sqlstrg, static::class, $query->parameter);
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
        $query = (new Query("select"))
            ->table(static::$tableName)
            ->condition($conditions)
            ->order($orderSequence)
            ->build();
        return DBConnect::fetchSingle($query->sqlstrg, static::class, $query->parameter);
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
        $query = (new Query("select"))
            ->table(static::$tableName)
            ->condition($conditions)
            ->order($orderSequence)
            ->build();
        return DBConnect::fetchAll($query->sqlstrg, static::class, $query->parameter);
    }
    
    
    /**
     * insertDB
     * 
     * Fügt einen Eintrag der Datenbank hinzu
     * 
     * @param Array $fields - Array von Key-Value Paren ("name" => value)
     */
    public function insertDB($fields) {
        $query = (new Query("insert"))
            ->table(static::$tableName)
            ->values($fields)
            ->build();        
        DBConnect::execute($query->sqlstrg, $query->parameter);
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
        $query = (new Query("update"))
            ->table(static::$tableName)
            ->values($fields)
            ->condition($conditions)
            ->build();        
        DBConnect::execute($query->sqlstrg, $query->parameter);
    }
}

