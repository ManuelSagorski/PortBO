<?php
namespace bo\components\controller;

use bo\components\classes\helper\dbConnect;
use bo\components\classes\helper\logger;
use bo\components\classes\helper\telegram;
use bo\components\classes\user;

$independent = true;
include '../config.php';

$telegram = new telegram(null, json_decode(file_get_contents('php://input'), true));
logger::writeLogInfo('telegramBot', 'Eingehende Nachricht: ' . $telegram->getMessage());

$userTelegram = dbConnect::fetchSingle("select * from port_bo_user where telegram_id = ?", user::class, array($telegram->getChatID()));

if(empty($userTelegram)) {
    $telegram->register();
    
    telegram::logMessage('recived', null, $telegram->getChatID(), null, $telegram->getMessage());
}
else {    
    telegram::logMessage('recived', $userTelegram->getId(), $telegram->getChatID(), null, $telegram->getMessage());
    
    if(stripos($telegram->getMessage(), '/hilfe') === 0)
    {
        $sent = true;
        $telegram->applyTemplate('_telegramHelp', array());
        $telegram->sendMessage(false,false);
    }
    
    if(!isset($sent) && !user::isBoUserActive()) {
        $telegram->sendMessage(false, "Hallo " . $userTelegram->getFirstName() .
            ". Derzeit ist kein Back-Office Mitarbeiter online. Wir werden dir so schnell wie möglich antworten. " .
            "Um eine Übersicht der möglichen Befehle zu erhalten, sende bitte '/hilfe'.");
    }
}

?>