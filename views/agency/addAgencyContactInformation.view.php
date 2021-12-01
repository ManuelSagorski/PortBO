<?php
namespace vieviews\agency;

use components\classes\agencyPortInfo;
use components\classes\dbConnect;

include '../../components/config.php';

if(isset($_GET['id'])) {
    $contact = dbConnect::fetchSingle("select * from port_bo_agencyPortInfo where id= ?", agencyPortInfo::class, array($_GET['id']));
}
?>

<form id="addAgencyPortInfo" class="ui form" autocomplete="off">
    <div class="ui error message">
		<div id="errorMessage"></div>
    </div>    

    <div id="inputPort" class="field">
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
	
    <div id="inputEmail" class="field">
    	<label>Email</label>
    	<input 
    		type="text" 
    		id="agencyContactEmail" 
    		name="agencyContactEmail" 
    		onkeyup="formValidate.clearAllError();" 
    		value="<?php echo(!empty($contact))?$contact->getEmail():''; ?>"
    	>
    </div>

    <div class="field">
    	<label>Kontakt Info</label>
    	<textarea rows="2" id="agencyContactInfo" name="agencyContactInfo"><?php echo(!empty($contact))?$contact->getInfo():''; ?></textarea>
    </div>
    
    <input type="hidden" name="agencyID" value="<?php echo $_GET['agencyID']; ?>">
    
    <button class="ui button" type="submit">Speichern</button>
</form>

<script>
$("#addAgencyPortInfo").submit(function(event){ event.preventDefault(); agency.addAgencyPortInfo(<?php echo (!empty($contact))?$contact->getId():'null'; ?>);});
</script>