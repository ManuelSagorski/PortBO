<?php
namespace bo\views\vessel;

use bo\components\classes\dbConnect;
use bo\components\classes\vesselContact;
use bo\components\classes\agency;
use bo\components\types\contactTypes;

include '../../components/config.php';

if(isset($_GET['contactID'])) {
    $contact = dbConnect::fetchSingle("select * from port_bo_vesselContact where id= ?", vesselContact::class, array($_GET['contactID']));
}
?>

<form id="addVesselContact" class="ui form" autocomplete="off">
    <div class="ui icon orange message">
        <i class="exclamation icon"></i>
        <div class="content">
            <div class="header">
            	Achtung!
            </div>
        	<p>Denke bitte daran, dass aus Gründen des Datenschutzes, hier keine persönlichen Informationen zu Mitgliedern der Crew hinterlegt werden dürfen.</p>
        </div>
    </div>

    <div class="ui error message">
		<div id="errorMessage"></div>
    </div>

	<div class="two fields">
        <div id="input_contactType" class="field">
        	<label>Kontaktart</label>
    		<select id="contactType" name="contactType">
    			<?php foreach (contactTypes::$contactTypes as $contactType) { ?>
    			<option 
    				value="<?php echo $contactType; ?>"
    				<?php if(!empty($contact)){echo ($contact->getContactType() == $contactType)?' selected':'';} ?>
    			><?php echo $contactType; ?></option>
    			<?php } ?>
    		</select>
        </div>

        <div id="input_contactDate" class="field">
        	<label>Datum</label>
        	<input 
        		type="date" 
        		id="contactDate" 
        		name="contactDate" 
        		value="<?php echo(!empty($contact))?$contact->getDate():date("Y-m-d"); ?>"
        	>
        </div>
	</div>
	
	<div class="two fields">
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

    	<div id="input_contactName" class="field ui search category">
    		<label>Kontakt durch</label>
    		<div class="ui icon input">
    			<input 
    				type="text" 
    				id="contactName" 
    				name="contactName" 
    				value="<?php echo(!empty($contact))?$contact->getContactName():''; ?>" 
    				onkeyup="(this.value.length > 0)?inputSearch('userForContact', this.value):hideInputSearchResults(); 
    				         formValidate.clearAllError();"
    				onblur="hideInputSearchResults();"
    			>
    			<i class="search icon"></i>
    		</div>
    		<div id="userSuggest" class="results transition"></div>
    	</div>
	</div>

	<div class="two fields">
        <div id="input_contactAgent" class="field ui search category">
        	<label>Agent</label>
        	<div class="ui icon input">
            	<input 
            		type="text" 
            		id="contactAgent" 
            		name="contactAgent" 
            		value="<?php echo(!empty($contact))?agency::getAgentName($contact->getAgentID()):''; ?>"
            		onkeyup="(this.value.length > 0)?inputSearch('agentForContact', this.value):hideInputSearchResults(); 
            		         $('#agentInfoContainer').hide('slow'); 
            		         formValidate.clearAllError();"
    				onblur="hideInputSearchResults();"
            	>
            	<i class="search icon"></i>
            </div>
            <div id="agentSuggest" class="results transition"></div>
        </div>
        
        <div id="input_contactPlanned" class="field">
        	<label>geplant</label>
    		<input 
    			type="checkbox" 
    			id="contactPlanned" 
    			name="contactPlanned" 
    			<?php echo (!empty($contact) && $contact->getPlanned() == 0)?"":" checked"; ?>
    		>
        </div>
	</div>

    <div id="agentInfoContainer" class="ui segment"></div>
	
	<div id="input_contactInfo" class="field">
    	<label>Kontakt Info</label>
    	<textarea rows="2" id="contactInfo" name="contactInfo"><?php echo(!empty($contact))?$contact->getInfo():''; ?></textarea>
    </div>
    
    <button class="ui button" type="submit">Speichern</button>
</form>

<script>
$("#addVesselContact").submit(function(event){ vessel.addVesselContact(<?php echo $_GET['vesselID']; ?>, <?php echo (!empty($contact))?$contact->getId():'null'; ?>);});
if($("#contactAgent").val() != "") {
	selectSuggested('agent', $("#contactAgent").val());
}
</script>