<?php
namespace bo\components\classes;

use bo\components\classes\helper\dbConnect;
use bo\components\classes\helper\logger;
use bo\components\classes\helper\ozg;
use bo\components\classes\helper\sendMail;
use bo\components\types\languages;

/**
 * Klasse user
 * @author Manuel Sagorski
 *
 */
class user extends abstractDBObject
{
    protected static $tableName = "port_bo_user";
    
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
    private $planning_id;
    
    private $userPorts = [];
    private $userLanguages = [];
    
    public static $userLevel = array(1 => "Verkündiger", 2 => "Foreign Port", 4 => "BO Mitarbeiter", 5 => "BO Supervisor", 9 => "Administrator");
    public static $defaultPage = array(2 => "lookup", 4 => "index", 5 => "index", 9 => "index");
    
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
        
        $pwd = self::generateHashForRandPassword($data['userLevel']);
        
        $sqlstrg = "insert into port_bo_user
                        (username, secret, email, phone, first_name, surname, level)
                    values
                        (?, ?, ?, ?, ?, ?, ?)";
        dbConnect::execute($sqlstrg, array($data['userUsername'], $pwd['pwdHash'], $data['userEmail'], $data['userPhone'], $data['userFirstName'], $data['userSurname'],
            $data['userLevel']));
        
        $newUser = user::getSingleObjectByID(dbConnect::getLastID());
        
        logger::writeLogCreate('settings', 'Neuer Benutzer angelegt: ' . $data['userFirstName'] . ' ' . $data['userSurname']);
        
        foreach(languages::$languages as $id=>$language) {
            if(in_array($id, $data['userLanguages'])) {
                $newUser->addUserLanguage($id);
            }
        }
        
        foreach($ports as $port) {
            if(in_array($port->getID(), $data['userPorts'])) {
                $newUser->addUserToPort($port->getID());
            }
        }
        
        if(!empty($pwd['pwd']) && isset($data['userSendInfo'])) {
            $mail = new sendMail();
            $mail->mail->addAddress($data['userEmail']);
            $mail->mail->Subject = "Dein Zugang zum Hafendienst-Backoffice";
            $mail->applyTemplate('_welcomeMail', array("Vorname" => $data['userFirstName'], "Benutzername" => $data['userUsername'], "Passwort" => $pwd['pwd']));
            
            $mail->mail->send();
        }
    }
    
    /**
     * Static Funktion die die Daten eines bestehenden Benutzers ändert
     * @param Array $data
     * @param int $user_id
     */
    public static function editUser($data, $user_id) {
        $ports = port::getMultipleObjects();
        
        $sqlstrg = "update port_bo_user
                       set username = ?, email = ?, phone = ?, first_name = ?, surname = ?, level = ?
                     where id = ?";
        dbConnect::execute($sqlstrg, array($data['userUsername'], $data['userEmail'], $data['userPhone'], $data['userFirstName'], $data['userSurname'],
            $data['userLevel'], $user_id));
        
        $actualUser = user::getSingleObjectByID($user_id);
        
        foreach(languages::$languages as $id=>$language) {
            if(in_array($id, $data['userLanguages']) && !$actualUser->userHasLanguage($id)) {
                $actualUser->addUserLanguage($id);
            }
            if(!in_array($id, $data['userLanguages']) && $actualUser->userHasLanguage($id)) {
                $actualUser->removeUserLanguage($id);
            }
        }
        
        foreach($ports as $port) {
            if(in_array($port->getID(), $data['userPorts']) && !$actualUser->userHasPort($port->getID())) {
                $actualUser->addUserToPort($port->getID());
            }
            if(!in_array($port->getID(), $data['userPorts']) && $actualUser->userHasPort($port->getID())) {
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
        $result = user::getSingleObjectByCondition(Array("concat(first_name, ' ', surname)" => $name));
        if(!empty($result)) {
            return $result->getID();
        }
        else {
            return 0;
        }
    }

    /**
     *  Function die dem Benutzer eine Einladungsmail mit Informationen zu seinem Benutzerkonto zuschickt
     *  Dabei wird das Passwort des Benutzers zurückgesetzt.
     */  
    public function sendInvitationMail() {
        $pwd = self::generateHashForRandPassword($this->level);
        
        dbConnect::execute("update port_bo_user set secret = ? where id = ?", Array($pwd['pwdHash'], $this->id));
        
        $mail = new sendMail();
        $mail->mail->addAddress($this->email);
        $mail->mail->Subject = "Dein Zugang zum Hafendienst-Backoffice";
        $mail->applyTemplate('_welcomeMail', array("Vorname" => $this->first_name, "Benutzername" => $this->username, "Passwort" => $pwd['pwd']));
        
        $mail->mail->send();
    }
    
    /**
     *  Function die dem Benutzer einen Link zum zurücksetzen des Passwortes zuschickt
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
    
    public function setNewEmail($newMail) {
        $sqlstrg = "update port_bo_user set email = ? where id = ?";
        dbConnect::execute($sqlstrg, Array($newMail, $this->id));
    }

    public function setNewPhone($newPhone) {
        $sqlstrg = "update port_bo_user set phone = ? where id = ?";
        dbConnect::execute($sqlstrg, Array($newPhone, $this->id));
    }
    
    /**
     * Legt für den User einen Zugang zum ÖZG Kalender an.
     */
    public function addKalender($kalender) { 
        $kalenderID = ozg::newOzgUser($this->first_name, $this->surname, $this->email, $this->phone, $kalender);
        
        if(is_numeric($kalenderID) and $kalenderID > 0) {
            dbConnect::execute("update port_bo_user set planning_id = ? where id = ?", Array($kalenderID, $this->id));
            logger::writeLogInfo('addKalender', 'Kalender angelegt für User: ' . $this->id);
        }
        else {
            logger::writeLogError('addKalender', 'OZG Profil konnte nicht erstellt werden für User: ' . $this->id);
        }
    }
    
    public function getLevelDescription() {
        return user::$userLevel[$this->getLevel()];
    }
    
    public function userGetPorts() {
        $sqlstrg = "select p.* 
                     from port_bo_port p join port_bo_userToPort utp on p.id = utp.port_id 
                    where utp.user_id = ?";
        $this->userPorts = dbConnect::fetchAll($sqlstrg, port::class, array($this->id));
    }
  
    public function userHasPort($portID) {
        foreach($this->userPorts as $port) {
            if($port->getID() == $portID)
                return true;
        }
        return false;
    }
    
    public function userHasLanguage($languageID) {
        return isset(array_column($this->userLanguages, null, 'language_id')[$languageID]);
    }

    private static function generateHashForRandPassword($userLevel) {
        $pwd = "";
        $pwdHash = "";
        
        if($userLevel > 1) {
            $bytes = openssl_random_pseudo_bytes(4);
            $pwd = bin2hex($bytes);
            $pwdHash = password_hash($pwd, PASSWORD_DEFAULT);
        }
        
        return Array("pwd" => $pwd, "pwdHash" => $pwdHash);
    }
    
    private function userGetLanguages() {
        $this->userLanguages = userToLanguage::getMultipleObjects(Array("user_id" => $this->id));
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
    public function getPlanningID() {
        return $this->planning_id;
    }
}

?>