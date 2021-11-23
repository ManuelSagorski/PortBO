<?php
namespace components\classes;

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
    public static function safeInfo($info) {
        $sqlstrg = "insert into port_bo_vesselInfo (vess_id, user_id, ts_erf, info) values (?, ?, now(), ?)";
        dbConnect::execute($sqlstrg, array($_SESSION['vessID'], $_SESSION['user'], $info));
        
        logger::writeLogCreate('vesselInfo', 'Neue Info für das Schiff ' . vessel::getVesselName($_SESSION['vessID']) . ' hinzugefügt. InfoText: ' . $info);
        vessel::setTS($_SESSION['vessID']);
    }
    
    /*
     * Funktion zum Bearbeiten einer neuen vesselInfo
     */
    public static function editInfo($info, $infoID) {
        $sqlstrg = "update port_bo_vesselInfo set user_id = ?, ts_erf = now(), info = ? where id = ?";
        dbConnect::execute($sqlstrg, array($_SESSION['user'], $info, $infoID));
        
        vessel::setTS($_SESSION['vessID']);
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