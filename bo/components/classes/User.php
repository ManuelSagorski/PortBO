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
    private $inactive;
    private $active;
    private $project_id;
    private $foreign_port;
    private $username;
    private $secret;
    private $email;
    private $phone;
    private $first_name;
    private $surname;
    private $level;
    private $coordination;
    private $password_code;
    private $password_code_time;
    private $telegram_id;
    private $telegram_code;
    private $planning_id;
    private $dataprotection;
    private $default_language;
    private $notifications;
    
    private $userPorts = [];
    private $userLanguages = [];
    
    private $sendInfo;
    
    public static $userLevel = array(0 => "no permission", 2 => "Foreign Port", 3 => "Publisher", 4 => "BO Assistant", 5 => "BO Supervisor", 8 => "Project Admin", 9 => "Administrator");
    public static $defaultPage = array(2 => "lookup", 3 => "index", 4 => "index", 5 => "index", 8 => "index", 9 => "index");
    
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
            if(isset($data['userLanguages'])) {
                $this->level = $data['userLevel'];
            }
            else {
                $this->level = 0;
            }
            if(isset($data['userLanguages']))
                $this->userLanguages = $data['userLanguages'];
            if(isset($data['userPorts']))
                $this->userPorts = $data['userPorts'];
            if(isset($data['userSendInfo']))
                $this->sendInfo = $data['userSendInfo'];
            if(isset($data['projectID']))
                $this->project_id = $data['projectID'];
            if(isset($data['foreignPort']))
                $this->foreign_port = $data['foreignPort'];
            if(isset($data['password1'])) {
                $this->secret = $data['password1'];
            }
            else {
                $this->secret = null;
            }
            if(isset($data['language'])) {
                $this->default_language = $data['language'];
            }
            if(isset($data['coordination'])) {
                $this->coordination = 1;
            }
            else {
                $this->coordination = 0;
            }
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
    public function addUser($register = false) {
        global $user;
        
        if($msg = $this->validateNewUserInput()) {
            return ["type" => "error", "msg" => $msg];
        }
        
        if(!empty($this->secret)) {
            $pwd = Array("pwdHash" => password_hash($this->secret, PASSWORD_DEFAULT));
        }
        else {
            $pwd = $this->generateHashForRandPassword($this->level);
        }
        
        $insertRequest = (new Query("insert"))
            ->table(self::TABLE_NAME)
            ->values([
                "username" => $this->username,
                "secret" => $pwd['pwdHash'],
                "email" => $this->email,
                "phone" => $this->phone,
                "first_name" => $this->first_name,
                "surname" => $this->surname,
                "level" => $this->level,
                "coordination" => $this->coordination,
                "foreign_port" => $this->foreign_port,
                "default_language" => $this->default_language
            ]);
        
            if(!empty($this->project_id) && ($register || $user->getLevel() == 9))
            $insertRequest->project($this->project_id);
        
        $insertRequest->execute();
        
        $this->id = DBConnect::getLastID();
        
        Logger::writeLogCreate('settings', 'Neuer Benutzer angelegt: ' . $this->first_name . ' ' . $this->surname);
        
        foreach(Language::getLanguages() as $id=>$language) {
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
        
        return ["type" => "success"];
    }
    
    /**
     * editUser - Funktion die die Daten eines bestehenden Benutzers ??ndert
     * @param Array $data
     * @param int $user_id
     */
    public function editUser($data) {
        if($msg = $this->validateNewUserInput($data)) {
            return ["type" => "error", "msg" => $msg];
        }
        
        if(isset($data['coordination'])) {
            $coordination = 1;
        }
        else {
            $coordination = 0;
        }
        
        $this->updateDB([
            "username" => $data['userUsername'],
            "email" => $data['userEmail'],
            "phone" => $data['userPhone'],
            "first_name" => $data['userFirstName'],
            "surname" => $data['userSurname'],
            "level" => $data['userLevel'],
            "coordination" => $coordination
        ], ["id" => $this->id]);
        
        if(isset($data['foreignPort'])) {
            $this->updateDB(["foreign_port" => $data['foreignPort']], ["id" => $this->id]);
        }
       
        foreach(Language::getLanguages() as $id=>$language) {
            if(isset($data['userLanguages'])) {
                if(in_array($id, $data['userLanguages']) && !$this->userHasLanguage($id)) {
                    $this->addUserLanguage($id);
                }
                if(!in_array($id, $data['userLanguages']) && $this->userHasLanguage($id)) {
                    $this->removeUserLanguage($id);
                }
            }
            else {
                $this->removeUserLanguage($id);
            }
        }
        
        foreach(Port::getMultipleObjects() as $port) {
            if(isset($data['userPorts'])) {
                if(in_array($port->getID(), $data['userPorts']) && !$this->userHasPort($port->getID())) {
                    $this->addUserToPort($port->getID());
                }
                if(!in_array($port->getID(), $data['userPorts']) && $this->userHasPort($port->getID())) {
                    $this->removeUserFromPort($port->getID());
                }
            }
            else {
                $this->removeUserFromPort($port->getID());
            }
        }
        
        return ["type" => "success"];
    }
    
    /**
     * Static Funktion die einen bestehenden Benutzer l??scht
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
        (new Query("delete"))
            ->table("port_bo_telegram")
            ->condition(["user_id" => $this->id])
            ->execute();

        $vesselContactCount = (new Query("select"))
            ->table(VesselContact::TABLE_NAME)
            ->condition(["contact_user_id" => $this->id])
            ->execute()
            ->rowCount();
        $vesselInfoCount = (new Query("select"))
            ->table(VesselInfo::TABLE_NAME)
            ->condition(["user_id" => $this->id])
            ->execute()
            ->rowCount();
        
        Logger::writeLogDelete("User", "User " . $this->first_name . " " . $this->surname . " gel??scht.");
            
        if ($vesselContactCount > 0 || $vesselInfoCount > 0) {
            $this->updateDB(["inactive" => 1], ["id" => $this->id]);
        }
        else {
            $this->deleteDB(["id" => $this->id]);
        }
    }
    
    /**
     * Static Funktion die den vollen Namen zu einer UserID liefert
     */
    public static function getUserFullName($id, $project = false) {
        $query = (new Query("select"))
            ->table(self::TABLE_NAME)
            ->condition(["id" => $id]);
        
        if($project !== false)
            $query->project($project);
            
        $row = $query->execute()->fetch();
        
        if(!empty($row)) {
            return $row['first_name'] . " " . $row['surname'];
        }
        else {
            return null;
        }
    }

    /**
     * Static Funktion die zu einem Namen die UserID zur??ckliefert
     */
    public static function getUserByFullName($name) {
        $result = User::getSingleObjectByCondition(["concat(first_name, ' ', surname)" => $name, "inactive" => 0]);
        if(!empty($result)) {
            return $result->getID();
        }
        else {
            return null;
        }
    }
   
    /**
     *  Function die dem Benutzer einen Link zum zur??cksetzen des Passwortes zuschickt
     */    
    public function sendResetPasswordLink() {
        $bytes = md5(time());
        $sqlstrg = "update port_bo_user set password_code = ?, password_code_time = NOW() where id = ?";
        DBConnect::execute($sqlstrg, array(password_hash($bytes, PASSWORD_DEFAULT), $this->id));
        
        $mail = new SendMail();
        $mail->mail->addAddress($this->email);
        $mail->mail->Subject = "Hafendienst-Backoffice - Passwort vergessen";
        $mail->applyTemplate('_passwordResetMail', array("Vorname" => $this->first_name, "LinkAdresse" => MAIN_PATH_WITH_HOST . "index.php?id=" . $this->id . "&code=" . $bytes));
        
        $mail->mail->send();
        
        Logger::writeLogInfo('user', "Der Benutzer " . User::getUserFullName($this->id) . " hat einen Link zum Zur??cksetzen des Passwortes angefordert.");
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
     * Legt f??r den User einen Zugang zum ??ZG Kalender an.
     */
    public function addKalender($kalender, $projectID) { 
        $project = Projects::getSingleObjectByID($projectID);
        
        $kalenderID = ozg::newOzgUser($this->first_name, $this->surname, $this->email, $this->phone, $kalender, $project->getModPlanningProject());
        
        if(is_numeric($kalenderID) and $kalenderID > 0) {
            $this->updateDB(["planning_id" => $kalenderID], ["id" => $this->id]);
            Logger::writeLogInfo('addKalender', 'Kalender angelegt f??r User: ' . $this->id);
        }
        else {
            Logger::writeLogError('addKalender', 'OZG Profil konnte nicht erstellt werden f??r User: ' . $this->id);
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
            ->order("p.name")
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
            $telegram = new Telegram($adminUser->getTelegramID());
            
            $telegram->applyTemplate("_userSendMessage", Array(
                "name" => User::getUserFullName($_SESSION['user']), 
                "message" => $data['message']
            ));
            
            $telegram->sendMessage(false);
        }
    }
    
    /*
     * Liefert alle User-Level die in der jeweiligen Situation vergeben werden d??rfen
     */
    public static function returnAllowedUserLevels($user, $userToEdit, $project) {
        $allowedLevel = [];
       
        foreach(self::$userLevel as $levelID => $level) {

            $editHigherLevel = (!empty($userToEdit) && $userToEdit->getLevel() > $user->getLevel());
            $lowerEqualOwn = ($levelID <= $user->getLevel());
            $allowedForeingPort = ($levelID != 2 || $user->getLevel() == 9);
            
            if(($editHigherLevel || $lowerEqualOwn) && $allowedForeingPort) {
                if(empty($project) || ($levelID == 0 || $levelID == 2 || $levelID >= 8)) {
                    $allowedLevel[$levelID] = $level;
                }
            }
        }
        return $allowedLevel;
    }

    public function checkDataprotection() {
        if(empty($this->dataprotection)) {
            return false;
        }
        else {
            return true;
        }
    }
    
    public function acceptDataprotection() {
        (new Query("update"))
        ->valuesString("dataprotection = now()")
        ->table(User::TABLE_NAME)
        ->condition(["id" => $this->id])
        ->execute();
        
        Logger::writeLogInfo("Dataprotection", "Der Benutzer " . $this->first_name . " " . $this->surname . " hat den Datenschutzbedingungen zugestimmt.");
    }
    
    private function validateNewUserInput($data = null) {
        global $t;
        
        $usernameQuery = (new Query("select"))
            ->table(self::TABLE_NAME)
            ->project(0);
        
            
        if(empty($data)) {
            $usernameQuery->condition(["username" => $this->username]);
        }
        else {
            $usernameQuery->condition(["username" => $data['userUsername']]);
            $usernameQuery->conditionNot(["id" => $this->id]);
        }
        
        $result = $usernameQuery->fetchAll(User::class);
        
        if(!empty($result))
            return array("field" => "userUsername", "msg" => $t->_get('user-already-existing'));
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
     * Static Function gibt zur??ck ob grade ein Backoffice Mitarbeiter online ist
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
    public function getActive() {
        return $this->active;
    }
    public function getProjectId() {
        return $this->project_id;
    }
    public function getForeignPort() {
        return $this->foreign_port;
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
    public function getCoordination() {
        return $this->coordination;
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
    public function getTelegramCode() {
        return $this->telegram_code;
    }
    public function getPlanningID() {
        return $this->planning_id;
    }
    public function getDefaultLanguage() {
        return $this->default_language;
    }
    public function getNotifications() {
        return $this->notifications;
    }
}

?>