<?php
namespace bo\components\classes;

use bo\components\classes\helper\DBConnect;
use bo\components\classes\helper\Logger;
use bo\components\classes\helper\Query;

class Vessel extends AbstractDBObject
{
    public const TABLE_NAME = "port_bo_vessel";
    
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
    private $vesselContactDetails = [];
    
    public $hasMail;
    public $hasPhone;
    
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
            $this->loadContactDetails();
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
            return array("type" => "error", "msg" => $msg);
        }
        else {
            $lastID = $this->insertDB([
                "name" => $this->name,
                "IMO" => $this->IMO,
                "MMSI" => $this->MMSI,
                "ENI" => $this->ENI,
                "typ" => $this->typ,
                "language" => $this->language
            ]);
            
            Logger::writeLogCreate('vessel', 'Neues Schiff anlgelegt: ' . $this->name);
            return array("type" => "added", "name" => $this->name, "imo" => $this->IMO, "id" => $lastID);
        }
    }
    
    /**
     * function editVessel()
     *
     * Bearbeiten eines existierenden Schiffes
     */
    public function editVessel() {
        if($msg = $this->validateVesselInput()) {
            return array("type" => "error", "msg" => $msg);
        }
        else {
            $this->updateDB([
                "name" => $this->name,
                "IMO" => $this->IMO,
                "MMSI" => $this->MMSI,
                "ENI" => $this->ENI,
                "typ" => $this->typ,
                "language" => $this->language
            ], ["id" => $this->id]);
            
            Vessel::setTS($this->id);
            return array("type" => "changed");
        }
    }
    
    /**
     * function loadInfo()
     *
     * Lädt die zu einem Schiff vorhandenen Informationen in $vesselInfos
     */
    private function loadInfo() {
        $this->vesselInfos = (new Query("select"))
            ->table(VesselInfo::TABLE_NAME)
            ->condition(["vess_id" => $this->id])
            ->order("ts_erf desc")
            ->fetchAll(VesselInfo::class);
    }
    
    /**
     * function loadContact()
     *
     * Lädt die zu einem Schiff vorhadenen Informationen in $vesselContacts
     */
    private function loadContact() {
        $this->vesselContacts = VesselContact::getMultipleObjects(Array("vess_id" => $this->id), "date desc", 0);
    }

    /**
     * function loadContactDetails()
     *
     * Lädt die zu dem Schiff vorhandenen Kontaktinformationen
     */
    private function loadContactDetails() {
        $this->vesselContactDetails = (new Query("select"))
            ->table(VesselContactDetails::TABLE_NAME)
            ->condition(["vessel_id" => $this->id])
            ->order("type")
            ->fetchAll(VesselContactDetails::class);
        
        foreach($this->vesselContactDetails as $contactDetail) {
            if($contactDetail->getType() == 'Email') {
                $this->hasMail = true;
            }
            if($contactDetail->getType() == 'Telefon') {
                $this->hasPhone = true;
            }
        }
        
    }
    
    /**
     * static function getVesselName($id)
     *
     * @param int $id ID des Schiffes
     * @return string Name zu der Schiffs-ID
     */
    public static function getVesselName($id) {
        $row = (new Query("select"))
            ->table(self::TABLE_NAME)
            ->condition(["id" => $id])
            ->execute()
            ->fetch();
        
        return $row['name'] ?? '';
    }

    /**
     * static function getVesselType($id)
     *
     * @param int $id ID des Schiffes
     * @return string ID des Shiffstyps
     */
    public static function getVesselType($id) {
        $row = (new Query("select"))
            ->table(self::TABLE_NAME)
            ->condition(["id" => $id])
            ->execute()
            ->fetch();

        return $row['typ'] ?? '';
    }

    /**
     * static function getLastContactVessel($id)
     *
     * @param int $id ID des Schiffes
     * @return string Datum
     */
    public static function getLastContactVessel($id) {
        $row = (new Query("select"))
            ->table(VesselContact::TABLE_NAME)
            ->condition(["vess_id" => $id]) 
            ->order("date desc")
            ->execute()
            ->fetch();
        
        return $row['date'];
    }
    
    /**
     * static function setTS($id)
     *
     * Timestamp eines Schiffes wird aktualisiert.
     *
     * @param int $id ID des Schiffes
     */
    public static function setTS($id) {
        (new Query("update"))
            ->table(self::TABLE_NAME)
            ->values(["ts_erf" => date('Y-m-d H:i:s')])
            ->condition(["id" => $id])
            ->execute();
    }
    
    private function validateVesselInput() {
        $msg = '';
        
        $sqlstrg = "select * from port_bo_vessel where ";
        if(!empty($this->id)){
            $sqlstrg .= "id != " . $this->id . " and ";
        }
        $sqlstrg .= "((IMO = ? and IMO <> '') or (ENI = ? and ENI <> ''))";
        if(DBConnect::execute($sqlstrg, array($this->IMO, $this->ENI))->rowCount() > 0) {
            $msg = array("field" => "vesselIMO", "msg" => "Es existiert bereits ein Schiff mit dieser IMO / ENI.");
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
    public function getVesselContactDetails() {
        return $this->vesselContactDetails;
    }
    public function getTsErf() {
        return $this->ts_Erf;
    }
}

?>