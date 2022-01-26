<?php
namespace bo\components\classes;

use bo\components\classes\helper\dbConnect;

abstract class abstractDBObject
{
    protected static $tableName;
    
    public static function getSingleObjectByID($id) {
        $sqlstrg = "select * from " . static::$tableName . " where id = ?";
        return dbConnect::fetchSingle($sqlstrg, static::class, array($id));
    }
    
    public static function getSingleObjectByCondition($conditions = [], $orderSequence = null) {
        $query = self::prepareQuery($conditions, $orderSequence);        
        return dbConnect::fetchSingle($query["sqlstrg"], static::class, $query["parameter"]);
    }
    
    /**
     * getMultipleObjects
     * 
     * Liefert mehrere rows aus der Datenbank zurück die den angegebenen Konditionen entsprechen
     * 
     * @param array $conditions - Array von Key-Value Paren ("name" => value)
     * @param String $orderSequence - optional: order by ...
     * @return Array of Objects
     */
    public static function getMultipleObjects($conditions = [], $orderSequence = null) {
        $query = self::prepareQuery($conditions, $orderSequence);        
        return dbConnect::fetchAll($query["sqlstrg"], static::class, $query["parameter"]);
    }
    
    private static function prepareQuery($conditions, $orderSequence) {
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

