<?php
use bo\components\classes\User;
use bo\components\controller\LoginController;

include 'components/config.php';

if(isset($_GET['logout'])) { $_SESSION = array(); }
if(isset($_SESSION['user'])) { header('Location: http://'.$hostname.($path == '/' ? '' : $path).'/public/' . User::$defaultPage[$_SESSION['userLevel']] . '.php'); }

$loginController = new loginController();
$view = '';

switch($_SERVER['REQUEST_METHOD']) {
    case('POST'):
        if (isset($_POST['username'])) {
            $msg = $loginController->login($_REQUEST);
            if($msg === true) {
                header('Location: http://'.$hostname.($path == '/' ? '' : $path).'/public/' . User::$defaultPage[$_SESSION['userLevel']] . '.php');
            }
        }
        
        if (isset($_POST['formData']['secretNew1']) && isset($_POST['formData']['secretNew2'])) {
            $msg['info'] = $loginController->changePassword($_REQUEST);
        }
        
        if (isset($_POST['formData']['usernameReset'])) {
            echo $loginController->forgotPassword($_REQUEST);
        }
        else {
            $view = 'views/login.view.php';
        }

        break;
        
    case('GET'):
        $view = 'views/login.view.php';
       
        if(isset($_GET['id']) && isset($_GET['code'])) {
            if($loginController->pwReset($_REQUEST)) {                
                $view = 'views/pwReset.view.php';
            }
            else {
                $msg['error'] = "Der verwendete Link zum Zurücksetzen des Passwortes ist ungültig.";                
            }
        }
        break;
}

if(!empty($view)) {
    ob_start();
    include 'views/templates/_indexSiteHeader.template.php';
    include $view;
    include 'views/templates/_indexSiteFooter.template.php';
    ob_end_flush();
}
?>