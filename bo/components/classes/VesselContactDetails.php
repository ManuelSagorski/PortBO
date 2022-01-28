<?php
namespace bo\components\classes;

use bo\components\classes\helper\DBConnect;
use bo\components\classes\helper\Logger;

/**
 * Klasse vesselContactDetails
 * @author Manuel Sagorski
 *
 */
class VesselContactDetails
{
    private $id;
    private $vessel_id;
    private $type;
    private $detail;
    private $info;
    
    public static $contactDetailTypes = array("Email", "Telefon", "Sonstiges");
    
    public function __construct($data = null) {
        if(!empty($data)) {
            $this->vessel_id    = $_SESSION['vessID'];
            $this->type         = $data['contactDetailType'];
            $this->detail       = $data['contactDetail'];
            $this->info         = $data['contactDetailInfo'];
        }
    }
    
    /**
     * Speichert eine Kontaktinformation in der Datenbank
     */
    public function addContactDetail() {
        $sqlstrg = "insert into port_bo_vesselContactDetails (vessel_id, type, detail, info) values (?, ?, ?, ?)";
        DBConnect::execute($sqlstrg, Array($this->vessel_id, $this->type, $this->detail, $this->info));
        
        Logger::writeLogCreate("vesselContactInfo", "Neue Kontaktdaten für Schiff " . Vessel::getVesselName($this->vessel_id) . " hinzugefügt. Typ: " . $this->type);
        
        Vessel::setTS($this->vessel_id);
    }
    
    /**
     * Ändert eine bestehende Kontaktinformation in der Datenbank
     */
    public static function editContactDetail($data, $contactDetailID) {
        $sqlstrg = "update port_bo_vesselContactDetails set type = ?, detail = ?, info = ? where id = ?";
        DBConnect::execute($sqlstrg, Array($data['contactDetailType'], $data['contactDetail'], $data['contactDetailInfo'], $contactDetailID));
        
        Vessel::setTS($_SESSION['vessID']);
    }
    
    /**
     * Löscht eine Kontaktinformation aus der Datenbank
     */
    public static function deleteContactDetail($contactDetailID) {
        $sqlstrg = "delete from port_bo_vesselContactDetails where id = ?";
        DBConnect::execute($sqlstrg, Array($contactDetailID));
        
        Vessel::setTS($_SESSION['vessID']);
    }
    
    /*
     Getter und Setter
     */
    public function getID() {
        return $this->id;
    }
    public function getVesselID() {
        return $this->vessel_id;
    }
    public function getType() {
        return $this->type;
    }
    public function getDetail() {
        return $this->detail;
    }
    public function getInfo() {
        return $this->info;
    }
}

?>