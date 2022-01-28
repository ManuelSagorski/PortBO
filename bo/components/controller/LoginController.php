<?php
namespace bo\components\controller;

use bo\components\classes\helper\Logger;
use bo\components\classes\User;

/**
 * Klasse loginController
 * 
 * @author Manuel Sagorski
 *
 */
class LoginController
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
        
        $user = User::getSingleObjectByCondition(Array("username" => $username));
        
        If (empty($user)) {
            Logger::writeLogError('login', 'Loginversuch mit unbekanntem Benutzername: ' . $username);
            return array('error' => 'Benutzername nicht bekannt');
        }
        else {
            If (password_verify($passwort, $user->getSecret())) {
                $_SESSION['user'] = $user->getId();
                $_SESSION['userLevel'] = $user->getLevel();
                Logger::writeLogInfo('login', 'Login erfolgreich');
                return true;
            }
            else {
                Logger::writeLogError('login', 'Loginversuch mit verkehrtem Passwort. Benutzername: ' . $username);
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
        $user = User::getSingleObjectByCondition(Array("username" => $request['formData']['usernameReset']));
        
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
        $resetUser = User::getSingleObjectByID($request['id']);
        
        if (password_verify($request['code'], $resetUser->getPasswordCode()) && strtotime($resetUser->getPasswordCodeTime()) > (time()-24*3600)) {
            return true;
        }
        else {
            Logger::writeLogError('user', "Versuchtes Zurücksetzen des Passwortes mit einem verkehrten Link. Benutzer: " . User::getUserFullName($resetUser->getId()));
            return false;
        }
    }

    /**
     * Ändert das Passwort eines Nutzers
     *
     * @param Array $request
     */
    public function changePassword($request) {
        $resetUser = User::getSingleObjectByID($request['formData']['userID']);
        
        if (password_verify($request['formData']['userCode'], $resetUser->getPasswordCode()) && strtotime($resetUser->getPasswordCodeTime()) > (time()-24*3600)) {
            $resetUser->setNewPassword($request['formData']['secretNew1']);
            return "Du kannst dich jetzt mit dem neuen Passwort anmelden";
        }
    }
}

?>