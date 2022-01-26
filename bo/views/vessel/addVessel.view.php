<?php
namespace bo\views\vessel;

use bo\components\classes\helper\dbConnect;
use bo\components\classes\vessel;
use bo\components\types\vesselTypes;

include '../../components/config.php';

if(isset($_GET['id']))
    $vessel = dbConnect::fetchSingle("select * from port_bo_vessel where id= ?", vessel::class, array($_GET['id']));
$editMode = !empty($vessel);

$searchValue = trim($_GET['searchValue']);

$imo = '';
$mmsi = '';
$name = '';

if(!empty($searchValue) && is_numeric($searchValue)) {
    if(strlen($searchValue) == 7)
        $imo = $searchValue;
    if(strlen($searchValue) == 9)
        $mmsi = $searchValue;
}
if(!empty($searchValue) && !is_numeric($searchValue))
    $name = $searchValue;
?>


<form id="addVessel" class="ui form" autocomplete="off">
  	<div id="addVesselLoader" class="ui inverted dimmer">
    	<div class="ui text loader">Loading</div>
  	</div>
  	
    <div id="infoMessage" class="ui info message">
    	<i class="close icon" onClick="$('#infoMessage').hide();"></i>
    	Zum Speichern eines neuen Schiffes muss mindestens ein Name und eine IMO oder eine ENI angegeben werden.
    </div>

    <div class="ui error message">
		<div id="errorMessage"></div>
    </div>

    <div id="input_vesselName" class="required field">
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
	    <div id="input_vesselIMO" class="required field">
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
        
        <div id="input_vesselMMSI" class="field">
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
	    <div id="input_vesselENI" class="required field">
        	<label>ENI</label>
        	<input 
        		type="number" 
        		id="vesselENI" 
        		name="vesselENI" 
        		onkeyup="formValidate.clearAllError();" 
        		value="<?php echo($editMode)?$vessel->getENI():''; ?>"
        	>
        </div>
        
        <div id="input_vesselTyp" class="field">
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
$("#addVessel").submit(function(event){ vessel.addVessel(<?php echo ($editMode)?$vessel->getID():'null'; ?>);});
</script>