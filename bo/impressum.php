<?php

use bo\components\classes\helper\Text;

$independent = true;
include 'components/config.php';
/** @var Text $t */

?>
<!DOCTYPE html>
<html lang="de">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="msapplication-TileColor" content="#ffffff">
        <meta name="msapplication-TileImage" content="resources/img/favicon/ms-icon-144x144.png">
        <meta name="theme-color" content="#ffffff">

        <link rel="apple-touch-icon" sizes="57x57" href="resources/img/favicon/apple-icon-57x57.png">
        <link rel="apple-touch-icon" sizes="60x60" href="resources/img/favicon/apple-icon-60x60.png">
        <link rel="apple-touch-icon" sizes="72x72" href="resources/img/favicon/apple-icon-72x72.png">
        <link rel="apple-touch-icon" sizes="76x76" href="resources/img/favicon/apple-icon-76x76.png">
        <link rel="apple-touch-icon" sizes="114x114" href="resources/img/favicon/apple-icon-114x114.png">
        <link rel="apple-touch-icon" sizes="120x120" href="resources/img/favicon/apple-icon-120x120.png">
        <link rel="apple-touch-icon" sizes="144x144" href="resources/img/favicon/apple-icon-144x144.png">
        <link rel="apple-touch-icon" sizes="152x152" href="resources/img/favicon/apple-icon-152x152.png">
        <link rel="apple-touch-icon" sizes="180x180" href="resources/img/favicon/apple-icon-180x180.png">
        <link rel="icon" type="image/png" sizes="192x192"  href="resources/img/favicon/android-icon-192x192.png">
        <link rel="icon" type="image/png" sizes="32x32" href="resources/img/favicon/favicon-32x32.png">
        <link rel="icon" type="image/png" sizes="96x96" href="resources/img/favicon/favicon-96x96.png">
        <link rel="icon" type="image/png" sizes="16x16" href="resources/img/favicon/favicon-16x16.png">
        <link rel="manifest" href="resources/img/favicon/manifest.json">

		<link rel="stylesheet" type="text/css" href="resources/css/global.css" />
		<link rel="stylesheet" type="text/css" href="resources/css/index.css" />

		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
		<script data-main="resources/js/index" src="resources/js/libraries/require.js"></script>
			
        <script>
            if(typeof window.history.pushState == 'function') {
                window.history.pushState({}, "Hide", '<?php echo $_SERVER['PHP_SELF'];?>');
            }
        </script>

		<title>BackOffice Hafengruppe Nord</title>
	</head>
	<body>
		<div id="imprintWrapper">
            <div id="loginBody" style="text-align: left;">
            	<h1><?php $t->_('imprint-title'); ?></h1>
            	<p>
                    <?php $t->_('imprint-main'); ?>
            		<br /><br />
                    <?php $t->_('imprint-responsible-for'); ?>
            		<br /><br />
                    <?php $t->_('website-owner-contact'); ?>
            		<br /><br />
            		<a onclick="window.close();" style="text-decoration: underline;"><?php $t->_('close'); ?></a>
            	</p>
            </div>
		</div>
	</body>
</html>