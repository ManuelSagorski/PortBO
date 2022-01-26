<?php
namespace bo\components\classes;

use bo\components\classes\helper\dbConnect;
use bo\components\classes\helper\logger;

class vesselContact extends abstractDBObject
{
    protected static $tableName = "port_bo_vesselContact";
    
    private $id;
    private $vess_id;
    private $user_id;
    private $agent_id;
    private $port_id;
    private $contact_type;
    private $contact_name;
    private $contactUserID;
    private $info;
    private $date;
    private $planned;
    
    private $inputData;
    private $vesselContactMail = [];
    
    public function __construct($data = null) {
        if(!empty($data)) {
            $this->vess_id      = $_SESSION['vessID'];
            $this->user_id      = $_SESSION['user'];
            $this->agent_id     = agency::getAgentID($data['contactAgent']);
            $this->port_id      = $data['contactPort'];
            $this->contact_type = $data['contactType'];
            $this->contact_name = $data['contactName'];
            $this->contactUserID= user::getUserByFullName($data['contactName']);
            $this->info         = $data['contactInfo'];
            $this->date         = $data['contactDate'];
            if(!isset($data['contactPlanned'])) {
                $this->planned  = 0; }
            else {
                $this->planned  = 1; 
            }
            
            $this->inputData = $data;
        }
        else {
            $this->vesselContactMail = dbConnect::fetchAll("select * from port_bo_vesselContactMail where contact_id = ?", vesselContactMail::class, Array($this->id));
        }
    }
    
    /**
     * function addContact()
     *
     * Hinzufügen eines neuen Kontaktes für ein Schiff
     */
    public function addContact() {
        if ($msg = $this->validateContactInput()) {
            return array("status" => "error", "msg" => $msg);
        }
        else {
            $sqlstrg = "insert into port_bo_vesselContact
                            (vess_id, user_id, contact_type, contact_name, info, date, agent_id, port_id, planned)
                        values
                            (?, ?, ?, ?, ?, ?, ?, ?, ?)";
            dbConnect::execute($sqlstrg, array($this->vess_id, $this->user_id, $this->contact_type, $this->contact_name, $this->info, $this->date,
                $this->agent_id, $this->port_id, $this->planned));
            
            logger::writeLogCreate('vesselContact', 'Neuen Kontakt für Schiff ' . vessel::getVesselName($this->vess_id) . ' hinzugefügt. InfoText: ' . $this->info);
            vessel::setTS($_SESSION['vessID']);
            if(!empty($this->agent_id)) {
                agency::setTS($this->agent_id);
            }
        }
    }
    
    /*
     * Funktion zum Ändern eines vesselContacts
     */
    public function editContact($contactData) {        
        $this->agent_id     = agency::getAgentID($contactData['contactAgent']);
        $this->contactUserID= user::getUserByFullName($contactData['contactName']);
        $this->inputData = $contactData;
        
        if ($msg = $this->validateContactInput()) {
            return array("status" => "error", "msg" => $msg);
        }
        
        if(!isset($contactData['contactPlanned'])) {
            $planned  = 0; 
        }
        else {
            $planned  = 1; 
        }
                
        $sqlstrg = "update port_bo_vesselContact
               set user_id = ?,
                   contact_type = ?,
                   contact_name = ?,
                   info = ?,
                   date = ?,
                   agent_id = ?,
                   port_id = ?,
                   planned = ?
             where id = ?";
        dbConnect::execute($sqlstrg, array($_SESSION['user'], $contactData['contactType'], $contactData['contactName'], $contactData['contactInfo'],
            $contactData['contactDate'], agency::getAgentID($contactData['contactAgent']), $contactData['contactPort'], $planned, $this->id));
        
        logger::writeLogInfo('vesselContact', 'Kontakt für Schiff ' . vessel::getVesselName($this->vess_id) . ' bearbeitet. InfoText: ' . $contactData['contactInfo']);
        
        vessel::setTS($_SESSION['vessID']);
        
        return array("status" => "success");
    }
    
    public static function getOpenContactsForUser($userID) {
        $sqlstrg = "select vc.*
                      from port_bo_vesselContact vc left join port_bo_userToPort up on vc.port_id = up.port_id
                     where vc.planned = 1
                       and up.user_id = ?
                     order by vc.port_id, vc.date";
        return dbConnect::fetchAll($sqlstrg, vesselContact::class, array($userID));
    }
    
    /*
     * Funktion zum Löschen eines vesselContacts
     */
    public static function deleteContact($id) {
        $sqlstrg = "delete from port_bo_vesselContact where id = ?";
        dbConnect::execute($sqlstrg, array($id));
        
        vessel::setTS($_SESSION['vessID']);
    }
    
    private function validateContactInput() {
        if($this->inputData['contactAgent'] != '' && $this->agent_id == 0) {
            return array("field" => "Agent", "msg" => "Der eingegebene Agent existiert nicht in der Datenbank. Bitte lege zuerst den Agenten an.");
        }

        if($this->inputData['contactName'] != '' && $this->contactUserID == 0) {
            return array("field" => "User", "msg" => "Der eingegebene Benutzer existiert nicht in der Datenbank. Bitte lege zuerst den Benutzer an.");
        }
    }
    
    /*
     Getter und Setter
     */
    public function getID() {
        return $this->id;
    }
    public function getVesselID() {
        return $this->vess_id;
    }
    public function getUserID() {
        return $this->user_id;
    }
    public function getAgentID() {
        return $this->agent_id;
    }
    public function getPortID() {
        return $this->port_id;
    }
    public function getContactType() {
        return $this->contact_type;
    }
    public function getContactName() {
        return $this->contact_name;
    }
    public function getInfo() {
        return $this->info;
    }
    public function getDate() {
        return $this->date;
    }
    public function getPlanned() {
        return $this->planned;
    }
    public function getVesselContactMail() {
        return $this->vesselContactMail;
    }  
}

?>