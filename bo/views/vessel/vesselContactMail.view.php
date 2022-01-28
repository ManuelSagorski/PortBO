<?php
namespace bo\views\vessel;

use bo\components\classes\helper\DBConnect;
use bo\components\classes\VesselContactMail;

include '../../components/config.php';
$vesselContactMails = DBConnect::fetchAll("select * from port_bo_vesselContactMail where contact_id = ?", VesselContactMail::class, Array($_GET['contactID']))
?>

<?php foreach ($vesselContactMails as $vesselContactMail) { ?>
<div class="mailComplete ui stacked segment">
    <div class="mailSubject listingHeadline"><?php echo $vesselContactMail->getSubject(); ?></div>
    <div class="mailBody">
    	<div class="mailText"><?php echo $vesselContactMail->getMessage(); ?></div>
    	<div class="mailOverlay"></div>
    </div>
</div>
<?php } ?>

