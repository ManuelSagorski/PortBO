<?php
namespace bo\components\classes;

use bo\components\classes\helper\DBConnect;
use bo\components\classes\helper\Logger;

/**
 * Klasse vesselContactDetails
 * @author Manuel Sagorski
 *
 */
class VesselContactDetails extends AbstractDBObject
{
    public const TABLE_NAME = "port_bo_vesselContactDetails";
    
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
        $this->insertDB([
            "vessel_id" => $this->vessel_id, 
            "type" => $this->type, 
            "detail" => $this->detail, 
            "info" => $this->info
        ]);
       
        Logger::writeLogCreate("vesselContactInfo", "Neue Kontaktdaten für Schiff " . Vessel::getVesselName($this->vessel_id) . " hinzugefügt. Typ: " . $this->type);
        Vessel::setTS($this->vessel_id);
    }
    
    /**
     * Ändert eine bestehende Kontaktinformation in der Datenbank
     */
    public function editContactDetail($data) {
        $this->updateDB([
            "type" => $data['contactDetailType'], 
            "detail" => $data['contactDetail'], 
            "info" => $data['contactDetailInfo'
        ]], ["id" => $this->id]);
        Vessel::setTS($_SESSION['vessID']);
    }
    
    /**
     * Löscht eine Kontaktinformation aus der Datenbank
     */
    public function deleteContactDetail() {
        $this->deleteDB(["id" => $this->id]);
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