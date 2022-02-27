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
    public static function getSingleObjectByID($id, $project = null) {        
        $query = (new Query("select"))
            ->table((get_called_class())::TABLE_NAME)
            ->condition(["id" => $id]);
            
        if($project !== null)
            $query->project($project);
            
        $query->build();
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
    public static function getSingleObjectByCondition($conditions = [], $orderSequence = null, $project = null) {
        $query = (new Query("select"))
            ->table((get_called_class())::TABLE_NAME)
            ->condition($conditions)
            ->order($orderSequence);
        
        if($project !== null)
            $query->project($project);
            
        $query->build();
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
    public static function getMultipleObjects($conditions = [], $orderSequence = null, $project = null) {
        $query = (new Query("select"))
            ->table((get_called_class())::TABLE_NAME)
            ->condition($conditions)
            ->order($orderSequence);
        
        if($project !== null)
            $query->project($project);
            
        $query->build();
            
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
            ->table((get_called_class())::TABLE_NAME)
            ->values($fields)
            ->build();            
        DBConnect::execute($query->sqlstrg, $query->parameter);
        
        return DBConnect::getLastID();
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
            ->table((get_called_class())::TABLE_NAME)
            ->values($fields)
            ->condition($conditions)
            ->build();        
        DBConnect::execute($query->sqlstrg, $query->parameter);
    }

    /**
     * deleteDB
     *
     * Löscht ein bestimmtes Element aus der Datenbank
     *
     * @param Array $conditions - Array von Key-Value Paren ("name" => value)
     */
    public function deleteDB($conditions) {
        (new Query("delete"))
            ->table((get_called_class())::TABLE_NAME)
            ->condition($conditions)
            ->execute();
    }
}

