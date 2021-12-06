<?php
namespace views\vessel;

use components\classes\dbConnect;
use components\classes\vesselContactDetails;

include '../../components/config.php';

if(isset($_GET['contactDetailID'])) {
    $contactDetail = dbConnect::fetchSingle("select * from port_bo_vesselContactDetails where id= ?", vesselContactDetails::class, array($_GET['contactDetailID']));
}
?>

<form id="addVesselContactDetail" class="ui form" autocomplete="off">
    <div class="ui error message">
		<div id="errorMessage"></div>
    </div>

    <div class="two fields">
        <div id="input_contactDetailType" class="field">
        	<label>Kontakt Typ</label>
        	<select id="contactDetailType" name="contactDetailType" class="ui fluid dropdown">
        	<?php foreach (vesselContactDetails::$contactDetailTypes as $type) { ?>
    			<option 
    				value="<?php echo $type; ?>"
    				<?php if(!empty($contactDetail)){echo ($contactDetail->getType() == $type)?' selected':'';} ?>
    			><?php echo $type; ?></option>
    		<?php } ?>
        	</select>
        </div>
    
        <div id="input_contactDetail" class="required field">
        	<label>Kontaktinformation</label>
        	<input 
        		type="text" 
        		id="contactDetail" 
        		name="contactDetail" 
        		onkeyup="formValidate.clearAllError();" 
        		value="<?php echo(!empty($contactDetail))?$contactDetail->getDetail():''; ?>"
        	>
        </div>
    </div>
    
	<div id="input_contactDetailInfo" class="field">
    	<label>Zusätzliche Information</label>
    	<textarea rows="2" id="contactDetailInfo" name="contactDetailInfo"><?php echo(!empty($contactDetail))?$contactDetail->getInfo():''; ?></textarea>
    </div>
    
    <button class="ui button" type="submit">Speichern</button>
</form>

<script>
$("#addVesselContactDetail").submit(function(event){ 
	vessel.addVesselContactDetail(<?php echo $_GET['vesselID']; ?>, <?php echo (!empty($contactDetail))?$contactDetail->getId():'null'; ?>);
});
</script>