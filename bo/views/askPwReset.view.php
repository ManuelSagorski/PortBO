<?php
use bo\components\classes\helper\Text;

$independent = true;
include 'components/config.php';

if(isset($_COOKIE["boLanguage"])) {
    $text = new Text($_COOKIE["boLanguage"]);
}
?>
<div id="loginBody">
	<h1>Backoffice</h1>
	<div id="pwResetBody">
    	<div><?php $text->_('forgott-your-password'); ?></div>
    	<form id="sendLink" class="ui form">
    		<div class="field">
    			<input 
    				type="text" 
    				name="usernameReset" 
    				placeholder="<?php $text->_('username'); ?>..."
    			/>
    			<div id="msgUsernameReset"></div>
    		</div>
    		<?php echo (isset($msg['error']))?'<div id="errorMsg">' . $msg['error'] . '</div>':''; ?>
    		<div class="loginRow">
    			<button class="ui primary button"><?php $text->_('send'); ?></button>
    		</div>
    	</form>
    	<script>
        	$("#sendLink").submit(function(event){ event.preventDefault(); askPwResetLink(); });
        </script>
    </div>
</div>