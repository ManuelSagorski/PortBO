<?php
namespace bo\components\classes\helper;

/**
 * Klasse logger
 * @author Manuel Sagorski
 *
 */
class Logger
{
    public const LOG_TABLE = "port_bo_log";
    
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
        
        (new Query("insert"))
            ->table(self::LOG_TABLE)
            ->values([
                "user_id" => $user, 
                "logLevel" => $logLevel, 
                "component" => $component, 
                "message" => $message
            ])
            ->execute();
    }
    
    /*
     * Individueller Error-Handler
     */
    public function setErrorHandler($fehlercode, $fehlertext, $fehlerdatei, $fehlerzeile) {
        Logger::writeLogError("php error", $fehlercode . " - " . $fehlertext . " - " . $fehlerzeile . " - " . $fehlerdatei);        
        return false;
    }
}

?>