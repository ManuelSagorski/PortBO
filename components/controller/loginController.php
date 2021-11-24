<?php
namespace components\controller;

use components\classes\dbConnect;
use components\classes\logger;
use components\classes\user;

/**
 * Klasse loginController
 * 
 * @author Manuel Sagorski
 *
 */
class loginController
{
    public function __construct() {}
    
    /**
     * Login eines Benutzers
     * 
     * @param Array $request
     * @return string[]
     */
    public function login($request) {
        $username = $_POST['username'];
        $passwort = $_POST['secret'];
        
        $user = dbConnect::fetchSingle("select * from port_bo_user where username = ?", user::class, array($username));
        
        If (empty($user)) {
            logger::writeLogError('login', 'Loginversuch mit unbekanntem Benutzername: ' . $username);
            return array('error' => 'Benutzername nicht bekannt');
        }
        else {
            If (password_verify($passwort, $user->getSecret())) {
                $_SESSION['user'] = $user->getId();
                logger::writeLogInfo('login', 'Login erfolgreich');
                return true;
            }
            else {
                logger::writeLogError('login', 'Loginversuch mit verkehrtem Passwort. Benutzername: ' . $username);
                return array('error' => 'Falsches Passwort');
            }
        }
    }
    
    /**
     * Nutzer hat Passwort vergessen
     * 
     * @param Array $request
     */
    public function forgotPassword($request) {
        $user = dbConnect::fetchSingle("select * from port_bo_user where username = ?", user::class, array($request['formData']['usernameReset']));
        
        if(!empty($user)) {
            $user->sendResetPasswordLink();
        }
        
        return 'Sofern der eingegebene Benutzername existiert, erhälst du eine Email mit weiteren Hinweisen zum Zurücksetzen des Passwortes.';
    }
    
    /**
     * Prüft ob ein Nutzer berechtigt ist sein Passwort zu ändern.
     * 
     * @param String $id
     */
    public function pwReset($request) {
        $resetUser = dbConnect::fetchSingle("select * from port_bo_user where id = ?", user::class, array($request['id']));
        
        if (password_verify($request['code'], $resetUser->getPasswordCode()) && strtotime($resetUser->getPasswordCodeTime()) > (time()-24*3600)) {
            return true;
        }
        else {
            logger::writeLogError('user', "Versuchtes Zurücksetzen des Passwortes mit einem verkehrten Link. Benutzer: " . user::getUserFullName($resetUser->getId()));
            return false;
        }
    }

    /**
     * Ändert das Passwort eines Nutzers
     *
     * @param Array $request
     */
    public function changePassword($request) {
        $resetUser = dbConnect::fetchSingle("select * from port_bo_user where id = ?", user::class, array($request['formData']['userID']));
        
        if (password_verify($request['formData']['userCode'], $resetUser->getPasswordCode()) && strtotime($resetUser->getPasswordCodeTime()) > (time()-24*3600)) {
            $resetUser->setNewPassword($request['formData']['secretNew1']);
            return "Du kannst dich jetzt mit dem neuen Passwort anmelden";
        }
    }
}

?>