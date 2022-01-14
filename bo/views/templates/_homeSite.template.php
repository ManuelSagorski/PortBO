<!DOCTYPE html>
<html lang="de">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="msapplication-TileColor" content="#ffffff">
        <meta name="msapplication-TileImage" content="../resources/img/favicon/ms-icon-144x144.png">
        <meta name="theme-color" content="#ffffff">

        <link rel="apple-touch-icon" sizes="57x57" href="../resources/img/favicon/apple-icon-57x57.png">
        <link rel="apple-touch-icon" sizes="60x60" href="../resources/img/favicon/apple-icon-60x60.png">
        <link rel="apple-touch-icon" sizes="72x72" href="../resources/img/favicon/apple-icon-72x72.png">
        <link rel="apple-touch-icon" sizes="76x76" href="../resources/img/favicon/apple-icon-76x76.png">
        <link rel="apple-touch-icon" sizes="114x114" href="../resources/img/favicon/apple-icon-114x114.png">
        <link rel="apple-touch-icon" sizes="120x120" href="../resources/img/favicon/apple-icon-120x120.png">
        <link rel="apple-touch-icon" sizes="144x144" href="../resources/img/favicon/apple-icon-144x144.png">
        <link rel="apple-touch-icon" sizes="152x152" href="../resources/img/favicon/apple-icon-152x152.png">
        <link rel="apple-touch-icon" sizes="180x180" href="../resources/img/favicon/apple-icon-180x180.png">
        <link rel="icon" type="image/png" sizes="192x192"  href="../resources/img/favicon/android-icon-192x192.png">
        <link rel="icon" type="image/png" sizes="32x32" href="../resources/img/favicon/favicon-32x32.png">
        <link rel="icon" type="image/png" sizes="96x96" href="../resources/img/favicon/favicon-96x96.png">
        <link rel="icon" type="image/png" sizes="16x16" href="../resources/img/favicon/favicon-16x16.png">
        <!-- <link rel="manifest" href="resources/img/favicon/manifest.json"> -->

		<link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css">
		<link rel="stylesheet" type="text/css" href="../resources/css/libraries/semantic.min.css" />
		<link rel="stylesheet" type="text/css" href="../resources/css/libraries/icon.min.css" />
		<link rel="stylesheet" type="text/css" href="../resources/css/libraries/flag.min.css" />
		<link rel="stylesheet" type="text/css" href="../resources/css/global.css" />
		<link rel="stylesheet" type="text/css" href="../resources/css/home.css" />
		<link rel="stylesheet" type="text/css" href="../resources/css/vessel.css" />
		<link rel="stylesheet" type="text/css" href="../resources/css/agency.css" />

	    <script>
        	if(typeof window.history.pushState == 'function') {
        		window.history.pushState({}, "Hide", '<?php echo $_SERVER['PHP_SELF'];?>');
        	}
        </script>
	
		<title>BackOffice Hafendienst Norddeutschland</title>
	</head>
	<body>
		<div id="homeWrapper">
    		<div id="head">
    			<div id="nav">
    				<div class="navElement"><a href="vessel">Schiffe</a></div>
    				<div class="navElement"><a href="agency">Agenten</a></div>
    				<div class="navElement"><a href="port">Häfen</a></div>
					<div class="navElement"><a href="profile" title="Profile"><i class="user icon" href="profile"></i></a></div>
					<?php if($user->getPlanningID() > 0) {?>
					<div class="navElement">
						<a class="item" href="https://portbo.xn--zg-eka.de/index.php?id=<?php echo $user->getPlanningID(); ?>" target="_blank">
							<i class="calendar alternate outline icon" href="https://portbo.xn--zg-eka.de/index.php?id=<?php echo $user->getPlanningID(); ?>" target="_blank"></i>
						</a>
					</div>
					<?php } ?>
    				<?php if($user->getLevel() >= 8) {?> <div class="navElement"><a href="settings" title="Settings"><i class="cogs icon" href="settings"></i></a></div> <?php } ?>
    				<div class="navElement"><a href="logout" title="Logout" class="item"><i class="power off icon" href="logout"></i></a></div>
    			</div>
    			<div id="title"><h1>Backoffice Hafendienst Norddeutschland</h1></div>
    		</div>
    		<div id="homeContend" class="flexBox">
    			<div id="mainColLeft" class="mainCol">&nbsp;</div>
    			<div id="mainColMiddle" class="mainCol infoCol">&nbsp;</div>
    			<div id="mainColRight" class="mainCol">&nbsp;</div>
    		</div>
			<div id="footer"></div>
		</div>
	
		<div id="window" class="ui-widget-content">
			<div class="windowHead"><div id="windowLabel" class="label">Label</div><div class="close" onClick="closeWindow();">X</div></div>
			<div id="windowBody" class="windowBody"></div>
		</div>		

		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
		<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
		<script src="../resources/js/libraries/semantic.min.js"></script>
		<script src="../resources/js/libraries/vanilla-router.min.js"></script>
		<script data-main="../resources/js/home" src="../resources/js/libraries/require.js"></script>


		<!-- <script data-main="res/js/telegramMessaging" src="res/js/require.js"></script> -->
	</body>
</html>