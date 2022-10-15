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
        self::sendMessage($component, $message);
    }
    
    public static function writeLogWarning($component, $message) {
        self::writeLog($component, 'warning', $message);
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
    
    public static function setErrorReporting() {
        register_shutdown_function(function () {
            $err = error_get_last();
            if (! is_null($err)) {
                Logger::writeLogError("php error", $err['message'] . " - " . $err['line'] . " - " . $err['file']);
            }
        });
    }
    
    public static function sendMessage(string $component, string $message) {
        $mail = new SendMail();
        $mail->mail->addAddress('manuel@sagorski.net');
        $mail->mail->Subject = 'Port Backoffice - Error Report';
        $mail->applyTemplate('email/_errorReport', ['component' => $component, 'message' => $message]);
        $mail->mail->Priority = 1;
        $mail->mail->AddCustomHeader("X-MSMail-Priority: High");
        $mail->mail->AddCustomHeader("Importance: High");
        $mail->mail->send();  
    }
}

?>