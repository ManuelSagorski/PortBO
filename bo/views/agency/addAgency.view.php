<?php
namespace bo\views\agency;
include '../../components/config.php';
use bo\components\classes\Agency;

if(isset($_GET['id']))
    $agency = Agency::getSingleObjectByID($_GET['id']);

$name = '';
if(!empty($_GET['searchValue']))
    $name = $_GET['searchValue'];
?>

<form id="addAgency" class="ui form" autocomplete="off">
    <div class="ui error message">
		<div id="errorMessage"></div>
    </div>

    <div id="input_agencyName" class="required field">
    	<label>Name</label>
    	<input 
    		type="text" 
    		id="agencyName" 
    		name="agencyName" 
    		onkeyup="formValidate.clearAllError();" 
    		value="<?php echo(!empty($agency))?$agency->getName():$name; ?>"
    	>
    </div>

    <div id="input_agencyShort" class="required field">
    	<label>Kürzel</label>
    	<input 
    		type="text" 
    		id="agencyShort" 
    		name="agencyShort" 
    		onkeyup="formValidate.clearAllError();" 
    		value="<?php echo(!empty($agency))?$agency->getShort():''; ?>"
    	>
    </div>
    
    <button class="ui button" type="submit">Speichern</button>
</form>

<script>
$("#addAgency").submit(function(event){ agency.addAgency(<?php echo (!empty($agency))?$agency->getID():'null'; ?>);});
</script>