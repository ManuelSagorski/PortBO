<?php
namespace bo\vieviews\agency;

use bo\components\classes\agencyPortInfo;
use bo\components\classes\helper\dbConnect;

include '../../components/config.php';

if(isset($_GET['id'])) {
    $contact = dbConnect::fetchSingle("select * from port_bo_agencyPortInfo where id= ?", agencyPortInfo::class, array($_GET['id']));
}
?>

<form id="addAgencyPortInfo" class="ui form" autocomplete="off">
    <div class="ui error message">
		<div id="errorMessage"></div>
    </div>    

    <div id="input_contactPort" class="field">
    	<label>Hafen</label>
		<select id="contactPort" name="contactPort">
			<?php foreach ($ports as $port) { ?>
			<option 
				value="<?php echo $port->getId(); ?>"
				<?php if(!empty($contact)){echo ($contact->getPortID() == $port->getId())?' selected':'';} ?>
			><?php echo $port->getName(); ?></option> 
			<?php } ?>
		</select>
    </div>
	
    <div id="input_agencyContactEmail" class="field">
    	<label>Email</label>
    	<input 
    		type="text" 
    		id="agencyContactEmail" 
    		name="agencyContactEmail" 
    		onkeyup="formValidate.clearAllError();" 
    		value="<?php echo(!empty($contact))?$contact->getEmail():''; ?>"
    	>
    </div>

    <div id="input_agencyContactInfo" class="field">
    	<label>Kontakt Info</label>
    	<textarea rows="2" id="agencyContactInfo" name="agencyContactInfo"><?php echo(!empty($contact))?$contact->getInfo():''; ?></textarea>
    </div>
    
    <input type="hidden" name="agencyID" value="<?php echo $_GET['agencyID']; ?>">
    
    <button class="ui button" type="submit">Speichern</button>
</form>

<script>
$("#addAgencyPortInfo").submit(function(event){ event.preventDefault(); agency.addAgencyPortInfo(<?php echo (!empty($contact))?$contact->getId():'null'; ?>);});
</script>