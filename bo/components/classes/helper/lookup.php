<?php
namespace bo\components\classes\helper;

use bo\components\classes\user;
use bo\components\classes\vessel;

/**
 * Klasse lookup - stellt Funktionen für foreignPorts zur Verfügung 
 * @author Manuel Sagorski
 *
 */
class lookup
{
    public static function lookupRequestInformation($vesselID) {
        $vessel = dbConnect::fetchSingle("select * from port_bo_vessel where id = ?", vessel::class, array($vesselID));
        $adminUsers = dbConnect::fetchAll("select * from port_bo_user where level = ?", user::class, array(9));
        
        foreach ($adminUsers as $adminUser) {
            $telegram = new telegram($adminUser->getTelegramID());
            
            $telegram->applyTemplate("_lookupRequest", Array("name" => user::getUserFullName($_SESSION['user']), "vesselName" => $vessel->getName(), "IMO" => $vessel->getIMO()));
            
            $telegram->sendMessage(false);
        }
    }
}

?>