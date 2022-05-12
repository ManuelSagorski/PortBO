<?php
namespace bo\views\vessel;

use bo\components\classes\helper\DBConnect;
use bo\components\classes\VesselContactDetails;

include '../../components/config.php';

if(isset($_GET['contactDetailID']))
    $contactDetail = DBConnect::fetchSingle("select * from port_bo_vesselContactDetails where id= ?", VesselContactDetails::class, array($_GET['contactDetailID']));
?>

<form id="addVesselContactDetail" class="ui form" autocomplete="off">
    <div class="ui icon orange message">
        <i class="exclamation icon"></i>
        <div class="content">
            <div class="header">
            	<?php $t->_('attention'); ?>
            </div>
        	<p><?php $t->_('no-private-data'); ?></p>
        </div>
    </div>
    
    <div class="ui error message">
		<div id="errorMessage"></div>
    </div>

    <div class="two fields">
        <div id="input_contactDetailType" class="field">
        	<label><?php $t->_('contact-type'); ?></label>
        	<select id="contactDetailType" name="contactDetailType" class="ui fluid dropdown">
        	<?php foreach (VesselContactDetails::$contactDetailTypes as $type) { ?>
    			<option 
    				value="<?php echo $type; ?>"
    				<?php if(!empty($contactDetail)){echo ($contactDetail->getType() == $type)?' selected':'';} ?>
    			><?php $t->_(VesselContactDetails::TYPE_TRANSLATION_KEYS[$type]); ?></option>
    		<?php } ?>
        	</select>
        </div>
    
        <div id="input_contactDetail" class="required field">
        	<label><?php $t->_('contactinformation'); ?></label>
        	<input 
        		type="text" 
        		id="contactDetail" 
        		name="contactDetail" 
        		onkeyup="formValidate.clearAllError();" 
        		value="<?php echo(!empty($contactDetail))?$contactDetail->getDetail():''; ?>"
        	>
        </div>
    </div>

<?php 
/*  

###
Kontakt Detail Info nicht mehr gewünscht
###

	<div id="input_contactDetailInfo" class="field">
    	<label>Zusätzliche Information</label>
    	<textarea rows="2" id="contactDetailInfo" name="contactDetailInfo"><?php echo(!empty($contactDetail))?$contactDetail->getInfo():''; ?></textarea>
    </div>
*/
?>

    <button class="ui button" type="submit"><?php $t->_('safe'); ?></button>
</form>

<script>
$("#addVesselContactDetail").submit(function(event){ 
	vessel.addVesselContactDetail(<?php echo $_GET['vesselID']; ?>, <?php echo (!empty($contactDetail))?$contactDetail->getId():'null'; ?>);
});
</script>