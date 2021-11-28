<?php
use components\classes\dbConnect;
use components\classes\vessel;
use components\types\vesselTypes;

include '../../components/config.php';

if(isset($_GET['id'])) {
    $vessel = dbConnect::fetchSingle("select * from port_bo_vessel where id= ?", vessel::class, array($_GET['id']));
}
$editMode = !empty($vessel);

$imo = '';
$mmsi = '';
$name = '';

if(!empty($_GET['searchValue']) && is_numeric($_GET['searchValue'])) {
    if(strlen($_GET['searchValue']) == 7) {
        $imo = $_GET['searchValue'];
    }
    if(strlen($_GET['searchValue']) == 9) {
        $mmsi = $_GET['searchValue'];
    }
}
if(!empty($_GET['searchValue']) && !is_numeric($_GET['searchValue'])) {
    $name = $_GET['searchValue'];
}
?>


<form id="addVessel" class="ui form" autocomplete="off">

  	<div id="addVesselLoader" class="ui inverted dimmer">
    	<div class="ui text loader">Loading</div>
  	</div>

    <div class="ui error message">
		<div id="errorMessage"></div>
    </div>

    <div id="inputName" class="field">
    	<label>Name</label>
    	<input 
    		type="text" 
    		id="vesselName" 
    		name="vesselName" 
    		onkeyup="formValidate.clearAllError();" 
    		value="<?php echo($editMode)?$vessel->getName():$name; ?>"
    	>
    </div>
    
	<div class="two fields">
	    <div id="inputIMO" class="field">
        	<label>IMO</label>
        	<div class="ui action input">
            	<input 
            		type="number" 
            		id="vesselIMO" 
            		name="vesselIMO" 
            		onkeyup="formValidate.clearAllError();" 
            		value="<?php echo($editMode)?$vessel->getIMO():$imo; ?>"
            	>
                <button class="ui right icon button" onClick="vessel.getData($('#vesselIMO').val());">
                	<i class="cloud download icon"></i>
                </button>
            </div>
        </div>
        
        <div id="inputMMSI" class="field">
        	<label>MMSI</label>
        	<div class="ui action input">
        		<input 
        			type="number" 
        			id="vesselMMSI" 
        			name="vesselMMSI" 
        			onkeyup="formValidate.clearAllError();" 
        			value="<?php echo($editMode)?$vessel->getMMSI():$mmsi; ?>"
        		>
        		<button class="ui right icon button" onClick="vessel.getData($('#vesselMMSI').val());">
                	<i class="cloud download icon"></i>
                </button>
            </div>
        </div>
	</div>
	
	<div class="two fields">
	    <div id="inputENI" class="field">
        	<label>ENI</label>
        	<input 
        		type="number" 
        		id="vesselENI" 
        		name="vesselENI" 
        		onkeyup="formValidate.clearAllError();" 
        		value="<?php echo($editMode)?$vessel->getENI():''; ?>"
        	>
        </div>
        
        <div class="field">
        	<label>Typ</label>
    		<select id="vesselTyp" name="vesselTyp">
    			<?php foreach(vesselTypes::$vesselTypes as $type ) {?>
    			<option value="<?php echo $type; ?>"<?php if($editMode){echo ($vessel->getTyp() == $type)?' selected':'';} ?>><?php echo $type; ?></option>
    			<?php } ?>
    		</select> 
        </div>
	</div>
	
    <div class="field">
    	<label>Sprachen - <i class="iconPointer cloud download icon" onClick="vessel.getLanguages($('#vesselIMO').val());"></i></label>
    	<textarea rows="2" id="vesselLanguage" name="vesselLanguage"><?php echo($editMode)?$vessel->getLanguage():''; ?></textarea>
    </div>
    
    <button class="ui button" type="submit">Speichern</button>
</form>
<script>
$('#vesselTyp').dropdown();
$("#addVessel").submit(function(event){ event.preventDefault(); vessel.addVessel(<?php echo ($editMode)?$vessel->getID():'null'; ?>);});
</script>