<?php
namespace bo;
use bo\components\controller\LoginController;
use bo\components\classes\helper\Text;
include 'components/config.php';

if(isset($_GET['language'])) {
    $text = new Text($_GET['language']);
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