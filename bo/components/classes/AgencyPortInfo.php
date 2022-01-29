<?php
namespace bo\components\classes;

use JsonSerializable;

class AgencyPortInfo extends AbstractDBObject implements JsonSerializable
{
    public const TABLE_NAME = "port_bo_agencyPortInfo";
    
    private $id;
    private $agency_id;
    private $port_id;
    private $info;
    private $email;
    
    public function __construct($data = null) {
        if(!empty($data)) {
            $this->agency_id = $_SESSION['agencyID'];
            $this->port_id = $data['contactPort'];
            $this->info = $data['agencyContactInfo'];
            $this->email = $data['agencyContactEmail'];
        }
    }
    
    public function addAgencyPortInfo() {
        $this->insertDB([
            "agency_id" => $this->agency_id, 
            "port_id" => $this->port_id, 
            "info" => $this->info, 
            "email" => $this->email
        ]);
        
        Agency::setTS($_SESSION['agencyID']);
    }
    
    public function deleteAgencyPortInfo() {
        $this->deleteDB(["id" => $this->id]);
    }
    
    public function editAgencyPortInfo($data) {
        $this->updateDB([
            "port_id" => $data['contactPort'], 
            "info" => $data['agencyContactInfo'], 
            "email" => $data['agencyContactEmail']
        ], ["id" => $this->id]);

        Agency::setTS($this->id);
    }
    
    /*
     Getter und Setter
     */
    public function getID() {
        return $this->id;
    }
    public function getAgencyID() {
        return $this->agency_id;
    }
    public function getPortID() {
        return $this->port_id;
    }
    public function getInfo() {
        return $this->info;
    }
    public function getEmail() {
        return $this->email;
    }
    
    public function jsonSerialize() {
        return [
            'agencyName' => Agency::getAgentName($this->agency_id),
            'portName' => Port::getPortName($this->port_id),
            'info' => $this->info,
            'email' => $this->email,
            'lastContact' => date("d.m.Y", strtotime(Agency::getLastContactToAgent($this->agency_id, $this->port_id)))
        ];
    }
}

?>