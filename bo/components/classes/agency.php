<?php
namespace bo\components\classes;

use bo\components\classes\helper\dbConnect;
use bo\components\classes\helper\logger;

/**
 * Klasse Agency
 * @author Manuel Sagorski
 *
 */
class agency
{
    private $id;
    private $name;
    private $short;
    private $agencyPortInfo = [];
    
    /**
     * Konstruktor
     *
     * @param array $data
     * @param int $id
     */
    public function __construct($data = null, $id = null){
        if(empty($data)) {
            $this->loadAgencyPortInfo();
        }
        else {
            $this->id       = $id;
            $this->name     = $data['agencyName'];
            $this->short    = $data['agencyShort'];
        }
    }
    
    /**
     * function addAgency()
     *
     * Hinzufügen einer neuen Agentur
     */
    public function addAgency() {
        if($msg = $this->validateNewAgencyInput()) {
            return array("type" => "error", "msg" => $msg);
        }
        else {
            $sqlstrg = "insert into port_bo_agency (name, short) values (?, ?)";
            dbConnect::execute($sqlstrg, array($this->name, $this->short));
            
            logger::writeLogCreate('agency', 'Neue Agentur angelegt: ' . $this->name);
            return array("type" => "added", "name" => $this->name);
        }
    }

    /**
     * function editAgency()
     *
     * Ändern der Daten einer Agentur
     */
    public function editAgency() {
        if($msg = $this->validateNewAgencyInput()) {
            return array("type" => "error", "msg" => $msg);
        }
        else {
            $sqlstrg = "update port_bo_agency set name = ?, short = ? where id = ?";
            dbConnect::execute($sqlstrg, array($this->name, $this->short, $this->id));
            
            logger::writeLogCreate('agency', 'Agentur bearbeitet: ' . $this->name);
            return array("type" => "changed");
        }
    }
    
    /**
     * function getAgentName($id)
     *
     * Liefert den Namen zu einer AgentID
     *
     * @param int $id
     * @return string
     */
    public static function getAgentName($id) {
        $sqlstrg = "select * from port_bo_agency where id = ?";
        $result = dbConnect::execute($sqlstrg, array($id));
        $row = $result->fetch();
        return $row['name'] ?? '';
    }
    
    /**
     * function getAgentShort($id)
     *
     * Liefert die Abkürzung zu einer AgentID
     *
     * @param int $id
     * @return string
     */
    public static function getAgentShort($id) {
        $sqlstrg = "select * from port_bo_agency where id = ?";
        $result = dbConnect::execute($sqlstrg, array($id));
        $row = $result->fetch();
        return $row['short'] ?? '';
    }
    
    /**
     * function getAgentID($name)
     *
     * Liefert die ID zu dem Namen eines Agenten
     *
     * @param string $name
     * @return string
     */
    public static function getAgentID($name) {
        $sqlstrg = "select * from port_bo_agency where name = ?";
        $result = dbConnect::execute($sqlstrg, array($name));
        $row = $result->fetch();
        return $row['id'] ?? '0';
    }
    
    /**
     * static function setTS($id)
     *
     * Timestamp einer Agency wird aktualisiert.
     *
     * @param int $id ID der Agency
     */
    public static function setTS($id) {
        $sqlstrg = "update port_bo_agency set ts_erf = ? where id = ?";
        dbConnect::execute($sqlstrg, array(date('Y-m-d H:i:s'), $id));
    }
    
    public static function getLastContactToAgent($agencyID, $portID) {
        $sqlstrg = "select * from port_bo_vesselContact where agent_id = ? and port_id = ? and date<CURDATE() order by date desc limit 1";
        $result = dbConnect::execute($sqlstrg, array($agencyID, $portID));
        $row = $result->fetch();
        return $row['date'] ?? '-';
    }
    
    private function loadAgencyPortInfo() {
        $sqlstrg = "select * from port_bo_agencyPortInfo where agency_id = ? order by port_id";
        $this->agencyPortInfo = dbConnect::fetchAll($sqlstrg, agencyPortInfo::class, array($this->id));
    }
    
    private function validateNewAgencyInput() {
        $sqlstrg = "select * from port_bo_agency where name = ?";
        if(!empty($this->id)){
            $sqlstrg .= " and id != " . $this->id;
        }
        if(dbConnect::execute($sqlstrg, array($this->name))->rowCount() > 0) {
            return array("field" => "agencyName", "msg" => "Es existiert bereits ein Agent mit diesem Namen.");
        }

        $sqlstrg = "select * from port_bo_agency where short = ?";
        if(!empty($this->id)){
            $sqlstrg .= " and id != " . $this->id;
        }
        if(dbConnect::execute($sqlstrg, array($this->short))->rowCount() > 0) {
            return array("field" => "agencyShort", "msg" => "Es existiert bereits ein Agent mit diesem Kürzel.");
        }
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
    public function getShort() {
        return $this->short;
    }
    public function getAgencyPortInfo() {
        return $this->agencyPortInfo;
    }
}

?>