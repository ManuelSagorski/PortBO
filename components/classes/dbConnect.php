<?php
namespace components\classes;

use \PDO;

/**
 * Klasse dbConnect
 * @author Manuel Sagorski
 *
 */
class dbConnect
{
    private static $PDOdb;
    
    public static function initDB() {
        self::$PDOdb = new PDO(SQL_HOST, SQL_USER, SQL_PW);
        self::$PDOdb->exec("SET CHARACTER SET utf8");
        self::$PDOdb->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING );
        
        $utl2 = new userToLanguage();
    }
    
    public static function fetchAll($sqlstrg, $class, $parameter) {
        $result = self::$PDOdb->prepare($sqlstrg);
        $result->execute($parameter);
        
        $error = $result->errorInfo();
        if(!empty($error[2])) {
            logger::writeLogError('dbConnect', print_r($error, true));
        }
        
        return $result->fetchAll(PDO::FETCH_CLASS, $class);
    }
    
    public static function fetchSingle($sqlstrg, $class, $parameter) {
        $result = self::$PDOdb->prepare($sqlstrg);
        $result->setFetchMode(PDO::FETCH_CLASS, $class);
        $result->execute($parameter);
        
        $error = $result->errorInfo();
        if(!empty($error[2])) {
            logger::writeLogError('dbConnect', print_r($error, true));
        }
        
        return $result->fetch();
    }
    
    public static function execute($sqlstrg, $parameter) {
        $result = self::$PDOdb->prepare($sqlstrg);
        $result->execute($parameter);
        
        $error = $result->errorInfo();
        if(!empty($error[2])) {
            logger::writeLogError('dbConnect', print_r($error, true));
        }
        
        return $result;
    }
    
    public static function getLastID() {
        return self::$PDOdb->lastInsertId();
    }
}

?>