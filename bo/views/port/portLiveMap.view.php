<?php 
    use bo\components\classes\Port;

    if (session_status() !== PHP_SESSION_ACTIVE) {
        include '../../components/config.php';
        $port = Port::getSingleObjectByID(intval($_GET['portID']));
    }
?>

<?php if(!empty($port->getLat()) && !empty($port->getLon())) { ?>
<iframe 
	name="vesselfinder" 
	id="vesselfinder" 
	width="100%" 
	height="350px" 
	frameborder="0"
	src="https://www.vesselfinder.com/aismap?zoom=<?php echo $port->getVfZoom(); ?>&lat=<?php echo $port->getLat(); ?>&lon=<?php echo $port->getLon(); ?>&names=false&clicktoact=false"
></iframe>
<?php } ?>