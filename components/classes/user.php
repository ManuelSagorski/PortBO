<?php
namespace components\classes;

use components\types\languages;

/**
 * Klasse user
 * @author Manuel Sagorski
 *
 */
class user
{
    private $id;
    private $username;
    private $secret;
    private $email;
    private $phone;
    private $first_name;
    private $surname;
    private $level;
    private $language;
    private $password_code;
    private $password_code_time;
    private $telegram_id;
    
    private $userPorts = [];
    private $userLanguages = [];
    
    public static $userLevel = array(1 => "Verkündiger", 2 => "BO Mitarbeiter", 5 => "BO Supervisor", 9 => "Administrator");
    
    /**
     * Konstructor
     */
    public function __construct() {
        $this->userGetPorts();
        $this->userGetLanguages();
    }    

    /**
     * Static Function - speichert einen neuen Mitarbeiter in der Datenbank
     * @param Array $data
     */
    public static function addUser($data) {
        global $ports;
        
        $pwd = "";
        $pwd_hash = "";
        
        if($data['userLevel'] > 1) {
            $bytes = openssl_random_pseudo_bytes(4);
            $pwd = bin2hex($bytes);
            $pwd_hash = password_hash($pwd, PASSWORD_DEFAULT);
        }
        
        $sqlstrg = "insert into port_bo_user
                        (username, secret, email, phone, first_name, surname, level)
                    values
                        (?, ?, ?, ?, ?, ?, ?)";
        dbConnect::execute($sqlstrg, array($data['userUsername'], $pwd_hash, $data['userEmail'], $data['userPhone'], $data['userFirstName'], $data['userSurname'],
            $data['userLevel']));
        
        logger::writeLogCreate('settings', 'Neuer Benutzer angelegt: ' . $data['userFirstName'] . ' ' . $data['userSurname']);
        
        $newUser = dbConnect::fetchSingle("select * from port_bo_user where username = ?", user::class, array($data['userUsername']));
        
        foreach(languages::$languages as $id=>$language) {
            if(isset($data['language_' . $language])) {
                $newUser->addUserLanguage($id);
            }
        }
        
        foreach($ports as $port) {
            if(isset($data['userPort' . $port->getName()])) {
                $newUser->addUserToPort($port->getID());
            }
        }
        
        if(!empty($pwd) && isset($data['userSendInfo'])) {
            $mail = new sendMail();
            $mail->mail->addAddress($data['userEmail']);
            $mail->mail->Subject = "Dein Zugang zum Hafendienst-Backoffice";
            $mail->applyTemplate('_welcomeMail', array("Vorname" => $data['userFirstName'], "Benutzername" => $data['userUsername'], "Passwort" => $pwd));
            
            $mail->mail->send();
        }
    }
    
    /**
     * Static Funktion die die Daten eines bestehenden Benutzers ändert
     * @param Array $data
     * @param int $user_id
     */
    public static function editUser($data, $user_id) {
        global $ports;
        
        $sqlstrg = "update port_bo_user
                       set username = ?, email = ?, phone = ?, first_name = ?, surname = ?, level = ?
                     where id = ?";
        dbConnect::execute($sqlstrg, array($data['userUsername'], $data['userEmail'], $data['userPhone'], $data['userFirstName'], $data['userSurname'],
            $data['userLevel'], $user_id));
        
        $actualUser = dbConnect::fetchSingle("select * from port_bo_user where id = ?", user::class, array($user_id));
        
        foreach(languages::$languages as $id=>$language) {
            if(isset($data['language_' . $language]) && !$actualUser->userHasLanguage($id)) {
                $actualUser->addUserLanguage($id);
            }
            if(!isset($data['language_' . $language]) && $actualUser->userHasLanguage($id)) {
                $actualUser->removeUserLanguage($id);
            }
        }
        
        foreach($ports as $port) {
            if(isset($data['userPort' . $port->getName()]) && !$actualUser->userHasPort($port->getID())) {
                $actualUser->addUserToPort($port->getID());
            }
            if(!isset($data['userPort' . $port->getName()]) && $actualUser->userHasPort($port->getID())) {
                $actualUser->removeUserFromPort($port->getID());
            }
        }
    }
    
    /**
     * Static Funktion die einen bestehenden Benutzer löscht
     */
    public static function deleteUser($id) {
        $sqlstrg = "delete from port_bo_userToPort where user_id = ?";
        dbConnect::execute($sqlstrg, array($id));
        
        $sqlstrg = "delete from port_bo_userToLanguage where user_id = ?";
        dbConnect::execute($sqlstrg, array($id));
        
        $sqlstrg = "delete from port_bo_user where id = ?";
        dbConnect::execute($sqlstrg, array($id));
    }
    
    /**
     * Static Funktion die den vollen Namen zu einer UserID liefert
     */
    public static function getUserFullName($id) {
        $sqlstrg = "select * from port_bo_user where id = ?";
        $result = dbConnect::execute($sqlstrg, array($id));
        $row = $result->fetch();
        return $row['first_name'] . " " . $row['surname'];
    }

