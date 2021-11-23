<?php
namespace components\classes;

use JsonSerializable;

class agencyPortInfo implements JsonSerializable
{
    private $id;
    private $agency_id;
    private $port_id;
    private $info;
    private $email;
    
    public function __construct()
    {}
    
    public static function addAgencyPortInfo($agencyPortInfoData) {
        $sqlstrg = "insert into port_bo_agencyPortInfo (agency_id, port_id, info, email) values (?, ?, ?, ?)";
        dbConnect::execute($sqlstrg, array($_SESSION['agencyID'], $agencyPortInfoData['contactPort'],
            $agencyPortInfoData['agencyContactInfo'], $agencyPortInfoData['agencyContactEmail']));
        agency::setTS($_SESSION['agencyID']);
    }
    
    public static function deleteAgencyPortInfo($id) {
        $sqlstrg = "delete from port_bo_agencyPortInfo where id = ?";
        dbConnect::execute($sqlstrg, array($id));
        agency::setTS($id);
    }
    
    public static function editAgencyPortInfo($agencyPortInfoData, $id) {
        $sqlstrg = "update port_bo_agencyPortInfo set port_id = ?, info = ?, email = ? where id = ?";
        dbConnect::execute($sqlstrg, array($agencyPortInfoData['contactPort'], $agencyPortInfoData['agencyContactInfo'],
            $agencyPortInfoData['agencyContactEmail'], $id));
        agency::setTS($id);
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
            'agencyName' => agency::getAgentName($this->agency_id),
            'portName' => port::getPortName($this->port_id),
            'info' => $this->info,
            'email' => $this->email,
            'lastContact' => date("d.m.Y", strtotime(agency::getLastContactToAgent($this->agency_id, $this->port_id)))
        ];
    }
}

?>