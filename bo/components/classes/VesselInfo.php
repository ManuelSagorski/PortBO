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
    
    public function __construct() {
    }
    
    /*
     * Funktion zum Speichern einer neuen vesselInfo
     */
    public static function safeInfo($data) {
        $sqlstrg = "insert into port_bo_vesselInfo (project_id, vess_id, user_id, ts_erf, info) values (?, ?, ?, now(), ?)";
        DBConnect::execute($sqlstrg, array($_SESSION['project'], $data['vesselID'], $_SESSION['user'], $data['vesselInfo']));
        
        Logger::writeLogCreate('vesselInfo', 'Neue Info für das Schiff ' . Vessel::getVesselName($data['vesselID']) . ' hinzugefügt. InfoText: ' . $data['vesselInfo']);
        Vessel::setTS($data['vesselID']);
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
    public static function deleteInfo($id) {
        $sqlstrg = "delete from port_bo_vesselInfo where id = ?";
        DBConnect::execute($sqlstrg, array($id));
        
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