    /**
     * Static Funktion die zu einem Namen die UserID zurückliefert
     */
    public static function getUserByFullName($name) {
        $result = dbConnect::fetchSingle("select * from port_bo_user where concat(first_name, ' ', surname) = ?", user::class, array($name));
        if(!empty($result)) {
            return $result->getID();
        }
        else {
            return 0;
        }
    }
    
    /**
     * Static Function die dem Benutzer einen Link zum zurücksetzen des Passwortes zuschickt
     */    
    public function sendResetPasswordLink() {
        $bytes = md5(time());
        $sqlstrg = "update port_bo_user set password_code = ?, password_code_time = NOW() where id = ?";
        dbConnect::execute($sqlstrg, array(password_hash($bytes, PASSWORD_DEFAULT), $this->id));
        
        $mail = new sendMail();
        $mail->mail->addAddress($this->email);
        $mail->mail->Subject = "Hafendienst-Backoffice - Passwort vergessen";
        $mail->applyTemplate('_passwordResetMail', array("Vorname" => $this->first_name, "LinkAdresse" => "http://".$_SERVER['HTTP_HOST']."/" . FOLDER . "/index.php?id=" . $this->id . "&code=" . $bytes));
        
        $mail->mail->send();
        
        logger::writeLogInfo('user', "Der Benutzer " . user::getUserFullName($this->id) . " hat einen Link zum Zurücksetzen des Passwortes angefordert.");
    }
    
    public function setNewPassword($newPassword) {
        $sqlstrg = "update port_bo_user set secret = ?, password_code = null, password_code_time = null where id = ?";
        dbConnect::execute($sqlstrg, array(password_hash($newPassword, PASSWORD_DEFAULT), $this->id));
    }
    
    public function getLevelDescription() {
        return user::$userLevel[$this->getLevel()];
    }
    
    public function userGetPorts() {
        $this->userPorts = dbConnect::fetchAll('select * from port_bo_userToPort where user_id = ?', userToPort::class, array($this->id));
    }
    
    private function userGetLanguages() {
        $this->userLanguages = dbConnect::fetchAll('select * from port_bo_userToLanguage where user_id = ?', userToLanguage::class, array($this->id));
    }
    
    public function userHasPort($portID) {
        return isset(array_column($this->userPorts, null, 'port_id')[$portID]);
    }
    
    public function userHasLanguage($languageID) {
        return isset(array_column($this->userLanguages, null, 'language_id')[$languageID]);
    }
    
    private function addUserToPort($portID) {
        $sqlstrg ="insert into port_bo_userToPort (user_id, port_id) values (?, ?)";
        dbConnect::execute($sqlstrg, array($this->id, $portID));
    }
    
    private function removeUserFromPort($portID) {
        $sqlstrg ="delete from port_bo_userToPort where user_id = ? and port_id = ?";
        dbConnect::execute($sqlstrg, array($this->id, $portID));
    }
    
    private function addUserLanguage($languageID) {
        $sqlstrg ="insert into port_bo_userToLanguage (user_id, language_id) values (?, ?)";
        dbConnect::execute($sqlstrg, array($this->id, $languageID));
    }
    
    private function removeUserLanguage($languageID) {
        $sqlstrg ="delete from port_bo_userToLanguage where user_id = ? and language_id = ?";
        dbConnect::execute($sqlstrg, array($this->id, $languageID));
    }
    
    /**
     * Static Function - setzt den Activity Timestamp zu einem bestimmten Benutzer
     * @param int $id
     */
    public static function setActivity($id) {
        dbConnect::execute("update port_bo_user set last_activity = now() where id = ?", array($id));
    }
    
    /**
     * Static Function gibt zurück ob grade ein Backoffice Mitarbeiter online ist
     * @return boolean
     */
    public static function isBoUserActive() {
        $activeUser = dbConnect::fetchAll("select * from port_bo_user where last_activity > TIMESTAMP(DATE_SUB(NOW(), INTERVAL 15 SECOND))", user::class, array());
        If(empty($activeUser)) {
            return false;
        }
        else {
            return true;
        }
    }
    
    /**
     * Getter und Setter
     */
    public function getId() {
        return $this->id;
    }
    public function getUsername() {
        return $this->username;
    }
    public function getSecret() {
        return $this->secret;
    }
    public function getEmail() {
        return $this->email;
    }
    public function getPhone() {
        return $this->phone;
    }
    public function getFirstName() {
        return $this->first_name;
    }
    public function getSurname() {
        return $this->surname;
    }
    public function getLevel() {
        return $this->level;
    }
    public function getLanguage() {
        return $this->language;
    }
    public function getPasswordCode() {
        return $this->password_code;
    }
    public function getPasswordCodeTime() {
        return $this->password_code_time;
    }
    public function getUserPorts() {
        return $this->userPorts;
    }
    public function getUserLanguages() {
        return $this->userLanguages;
    }
    public function getTelegramID() {
        return $this->telegram_id;
    }
}

?>