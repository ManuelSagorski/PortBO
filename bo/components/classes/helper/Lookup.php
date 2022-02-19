<?php
namespace bo\components\classes\helper;

use bo\components\classes\User;
use bo\components\classes\Vessel;

/**
 * Klasse lookup - stellt Funktionen für foreignPorts zur Verfügung 
 * @author Manuel Sagorski
 *
 */
class Lookup
{
    public static function lookupRequestInformation($vesselID) {
        $vessel = Vessel::getSingleObjectByID($vesselID);
        $adminUsers = User::getMultipleObjects(Array("level" => 9));
        
        foreach ($adminUsers as $adminUser) {
            $telegram = new Telegram($adminUser->getTelegramID());
            
            $telegram->applyTemplate("_lookupRequest", Array("name" => User::getUserFullName($_SESSION['user']), "vesselName" => $vessel->getName(), "IMO" => $vessel->getIMO()));
            
            $telegram->sendMessage(false);
        }
    }
}

?>