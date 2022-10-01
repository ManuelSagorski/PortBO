<?php
namespace bo\interfaces;

use bo\components\classes\UserToLanguage;
use bo\components\classes\helper\Query;
use bo\components\types\Languages;

$independent = true;
include '../components/config.php';

$userLanguages = UserToLanguage::getMultipleObjects();

foreach ($userLanguages as $oneRow) {
    (new Query('update'))
        ->table(UserToLanguage::TABLE_NAME)
        ->values(['language_id' => Languages::$translate[$oneRow->getLanguageID()]])
        ->condition(['id' => $oneRow->getID()])
        ->execute();
    
     echo $oneRow->getLanguageID() . " -> " . Languages::$translate[$oneRow->getLanguageID()] . "<br>";
}