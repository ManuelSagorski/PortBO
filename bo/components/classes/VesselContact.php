<?php
namespace bo\components\classes;

use bo\components\classes\helper\Logger;
use bo\components\classes\helper\Query;

class VesselContact extends AbstractDBObject
{
    public const TABLE_NAME = "port_bo_vesselContact";
    
    private $id;
    private $project_id;
    private $vess_id;
    private $user_id;
    private $agent_id;
    private $company_id;
    private $port_id;
    private $contact_type;
    private $contact_name;
    private $contact_user_id;
    private $contactUserID;
    private $date;
    private $planned;
    private $month_next;
    
    private $inputData;
    private $contactUser;
    
    public static $monthNext = [0 => 'not-specified', 1 => 'immediately', 3 => '3months', 6 => '6months'];
    
    public function __construct($data = null) {
        if(!empty($data)) {
            $this->vess_id      = $_SESSION['vessID'];
            $this->user_id      = $_SESSION['user'];
            $this->agent_id     = Agency::getAgentID($data['contactAgent']);
            $this->company_id   = Company::getCompanyByName($data['contactCompany']);
            $this->port_id      = $data['contactPort'];
            $this->contact_type = $data['contactType'];
            $this->contact_name = $data['contactName'];
            $this->contactUserID= User::getUserByFullName($data['contactName']);
            $this->date         = $data['contactDate'];
            $this->month_next   = $data['contactNext'];
            if(!isset($data['contactPlanned'])) {
                $this->planned  = 0; }
            else {
                $this->planned  = 1; 
            }
            
            $this->inputData = $data;
        }
        else {
            $this->contactUser = User::getSingleObjectByID($this->contact_user_id, 0);
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
            $this->insertDB([
                "vess_id" => $this->vess_id,
                "user_id" => $this->user_id,
                "contact_type" => $this->contact_type,
                "contact_name" => $this->contact_name,
                "contact_user_id" => User::getUserByFullName($this->contact_name),
                "date" => $this->date,
                "agent_id" => $this->agent_id,
                "company_id" => $this->company_id,
                "port_id" => $this->port_id,
                "planned" => $this->planned,
                "month_next" => $this->month_next
            ]);
            
            Logger::writeLogCreate('vesselContact', 'Neuen Kontakt für Schiff ' . Vessel::getVesselName($this->vess_id) . ' hinzugefügt.');
            Vessel::setTS($_SESSION['vessID']);
            if(!empty($this->agent_id)) {
                Agency::setTS($this->agent_id);
            }
        }
    }
    
    /*
     * Funktion zum Ändern eines vesselContacts
     */
    public function editContact($data) {        
        $this->agent_id     = Agency::getAgentID($data['contactAgent']);
        $this->contactUserID = User::getUserByFullName($data['contactName']);
        $this->company_id = Company::getCompanyByName($data['contactCompany']);
        $this->inputData = $data;
        
        if ($msg = $this->validateContactInput())
            return array("status" => "error", "msg" => $msg);
        
        if(!isset($data['contactPlanned'])) {
            $planned  = 0; 
        }
        else {
            $planned  = 1; 
        }
        
        $this->updateDB([
            "user_id" => $_SESSION['user'],
            "contact_type" => $data['contactType'],
            "contact_name" => $data['contactName'],
            "contact_user_id" => User::getUserByFullName($data['contactName']),
            "date" => $data['contactDate'],
            "agent_id" => Agency::getAgentID($data['contactAgent']),
            "company_id" => Company::getCompanyByName($data['contactCompany']),
            "port_id" => $data['contactPort'],
            "planned" => $planned,
            "month_next" => $data['contactNext']
        ], ["id" => $this->id]);
       
        Logger::writeLogInfo('vesselContact', 'Kontakt für Schiff ' . Vessel::getVesselName($this->vess_id) . ' bearbeitet.');
        Vessel::setTS($_SESSION['vessID']);
        
        return array("status" => "success");
    }
    
    public static function getOpenContactsForUser($userID) {
        return (new Query("select"))
            ->distinct()
            ->fields("vc.*")
            ->table(self::TABLE_NAME, "vc")
            ->leftJoin(UserToPort::TABLE_NAME, "up", "port_id", "port_id")
            ->condition(["vc.planned" => 1])
            ->conditionString(["vc.contact_user_id = ? or up.user_id = ?" => [$userID, $userID]])
            ->order("vc.port_id, vc.date")
            ->fetchAll(self::class);
    }
    
    /*
     * Funktion zum Löschen eines vesselContacts
     */
    public function deleteContact() {
        $this->deleteDB(["id" => $this->id]);
        Vessel::setTS($_SESSION['vessID']);
    }
    
    private function validateContactInput() {
        global $t;
        
        if($this->inputData['contactName'] != '' && $this->contactUserID == 0) {
            return array("field" => "contactName", "msg" => $t->_('user-not-existing'));
        }

        if($this->inputData['contactAgent'] != '' && $this->agent_id == 0) {
            return array("field" => "contactAgent", "msg" => $t->_('agent-not-existing'));
        }
        
        if($this->inputData['contactCompany'] != '' && $this->company_id == 0) {
            return array("field" => "contactCompany", "msg" => $t->_('company-not-existing'));
        }
    }
    
    /*
     Getter und Setter
     */
    public function getID() {
        return $this->id;
    }
    public function getProjectId() {
        return $this->project_id;
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
    public function getCompanyID() {
        return $this->company_id;
    }
    public function getPortID() {
        return $this->port_id;
    }
    public function getContactType() {
        return $this->contact_type;
    }
    public function getContactUserID() {
        return $this->contact_user_id;
    }
    public function getContactUser() {
        return $this->contactUser;
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
    public function getMonthNext() {
        return $this->month_next;
    }
}

?>