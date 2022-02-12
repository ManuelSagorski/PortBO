<?php
namespace bo\components\classes;

use bo\components\classes\helper\DBConnect;
use bo\components\classes\helper\Logger;
use bo\components\classes\helper\OZG;
use bo\components\classes\helper\SendMail;
use bo\components\types\Languages;
use bo\components\classes\helper\Query;
use bo\components\classes\helper\Telegram;

/**
 * Klasse user
 * @author Manuel Sagorski
 *
 */
class User extends AbstractDBObject
{
    public const TABLE_NAME = "port_bo_user";
    
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
    
    private $sendInfo;
    
    public static $userLevel = array(1 => "Verkündiger", 2 => "Foreign Port", 4 => "BO Mitarbeiter", 5 => "BO Supervisor", 9 => "Administrator");
    public static $defaultPage = array(2 => "lookup", 4 => "index", 5 => "index", 9 => "index");
    
    /**
     * Konstructor
     */
    public function __construct($data = null) {
        if(!empty($data)) {
            $this->username = $data['userUsername'];
            $this->email = $data['userEmail'];
            $this->phone = $data['userPhone'];
            $this->first_name = $data['userFirstName'];
            $this->surname = $data['userSurname'];
            $this->level = $data['userLevel'];
            $this->userLanguages = $data['userLanguages'];
            $this->userPorts = $data['userPorts'];
            if(isset($data['userSendInfo']))
                $this->sendInfo = $data['userSendInfo'];
        }
        else {
            $this->userGetPorts();
            $this->userGetLanguages();
        }
    }    

    /**
     * addUser - speichert einen neuen Mitarbeiter in der Datenbank
     * @param Array $data
     */
    public function addUser() {
        $pwd = $this->generateHashForRandPassword($this->level);
        
        $this->insertDB([
            "username" => $this->username,
            "secret" => $pwd['pwdHash'],
            "email" => $this->email,
            "phone" => $this->phone,
            "first_name" => $this->first_name,
            "surname" => $this->surname,
            "level" => $this->level
        ]);
        
        $this->id = DBConnect::getLastID();
        
        Logger::writeLogCreate('settings', 'Neuer Benutzer angelegt: ' . $this->first_name . ' ' . $this->surname);
        
        foreach(languages::$languages as $id=>$language) {
            if(in_array($id, $this->userLanguages)) {
                $this->addUserLanguage($id);
            }
        }
        
        foreach(Port::getMultipleObjects() as $port) {
            if(in_array($port->getID(), $this->userPorts)) {
                $this->addUserToPort($port->getID());
            }
        }
        
        if(!empty($pwd['pwd']) && !empty($this->sendInfo)) {
            $mail = new sendMail();
            $mail->mail->addAddress($this->email);
            $mail->mail->Subject = "Dein Zugang zum Hafendienst-Backoffice";
            $mail->applyTemplate('_welcomeMail', array("Vorname" => $this->first_name, "Benutzername" => $this->username, "Passwort" => $pwd['pwd']));
            
            $mail->mail->send();
        }
    }
    
    /**
     * editUser - Funktion die die Daten eines bestehenden Benutzers ändert
     * @param Array $data
     * @param int $user_id
     */
    public function editUser($data) {
        $this->updateDB([
            "username" => $data['userUsername'],
            "email" => $data['userEmail'],
            "phone" => $data['userPhone'],
            "first_name" => $data['userFirstName'],
            "surname" => $data['userSurname'],
            "level" => $data['userLevel']
        ], ["id" => $this->id]);
       
        foreach(languages::$languages as $id=>$language) {
            if(in_array($id, $data['userLanguages']) && !$this->userHasLanguage($id)) {
                $this->addUserLanguage($id);
            }
            if(!in_array($id, $data['userLanguages']) && $this->userHasLanguage($id)) {
                $this->removeUserLanguage($id);
            }
        }
        
        foreach(Port::getMultipleObjects() as $port) {
            if(in_array($port->getID(), $data['userPorts']) && !$this->userHasPort($port->getID())) {
                $this->addUserToPort($port->getID());
            }
            if(!in_array($port->getID(), $data['userPorts']) && $this->userHasPort($port->getID())) {
                $this->removeUserFromPort($port->getID());
            }
        }
    }
    
    /**
     * Static Funktion die einen bestehenden Benutzer löscht
     */
    public function deleteUser() {
        (new Query("delete"))
            ->table(UserToPort::TABLE_NAME)
            ->condition(["user_id" => $this->id])
            ->execute();
        (new Query("delete"))
            ->table(UserToLanguage::TABLE_NAME)
            ->condition(["user_id" => $this->id])
            ->execute();
        $this->deleteDB(["id" => $this->id]);
    }
    
    /**
     * Static Funktion die den vollen Namen zu einer UserID liefert
     */
    public static function getUserFullName($id) {
        $row = (new Query("select"))
            ->table(self::TABLE_NAME)
            ->condition(["id" => $id])
            ->execute()
            ->fetch();
        
        return $row['first_name'] . " " . $row['surname'];
    }

