<?php
namespace bo\components\controller;

use bo\components\classes\helper\IMAP;

include '../config.php';

$imap = new imap();

$imap->getMails();

?>