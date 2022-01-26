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
        $vessel = vessel::getSingleObjectByID($vesselID);
        $adminUsers = user::getMultipleObjects(Array("level" => 9));
        
        foreach ($adminUsers as $adminUser) {
            $telegram = new telegram($adminUser->getTelegramID());
            
            $telegram->applyTemplate("_lookupRequest", Array("name" => user::getUserFullName($_SESSION['user']), "vesselName" => $vessel->getName(), "IMO" => $vessel->getIMO()));
            
            $telegram->sendMessage(false);
        }
    }
}

?>