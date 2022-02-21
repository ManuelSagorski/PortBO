<?php
namespace bo\interfaces;

use bo\components\classes\helper\Logger;
use bo\components\classes\helper\Telegram;
use bo\components\classes\User;

$independent = true;
include '../components/config.php';

$telegram = new telegram(null, json_decode(file_get_contents('php://input'), true));
Logger::writeLogInfo('telegramBot', 'Eingehende Nachricht: ' . $telegram->getMessage());

$userTelegram = User::getSingleObjectByCondition(Array("telegram_id" => $telegram->getChatID()));

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
    
    if(!isset($sent) && !User::isBoUserActive()) {
        $telegram->sendMessage(false, "Hallo " . $userTelegram->getFirstName() .
            ". Derzeit ist kein Back-Office Mitarbeiter online. Wir werden dir so schnell wie möglich antworten. " .
            "Um eine Übersicht der möglichen Befehle zu erhalten, sende bitte '/hilfe'.");
    }
}

?>