    /**
     * Static Funktion die zu einem Namen die UserID zurückliefert
     */
    public static function getUserByFullName($name) {
        $result = User::getSingleObjectByCondition(Array("concat(first_name, ' ', surname)" => $name));
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
        $pwd = $this->generateHashForRandPassword($this->level);
        
        $this->updateDB([
            "secret" => $pwd['pwdHash']
        ], ["id" => $this->id]);
       
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
        DBConnect::execute($sqlstrg, array(password_hash($bytes, PASSWORD_DEFAULT), $this->id));
        
        $mail = new sendMail();
        $mail->mail->addAddress($this->email);
        $mail->mail->Subject = "Hafendienst-Backoffice - Passwort vergessen";
        $mail->applyTemplate('_passwordResetMail', array("Vorname" => $this->first_name, "LinkAdresse" => MAIN_PATH . "index.php?id=" . $this->id . "&code=" . $bytes));
        
        $mail->mail->send();
        
        Logger::writeLogInfo('user', "Der Benutzer " . User::getUserFullName($this->id) . " hat einen Link zum Zurücksetzen des Passwortes angefordert.");
    }
    
    public function setNewPassword($newPassword) {
        $this->updateDB([
            "secret" => password_hash($newPassword, PASSWORD_DEFAULT),
            "password_code" => null,
            "password_code_time" => null
        ], ["id" => $this->id]);
    }
    
    public function setNewEmail($newMail) {
        $this->updateDB(["email" => $newMail], ["id" => $this->id]);
    }

    public function setNewPhone($newPhone) {
        $this->updateDB(["phone" => $newPhone], ["id" => $this->id]);
    }
    
    /**
     * Legt für den User einen Zugang zum ÖZG Kalender an.
     */
    public function addKalender($kalender) { 
        $kalenderID = ozg::newOzgUser($this->first_name, $this->surname, $this->email, $this->phone, $kalender);
        
        if(is_numeric($kalenderID) and $kalenderID > 0) {
            $this->updateDB(["planning_id" => $kalenderID], ["id" => $this->id]);
            Logger::writeLogInfo('addKalender', 'Kalender angelegt für User: ' . $this->id);
        }
        else {
            Logger::writeLogError('addKalender', 'OZG Profil konnte nicht erstellt werden für User: ' . $this->id);
        }
    }
    
    public function getLevelDescription() {
        return User::$userLevel[$this->getLevel()];
    }
    
    public function userGetPorts() {
        $this->userPorts = (new Query("select"))
            ->fields("p.*")
            ->table(Port::TABLE_NAME, "p")
            ->join(UserToPort::TABLE_NAME, "utp", "id", "port_id")
            ->condition(["utp.user_id" => $this->id])
            ->fetchAll(Port::class);
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

    /*
     * Auf der Profilseite eingegebene Nachricht des Nutzers wird an das Koordinatoren-Team geschickt
     */
    public function userSendMessage($data) {
        $adminUsers = User::getMultipleObjects(Array("level" => 9));
        
        foreach ($adminUsers as $adminUser) {
            $telegram = new telegram($adminUser->getTelegramID());
            
            $telegram->applyTemplate("_userSendMessage", Array(
                "name" => User::getUserFullName($_SESSION['user']), 
                "message" => $data['message']
            ));
            
            $telegram->sendMessage(false);
        }
    }
    
    private function generateHashForRandPassword($userLevel) {
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
        $this->userLanguages = UserToLanguage::getMultipleObjects(Array("user_id" => $this->id));
    }
    
    private function addUserToPort($portID) {
        (new Query("insert"))
            ->table(UserToPort::TABLE_NAME)
            ->values(["user_id" => $this->id, "port_id" => $portID])
            ->execute();
    }
    
    private function removeUserFromPort($portID) {
        (new Query("delete"))
            ->table(UserToPort::TABLE_NAME)
            ->condition(["user_id" => $this->id, "port_id" => $portID])
            ->execute();
    }
    
    private function addUserLanguage($languageID) {
        (new Query("insert"))
            ->table(UserToLanguage::TABLE_NAME)
            ->values(["user_id" => $this->id, "language_id" => $languageID])
            ->execute();
    }
    
    private function removeUserLanguage($languageID) {
        (new Query("delete"))
            ->table(UserToLanguage::TABLE_NAME)
            ->condition(["user_id" => $this->id, "language_id" => $languageID])
            ->execute();
    }
    
    /**
     * Static Function - setzt den Activity Timestamp zu einem bestimmten Benutzer
     * @param int $id
     */
    public static function setActivity($id) {
        DBConnect::execute("update port_bo_user set last_activity = now() where id = ?", array($id));
    }
    
    /**
     * Static Function gibt zurück ob grade ein Backoffice Mitarbeiter online ist
     * @return boolean
     */
    public static function isBoUserActive() {
        $activeUser = DBConnect::fetchAll("select * from port_bo_user where last_activity > TIMESTAMP(DATE_SUB(NOW(), INTERVAL 15 SECOND))", User::class, array());
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