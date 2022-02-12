<?php
namespace bo\components\classes\helper;

class Security
{
    public function __construct()
    {}
    
    public static function grantAccess($levelNeeded) {
        global $user;
        
        if(!$user->getLevel() >= $levelNeeded)
            header('Location: ' . MAIN_PATH . 'index.php');
    }
}

