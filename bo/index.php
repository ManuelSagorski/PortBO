<?php
namespace bo;
use bo\components\controller\LoginController;
include 'components/config.php';

$result = (new loginController())->start();

if(!empty($result['view'])) {
    ob_start();
    include 'views/templates/_indexSiteHeader.template.php';
    include $result['view'];
    include 'views/templates/_indexSiteFooter.template.php';
    ob_end_flush();
}
?>