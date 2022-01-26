<?php
namespace bo\views\vessel;

use bo\components\classes\helper\dbConnect;
use bo\components\classes\vesselContactMail;

include '../../components/config.php';
$vesselContactMails = dbConnect::fetchAll("select * from port_bo_vesselContactMail where contact_id = ?", vesselContactMail::class, Array($_GET['contactID']))
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

