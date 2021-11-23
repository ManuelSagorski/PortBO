<?php
namespace components\classes;

/**
 * Klasse logger
 * @author Manuel Sagorski
 *
 */
class logger
{
    public function __construct() {
    }
    
    public static function writeLogInfo($component, $message) {
        self::writeLog($component, 'info', $message);
    }
    
    public static function writeLogError($component, $message) {
        self::writeLog($component, 'error', $message);
    }
    
    public static function writeLogCreate($component, $message) {
        self::writeLog($component, 'create', $message);
    }
    
    public static function writeLogDelete($component, $message) {
        self::writeLog($component, 'delete', $message);
    }
    
    public static function writeLog($component, $logLevel, $message, $user = null) {
        if(isset($_SESSION['user']) && empty($user)) {
            $user = $_SESSION['user'];
        }
        
        $sqlstrg = "insert into port_bo_log
                        (user_id, logLevel, component, message)
                    values
                        (?, ?, ?, ?)";
        dbConnect::execute($sqlstrg, array($user, $logLevel, $component, $message));
    }
}

?>