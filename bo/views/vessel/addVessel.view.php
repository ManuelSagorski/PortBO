<?php
namespace bo\views\vessel;

use bo\components\classes\Vessel;
use bo\components\types\VesselTypes;
use bo\components\classes\Language;

include '../../components/config.php';

if(isset($_GET['id']))
    $vessel = Vessel::getSingleObjectByID($_GET['id']);
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
    	<?php $t->_('to-safe-imo-eni'); ?>
    </div>

    <div class="ui error message">
		<div id="errorMessage"></div>
    </div>

    <div id="input_vesselName" class="required field">
    	<label><?php $t->_('name'); ?></label>
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
        	<label><?php $t->_('typ'); ?></label>
    		<select id="vesselTyp" name="vesselTyp">
    			<?php foreach(vesselTypes::$vesselTypes as $type ) {?>
    			<option value="<?php echo $type; ?>"<?php if($editMode){echo ($vessel->getTyp() == $type)?' selected':'';} ?>><?php echo $type; ?></option>
    			<?php } ?>
    		</select> 
        </div>
	</div>
	
    <div class="field">
    	<label><?php $t->_('nationalitis'); ?> - <i class="iconPointer cloud download icon" onClick="vessel.getLanguages($('#vesselIMO').val());"></i></label>
    	<textarea rows="2" id="vesselLanguage" name="vesselLanguage"<?php echo ($user->getLevel() != 9)?" readonly='readonly'":""; ?>><?php echo($editMode)?$vessel->getLanguage():''; ?></textarea>
    </div>

	<div class="two fields">
        <div id="input_vesselLanguagesMaster" class="field">
        	<label><?php $t->_('language-master'); ?></label>
    		<select id="vesselLanguagesMaster" name="vesselLanguagesMaster" multiple="multiple" class="ui fluid dropdown">
    			<?php foreach(Language::getMultipleObjects() as $language ) {?>
    			<option value="<?php echo $language->getID(); ?>"><?php echo $language->getName(); ?></option>
    			<?php } ?>
    		</select> 
        </div>
    
        <div id="input_vesselLanguagesCrew" class="field">
        	<label><?php $t->_('language-crew'); ?></label>
    		<select id="vesselLanguagesCrew" name="vesselLanguagesCrew" multiple="multiple" class="ui fluid dropdown">
    			<?php foreach(Language::getMultipleObjects() as $language ) {?>
    			<option value="<?php echo $language->getID(); ?>"><?php echo $language->getName(); ?></option>
    			<?php } ?>
    		</select> 
        </div>
    </div>
    
    <button class="ui button" type="submit"><?php $t->_('safe'); ?></button>
</form>
<script>
$('#vesselTyp').dropdown();
$('#vesselLanguagesMaster').dropdown();
$('#vesselLanguagesCrew').dropdown();
$("#addVessel").submit(function(event){ vessel.addVessel(<?php echo ($editMode)?$vessel->getID():'null'; ?>);});

<?php 
if($editMode) { 
    foreach ($vessel->getVesselLanguagesCrew() as $language) {
        ?>$('#vesselLanguagesCrew').dropdown('set selected', '<?php echo Language::getLanguageByID($language->getLanguageID()); ?>');<?php 
    }
    foreach ($vessel->getVesselLanguagesMaster() as $language) {
        ?>$('#vesselLanguagesMaster').dropdown('set selected', '<?php echo Language::getLanguageByID($language->getLanguageID()); ?>');<?php
    }
} ?>
</script>