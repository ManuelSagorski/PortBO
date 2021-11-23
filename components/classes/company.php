<?php
namespace components\classes;

use JsonSerializable;

class company implements JsonSerializable
{
    private $id;
    private $name;
    private $port_id;
    private $info;
    private $mtLink;
    private $pmLink;
    
    public function __construct($data = null) {
        if(!empty($data)) {
            $this->name = $data['companyName'];
            $this->port_id = $data['companyPortID'];
            $this->info = $data['companyInfo'];
            $this->mtLink = $data['companyMTLink'];
            $this->pmLink = $data['companyPMLink'];
        }
    }
    
    /*
     * Funktion die einen neuen Liegeplatz anlegt
     */
    public function addCompany() {
        $sqlstrg = "insert into port_bo_company (name, port_id, info, mtLink, pmLink) values (?, ?, ?, ?, ?)";
        dbConnect::execute($sqlstrg, array($this->name, $this->port_id, $this->info, $this->mtLink, $this->pmLink));
        
        logger::writeLogCreate('company', 'Neuer Liegeplatz angelegt: ' . $this->name);
    }
    
    /*
     * Static Funktion zum Bearbeiten eines Liegeplatzes
     */
    public static function editCompany($data, $id) {
        $sqlstrg = "update port_bo_company set name = ?, info = ?, mtLink = ?, pmLink = ? where id = ?";
        dbConnect::execute($sqlstrg, array($data['companyName'], $data['companyInfo'], $data['companyMTLink'], $data['companyPMLink'], $id));
    }
    
    /*
     * Static Funktion die einen Liegeplatz löscht
     */
    public static function deleteCompany($id) {
        $sqlstrg = "delete from port_bo_company where id = ?";
        dbConnect::execute($sqlstrg, array($id));
    }
    
    /*
     * Static Funktion die den Namen zu einer LiegeplatzID liefert
     */
    public static function getCompanyName($id) {
        $sqlstrg = "select * from port_bo_company where id = ?";
        $result = dbConnect::execute($sqlstrg, array($id));
        $row = $result->fetch();
        return $row['name'] ?? '';
    }
    
    /*
     Getter und Setter
     */
    public function getID() {
        return $this->id;
    }
    public function getName() {
        return $this->name;
    }
    public function getPortID() {
        return $this->port_id;
    }
    public function getInfo() {
        return $this->info;
    }
    public function getMTLink() {
        return $this->mtLink;
    }
    public function getPMLink() {
        return $this->pmLink;
    }
    
    public function jsonSerialize() {
        return [
            'companyName' => $this->name,
            'companyInfo' => $this->info,
            'portName' => port::getPortName($this->port_id),
            'companyMTLink' => $this->mtLink,
            'companyPMLink' => $this->pmLink
        ];
    }
}

?>