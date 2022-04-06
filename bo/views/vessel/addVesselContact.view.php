<?php
namespace bo\views\vessel;

use bo\components\classes\VesselContact;
use bo\components\classes\Agency;
use bo\components\types\ContactTypes;
use bo\components\classes\Port;
use bo\components\classes\User;
use bo\components\classes\Company;

include '../../components/config.php';

if(isset($_GET['contactID']))
    $contact = VesselContact::getSingleObjectByID($_GET['contactID']);
?>

<form id="addVesselContact" class="ui form" autocomplete="off">
    <div class="ui error message">
		<div id="errorMessage"></div>
    </div>

	<div class="two fields">
        <div id="input_contactType" class="field">
        	<label><?php $t->_('kind-of-contact'); ?></label>
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
        	<label><?php $t->_('date'); ?></label>
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
        	<label><?php $t->_('port'); ?></label>
    		<select id="contactPort" name="contactPort">
    			<?php foreach ($user->getUserPorts() as $port) { ?>
    			<option 
    				value="<?php echo $port->getID(); ?>"
    				<?php if(!empty($contact)){echo ($contact->getPortID() == $port->getID())?' selected':'';} ?>
    			><?php echo $port->getName(); ?></option> 
    			<?php } ?>
    			<?php if(!empty($contact) && !$user->userHasPort($contact->getPortID())) {?>
    			<option
    				value="<?php echo $contact->getPortID(); ?>"
    				selected
    			><?php echo Port::getPortName($contact->getPortID())?></option>
    			<?php } ?>
    		</select>
        </div>

    	<div id="input_contactName" class="field ui search category">
    		<label><?php $t->_('contact-by'); ?></label>
    		<div class="ui icon input">
    			<input 
    				type="text" 
    				id="contactName" 
    				name="contactName" 
    				value="<?php echo(!empty($contact))?User::getUserFullName($contact->getContactUserID()):''; ?>" 
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
        	<label><?php $t->_('agent'); ?></label>
        	<div class="ui icon input">
            	<input 
            		type="text" 
            		id="contactAgent" 
            		name="contactAgent" 
            		value="<?php echo(!empty($contact))?Agency::getAgentName($contact->getAgentID()):''; ?>"
            		onkeyup="(this.value.length > 0)?inputSearch('agentForContact', this.value):hideInputSearchResults(); 
            		         $('#agentInfoContainer').hide('slow'); 
            		         formValidate.clearAllError();"
    				onblur="hideInputSearchResults();"
            	>
            	<i class="search icon"></i>
            </div>
            <div id="agentSuggest" class="results transition"></div>
        </div>
        
	    <div id="input_contactNext" class="field">
        	<label><?php $t->_('next-contact'); ?></label>
    		<select id="contactNext" name="contactNext">
    			<?php foreach (VesselContact::$monthNext as $key => $month) { ?>
    			<option 
    				value="<?php echo $key; ?>"
    				<?php if(!empty($contact)){echo ($contact->getMonthNext() == $key)?' selected':'';} ?>
    			>
    				<?php echo $month; ?>
    			</option>
    			<?php } ?>
    		</select>
        </div>
	</div>

    <div id="agentInfoContainer" class="ui segment"></div>

	<div class="two fields">
        <div id="input_contactCompany" class="field ui search category">
        	<label><?php $t->_('company'); ?></label>
        	<div class="ui icon input">
            	<input 
            		type="text" 
            		id="contactCompany" 
            		name="contactCompany" 
            		value="<?php echo(!empty($contact))?Company::getCompanyName($contact->getCompanyID()):''; ?>"
            		onkeyup="(this.value.length > 0)?inputSearch('companyForContact', this.value, $('#contactPort').dropdown('get value')):hideInputSearchResults(); 
            		         $('#companyInfoContainer').hide('slow'); 
            		         formValidate.clearAllError();"
    				onblur="hideInputSearchResults();"
            	>
            	<i class="search icon"></i>
            </div>
            <div id="companySuggest" class="results transition"></div>
        </div>
        
        <div id="input_contactPlanned" class="field">
        	<label><?php $t->_('planned'); ?></label>
    		<input 
    			type="checkbox" 
    			id="contactPlanned" 
    			name="contactPlanned" 
    			<?php echo (!empty($contact) && $contact->getPlanned() == 0)?"":" checked"; ?>
    		>
        </div>
	</div>
	
	<div id="input_contactInfo" class="field disabled">
    	<label><?php $t->_('contact-info'); ?></label>
    	<textarea rows="2" id="contactInfo" name="contactInfo"><?php echo(!empty($contact))?$contact->getInfo():''; ?></textarea>
    </div>
   
    <button class="ui button" type="submit"><?php $t->_('safe'); ?></button>
</form>

<script>
$("#addVesselContact").submit(function(event){ vessel.addVesselContact(<?php echo $_GET['vesselID']; ?>, <?php echo (!empty($contact))?$contact->getId():'null'; ?>);});
if($("#contactAgent").val() != "") {
	selectSuggested('agent', $("#contactAgent").val());
}
</script>