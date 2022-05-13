<?php
namespace bo\components;

use bo\components\classes\helper\DBConnect;
use bo\components\classes\helper\Logger;
use bo\components\classes\User;
use bo\components\classes\Projects;
use bo\components\classes\helper\Security;
use bo\components\classes\helper\Text;

session_start();

error_reporting(-1);
ini_set('display_errors', 'On');

define("FOLDER", 'bo/');
define("MAIN_PATH", "/" . FOLDER);
define("PUBLIC_PATH", "/" . FOLDER . "public/");
define("MAIN_DOCUMENT_PATH", $_SERVER["DOCUMENT_ROOT"] . FOLDER);

require_once(MAIN_DOCUMENT_PATH . 'components/configCredentials.php');

spl_autoload_register(function($class) {
    $class_name = explode('\\', $class);
    $classFolders = array("classes", "classes/PHPMailer", "classes/forecast", "classes/helper", "types", "controller");
    
    foreach($classFolders as $folder) {
        $file = MAIN_DOCUMENT_PATH . 'components/' . $folder . '/' . str_replace('\\', '/', $class_name[count($class_name)-1]) . '.php';
        if(file_exists($file)) {
            require_once($file);
            break;
        }
    }
});

DBConnect::initDB();
Security::sessionDuration(3600);
Logger::setErrorReporting();

$t = new Text();

if($_SERVER[ 'SCRIPT_NAME' ] != "/" . FOLDER . "index.php" && !isset($independent)) {
    if(!isset($_SESSION['user'])) {
        if($_SERVER[ 'SCRIPT_NAME' ] == "/" . FOLDER . "public/index.php") {
            header('Location: ' . MAIN_PATH . 'index.php');
            exit;
        }
        else {
            echo "<script>location.href='" . MAIN_PATH . "index.php';</script>";
            exit;
        }
    }
    else {
        $user = User::getSingleObjectByID($_SESSION['user']);
        $project = Projects::getSingleObjectByID($_SESSION['project']);
    }
}
?>