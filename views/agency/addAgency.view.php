<?php
namespace views\agency;

include '../../components/config.php';

use components\classes\agency;
use components\classes\dbConnect;

if(isset($_GET['id'])) {
    $agency = dbConnect::fetchSingle("select * from port_bo_agency where id= ?", agency::class, array($_GET['id']));
}
$editMode = !empty($agency);

$name = '';
if(!empty($_GET['searchValue'])) {
    $name = $_GET['searchValue'];
}
?>

<form id="addAgency" class="ui form" autocomplete="off">
    <div class="ui error message">
		<div id="errorMessage"></div>
    </div>

    <div id="inputName" class="required field">
    	<label>Name</label>
    	<input 
    		type="text" 
    		id="agencyName" 
    		name="agencyName" 
    		onkeyup="formValidate.clearAllError();" 
    		value="<?php echo($editMode)?$agency->getName():$name; ?>"
    	>
    </div>

    <div id="inputShort" class="required field">
    	<label>Kürzel</label>
    	<input 
    		type="text" 
    		id="agencyShort" 
    		name="agencyShort" 
    		onkeyup="formValidate.clearAllError();" 
    		value="<?php echo($editMode)?$agency->getShort():''; ?>"
    	>
    </div>
    
    <button class="ui button" type="submit">Speichern</button>
</form>

<script>
$("#addAgency").submit(function(event){ agency.addAgency(<?php echo ($editMode)?$agency->getID():'null'; ?>);});
</script>