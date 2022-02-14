<?php
namespace bo\components\classes;

use bo\components\classes\helper\DBConnect;
use bo\components\classes\helper\Logger;

/**
 * Klasse vesselInfo
 * @author Manuel Sagorski
 *
 */
class VesselInfo extends AbstractDBObject
{
    public const TABLE_NAME = "port_bo_vesselInfo";
    
    private $id;
    private $project_id;
    private $vess_id;
    private $user_id;
    private $ts_erf;
    private $info;
    
    public function __construct($data = null) {
        if(!empty($data)) {
            $this->vess_id = $data['vesselID'];
            $this->info = $data['vesselInfo'];
        }
    }
    
    /*
     * Funktion zum Speichern einer neuen vesselInfo
     */
    public function safeInfo() {
        $this->insertDB([
            "vess_id" => $this->vess_id, 
            "user_id" => $_SESSION['user'], 
            "info" => $this->info
        ]);

        Logger::writeLogCreate('vesselInfo', 'Neue Info für das Schiff ' . Vessel::getVesselName($this->vess_id) . ' hinzugefügt. InfoText: ' . $this->info);
        Vessel::setTS($this->vess_id);
    }
    
    /*
     * Funktion zum Bearbeiten einer neuen vesselInfo
     */
    public static function editInfo($data, $infoID) {
        $sqlstrg = "update port_bo_vesselInfo set user_id = ?, ts_erf = now(), info = ? where id = ?";
        DBConnect::execute($sqlstrg, array($_SESSION['user'], $data['vesselInfo'], $infoID));
        
        Vessel::setTS($data['vesselID']);
    }
    
    /*
     * Funktion zum löschen einer vesselInfo
     */
    public function deleteInfo() {
        $this->deleteDB(["id" => $this->id]);
        Vessel::setTS($_SESSION['vessID']);
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
    public function getUser() {
        return $this->user_id;
    }
    public function getTs_erf() {
        return $this->ts_erf;
    }
    public function getInfo() {
        return $this->info;
    }
}

?>