<?php
namespace components;

session_start();

/*
 * Errorhandling
 */
error_reporting(-1);
ini_set('display_errors', 'On');

/*
 * Pfadangaben
 */
define("FOLDER",'boNew');
define("PATH",$_SERVER["DOCUMENT_ROOT"] . FOLDER);

$hostname = $_SERVER['HTTP_HOST'];
$path = dirname($_SERVER['PHP_SELF']);

/*
 * Autoload und einbinden der benötigten Klassen
 */
spl_autoload_register(function($class) {
    require_once(PATH . '/' . str_replace('\\', '/', $class) . '.php');
});

use components\classes\dbConnect;
use components\classes\logger;

/*
 * Aufbau der DB Verbindung
 */
require_once(PATH . '/components/configDB.php');

$logger = new logger();

if(isset($_GET['logout'])) {
    $_SESSION = array();
    header('Location: http://'.$hostname.($path == '/' ? '' : $path).'/index.php');
}

if(basename($_SERVER[ 'SCRIPT_NAME' ]) != "index.php" && !isset($independent)) {
    if(!isset($_SESSION['user'])) {
        header('Location: http://'.$hostname.'/' . FOLDER . '/index.php');
    }
    else {
        // $user = dbConnect::fetchSingle("select * from port_bo_user where id = ?", "user", array($_SESSION['user']));
    }
}

// $ports = dbConnect::fetchAll('select * from port_bo_port', 'port', array());

?>