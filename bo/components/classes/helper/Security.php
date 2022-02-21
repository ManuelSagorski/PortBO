<?php
namespace bo\components\classes\helper;

class Security
{
    public function __construct()
    {}
    
    public static function grantAccess($levelNeeded) {
        global $user;
        
        if(!$user->getLevel() >= $levelNeeded) {
            header('Location: ' . MAIN_PATH . 'index.php');
            exit;
        }
    }
    
    public static function sessionDuration($sessionDuration) {
        if( isset( $_SESSION[ 'lastaccess' ] ) ) {
            $duration = time() - intval( $_SESSION[ 'lastaccess' ] );

            if( $duration > $sessionDuration ) {
                session_unset();
                session_destroy();
                session_start();
            }
        }
        $_SESSION['lastaccess'] = time();
    }
}

