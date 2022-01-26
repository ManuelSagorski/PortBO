<?php
namespace bo\views\port;

use bo\components\classes\helper\dbConnect;
use bo\components\classes\company;

include '../../components/config.php';

if(isset($_GET['id'])) {
    $company = dbConnect::fetchSingle("select * from port_bo_company where id= ?", company::class, array($_GET['id']));
}
$editMode = !empty($company);
?>

<form id="addPortCompany" class="ui form" autocomplete="off">
    <div class="ui error message">
		<div id="errorMessage"></div>
    </div>
    
    <div id="input_companyName" class="required field">
    	<label>Name</label>
    	<input 
    		type="text" 
    		id="companyName" 
    		name="companyName" 
    		onkeyup="formValidate.clearAllError();" 
    		value="<?php echo($editMode)?$company->getName():''; ?>"
    	>
    </div>
    
    <div id="input_companyInfo" class="field">
    	<label>Info</label>
    	<textarea rows="4" id="companyInfo" name="companyInfo"><?php echo($editMode)?$company->getInfo():''; ?></textarea>
    </div>

    <div id="input_companyMTLink" class="field">
    	<label>MarineTraffic Link</label>
    	<textarea rows="2" id="companyMTLink" name="companyMTLink"><?php echo($editMode)?$company->getMTLink():''; ?></textarea>
    </div>
    
    <div id="input_companyPMLink" class="field">
    	<label>PortMap Link</label>
    	<textarea rows="2" id="companyPMLink" name="companyPMLink"><?php echo($editMode)?$company->getPMLink():''; ?></textarea>
    </div>
    
    <input type="hidden" name="portID" value="<?php echo $_GET['portID']; ?>">
    
    <button class="ui button" type="submit">Speichern</button>
</form>

<script>
$("#addPortCompany").submit(function(event){ portC.addPortCompany(<?php echo ($editMode)?$company->getID():'null'; ?>);});
</script>