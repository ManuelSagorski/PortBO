<?php
use components\classes\dbConnect;
use components\classes\logger;

include 'components/config.php';

if(isset($_SESSION['user'])) {
    header('Location: http://'.$hostname.($path == '/' ? '' : $path).'/home.php');
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['username'])) {
    $username = $_POST['username'];
    $passwort = $_POST['secret'];
    
    $user = dbConnect::fetchSingle("select * from port_bo_user where username = ?", "user", array($username));
    
    If (empty($user)) {
        logger::writeLogError('login', 'Loginversuch mit unbekanntem Benutzername: ' . $username);
        $errMessage =  "Benutzername nicht bekannt";
    }
    else {
        If (password_verify($passwort, $user->getSecret())) {
            $_SESSION['user'] = $user->getId();
            logger::writeLogInfo('login', 'Login erfolgreich');
            header('Location: http://'.$hostname.($path == '/' ? '' : $path).'/home.php');
        }
        else {
            logger::writeLogError('login', 'Loginversuch mit verkehrtem Passwort. Benutzername: ' . $username);
            $errMessage = "Falsches Passwort";
        }
    }
}

ob_start();

include 'views/templates/_indexSiteHeader.template.php';
include 'views/login.view.php';
include 'views/templates/_indexSiteFooter.template.php';

ob_end_flush();

?>