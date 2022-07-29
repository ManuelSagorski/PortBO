<?php
namespace bo\components\controller;

use bo\components\classes\helper\Query;
use bo\components\classes\User;

class LanguageController
{
    public function __construct()
    {}    
    
    public function changeLanguage()
    {
        $_SESSION['language'] = $_POST['language'];
        setcookie('boLanguage', $_SESSION['language'], time()+(3600*24*30), '/');
        
        (new Query("update"))
        ->table(User::TABLE_NAME)
        ->values(["default_language" => $_POST['language']])
        ->condition(["id" => $_SESSION['user']])
        ->execute();
    }
    
    public function getLanguages() {
        global $t;
        echo json_encode(["default" => $t->getLanguageDefaultText(), "selected" => $t->getLanguageText()]);
    }
}

