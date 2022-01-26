<?php
namespace bo\components\classes;

use bo\components\classes\helper\dbConnect;
use bo\components\classes\helper\logger;

/**
 * Klasse vesselInfo
 * @author Manuel Sagorski
 *
 */
class vesselInfo
{
    private $id;
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
        $sqlstrg = "insert into port_bo_vesselInfo (vess_id, user_id, ts_erf, info) values (?, ?, now(), ?)";
        dbConnect::execute($sqlstrg, array($data['vesselID'], $_SESSION['user'], $data['vesselInfo']));
        
        logger::writeLogCreate('vesselInfo', 'Neue Info für das Schiff ' . vessel::getVesselName($data['vesselID']) . ' hinzugefügt. InfoText: ' . $data['vesselInfo']);
        vessel::setTS($data['vesselID']);
    }
    
    /*
     * Funktion zum Bearbeiten einer neuen vesselInfo
     */
    public static function editInfo($data, $infoID) {
        $sqlstrg = "update port_bo_vesselInfo set user_id = ?, ts_erf = now(), info = ? where id = ?";
        dbConnect::execute($sqlstrg, array($_SESSION['user'], $data['vesselInfo'], $infoID));
        
        vessel::setTS($data['vesselID']);
    }
    
    /*
     * Funktion zum löschen einer vesselInfo
     */
    public static function deleteInfo($id) {
        $sqlstrg = "delete from port_bo_vesselInfo where id = ?";
        dbConnect::execute($sqlstrg, array($id));
        
        vessel::setTS($_SESSION['vessID']);
    }
    
    /*
     Getter und Setter
     */
    public function getID() {
        return $this->id;
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