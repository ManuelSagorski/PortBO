<?php
namespace bo;
use bo\components\controller\LoginController;
use bo\components\classes\helper\Text;
include 'components/config.php';

if(isset($_GET['language'])) {
    $text = new Text($_GET['language']);
    setcookie('boLanguage', $_GET['language'], time()+(3600*24*30), '/');
}
else {
    $text = new Text('de');
}

if(isset($_COOKIE["boLanguage"])) {
    $text = new Text($_COOKIE["boLanguage"]);
}

$result = (new loginController())->start();

if(!empty($result['view'])) {
    ob_start();
    include 'views/templates/_indexSiteHeader.template.php';
    include $result['view'];
    include 'views/templates/_indexSiteFooter.template.php';
    ob_end_flush();
}
?>