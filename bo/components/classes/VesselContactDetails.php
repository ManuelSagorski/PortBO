<?php
namespace bo\components\classes;

use bo\components\classes\helper\Logger;
use bo\components\classes\helper\Query;

/**
 * Klasse vesselContactDetails
 * @author Manuel Sagorski
 *
 */
class VesselContactDetails extends AbstractDBObject
{
    public const TABLE_NAME = "port_bo_vesselContactDetails";

    public const TYPE_TRANSLATION_KEYS = [
        'Email' => 'email-address',
        'Telefon' => 'phone-number',
    ];
    
    private $id;
    private $project_id;
    private $vessel_id;
    private $type;
    private $detail;
    private $info;
    private $invalid;
    private $supposed;
    
    public static $contactDetailTypes = array("Email", "Telefon");
    
    public function __construct($data = null) {
        if(!empty($data)) {
            $this->vessel_id    = $_SESSION['vessID'];
            $this->type         = $data['contactDetailType'];
            $this->detail       = $data['contactDetail'];
            // $this->info         = $data['contactDetailInfo'];
        }
    }
    
    /**
     * Speichert eine Kontaktinformation in der Datenbank
     */
    public function addContactDetail() {
        global $project;
        
        $this->insertDB([
            "project_id" => ($project->getContactDetailsSeparated() == 0)?0:$project->getID(),
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
            "detail" => $data['contactDetail'] 
        ], ["id" => $this->id]);
        Vessel::setTS($_SESSION['vessID']);
    }
    
    /**
     * Löscht eine Kontaktinformation aus der Datenbank
     */
    public function deleteContactDetail() {
        $this->deleteDB(["id" => $this->id]);
        Vessel::setTS($_SESSION['vessID']);
    }

    /**
     * Kontaktinformation als nicht bestätigt markieren
     */
    public function toggleSupposed() {
        (new Query("update"))
            ->table(self::TABLE_NAME)
            ->valuesString("supposed = !supposed")
            ->condition(["id" => $this->id])
            ->execute();

        Vessel::setTS($_SESSION['vessID']);
    }

    /**
     * Kontaktinformation als ungültig markieren
     */
    public function toggleInvalid() {
        (new Query("update"))
            ->table(self::TABLE_NAME)
            ->valuesString("invalid = !invalid")
            ->condition(["id" => $this->id])
            ->execute();

        Vessel::setTS($_SESSION['vessID']);
    }
   
    /*
     Getter und Setter
     */
    public function getID() {
        return $this->id;
    }
    public function getProjectID() {
        return $this->project_id;
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
    public function getInvalid() {
        return $this->invalid;
    }
    public function getSupposed() {
        return $this->supposed;
    }
}

?>