<?php
namespace views\vessel;

use components\classes\dbConnect;
use components\classes\vesselInfo;

include '../../components/config.php';

if(isset($_GET['infoID'])) {
    $info = dbConnect::fetchSingle("select * from port_bo_vesselInfo where id= ?", vesselInfo::class, array($_GET['infoID']));
}
?>

<form id="addVesselInfo" class="ui form" autocomplete="off">
    <div class="ui error message">
		<div id="errorMessage"></div>
    </div>

    <div id="inputInfo" class="field">
    	<label>Information</label>
    	<textarea rows="2" id="vesselInfo" name="vesselInfo" onkeyup="formValidate.clearAllError();"><?php echo (!empty($info))?$info->getInfo():''; ?></textarea>
    </div>
    
    <input type="hidden" name="vesselID" value="<?php echo $_GET['vesselID']; ?>">
    
    <button class="ui button" type="submit">Speichern</button>
</form>
<script>
$("#addVesselInfo").submit(function(event){ vessel.addVesselInfo(<?php echo (!empty($info))?$info->getID():'null'; ?>);});
</script>