<?php
namespace components\classes;

class vessel
{
    private $id;
    private $name;
    private $IMO;
    private $MMSI;
    private $ENI;
    private $typ;
    private $language;
    private $ts_erf;
    private $vesselInfos = [];
    private $vesselContacts = [];
    
    /**
     * Konstruktor
     *
     * @param array $data
     * @param int $id
     */
    public function __construct($data = null, $id = null) {
        if(empty($data)) {
            $this->loadInfo();
            $this->loadContact();
        }
        else {
            $this->id       = $id;
            $this->name     = $data['vesselName'];
            $this->IMO      = $data['vesselIMO'];
            $this->MMSI     = $data['vesselMMSI'];
            $this->ENI      = $data['vesselENI'];
            $this->typ      = $data['vesselTyp'];
            $this->language = $data['vesselLanguage'];
        }
    }
    
    /**
     * function addVessel()
     *
     * Hinzufügen eines neuen Schiffes.
     */
    public function addVessel() {
        if($msg = $this->validateVesselInput()) {
            echo $msg;
        }
        else {
            $sqlstrg = "insert into port_bo_vessel (name, IMO, MMSI, ENI, typ, language) values (?, ?, ?, ?, ?, ?)";
            dbConnect::execute($sqlstrg, array($this->name, $this->IMO, $this->MMSI, $this->ENI, $this->typ, $this->language));
            
            logger::writeLogCreate('vessel', 'Neues Schiff anlgelegt: ' . $this->name);
        }
    }
    
    /**
     * function editVessel()
     *
     * Bearbeiten eines existierenden Schiffes
     */
    public function editVessel() {
        if($msg = $this->validateVesselInput()) {
            echo $msg;
        }
        else {
            $sqlstrg = "update port_bo_vessel set name = ?, IMO = ?, MMSI = ?, ENI = ?, typ = ?, language = ? where id = ?";
            dbConnect::execute($sqlstrg, array($this->name, $this->IMO, $this->MMSI, $this->ENI, $this->typ, $this->language, $this->id));
            vessel::setTS($this->id);
        }
    }
    
    /**
     * function loadInfo()
     *
     * L�dt die zu einem Schiff vorhandenen Informationen in $vesselInfos
     */
    public function loadInfo() {
        $sqlstrg = "select * from port_bo_vesselInfo where vess_id = ? order by ts_erf desc";
        $this->vesselInfos = dbConnect::fetchAll($sqlstrg, vesselInfo::class, array($this->id));
    }
    
    /**
     * function loadContact()
     *
     * L�dt die zu einem Schiff vorhadenen Informationen in $vesselContacts
     */
    public function loadContact() {
        $sqlstrg = "select * from port_bo_vesselContact where vess_id = ? order by date desc";
        $this->vesselContacts = dbConnect::fetchAll($sqlstrg, vesselContact::class, array($this->id));
    }
    
    /**
     * static function getVesselName($id)
     *
     * @param int $id ID des Schiffes
     * @return string Name zu der Schiffs-ID
     */
    public static function getVesselName($id) {
        $sqlstrg = "select * from port_bo_vessel where id = ?";
        $result = dbConnect::execute($sqlstrg, array($id));
        $row = $result->fetch();
        return $row['name'] ?? '';
    }
    
    /**
     * static function setTS($id)
     *
     * Timestamp eines Schiffes wird aktualisiert.
     *
     * @param int $id ID des Schiffes
     */
    public static function setTS($id) {
        $sqlstrg = "update port_bo_vessel set ts_Erf = ? where id = ?";
        dbConnect::execute($sqlstrg, array(date('Y-m-d H:i:s'), $id));
    }
    
    private function validateVesselInput() {
        $msg = '';
        
        $sqlstrg = "select * from port_bo_vessel where ";
        if(!empty($this->id)){
            $sqlstrg .= "id != " . $this->id . " and ";
        }
        $sqlstrg .= "IMO = ? and IMO != '' or ENI = ? and ENI != ''";
        if(dbConnect::execute($sqlstrg, array($this->IMO, $this->ENI))->rowCount() > 0) {
            $msg = "Es existiert bereits ein Schiff mit dieser IMO / ENI.";
        }
        
        if(empty($this->IMO) && empty($this->ENI)) {
            $msg = "Bitte eine IMO oder eine ENI eingeben";
        }
        
        if(empty($this->name)) {
            $msg = "Bitte den Namen des Schiffes eingeben";
        }
        
        return $msg;
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
    public function getIMO() {
        return $this->IMO;
    }
    public function getMMSI() {
        return $this->MMSI;
    }
    public function getENI() {
        return $this->ENI;
    }
    public function getTyp() {
        return $this->typ;
    }
    public function getLanguage() {
        return $this->language;
    }
    public function getVesselInfo() {
        return $this->vesselInfos;
    }
    public function getVesselContact() {
        return $this->vesselContacts;
    }
    public function getTsErf() {
        return $this->ts_Erf;
    }
}

?>