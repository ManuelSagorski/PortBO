<div class="ui raised green segment" id="loginBody">
	<h1><?php $text->_('title-register'); ?></h1>
	<p><?php $text->_('register'); ?></p>
	<form class="ui form register" id="registerForm" action="index.php" method="post">

		<div class="ui error message"></div>
		
    	<div class="field">
       		<input type="text" name="userFirstName" placeholder="<?php $text->_('first-name'); ?>">
    	</div>
    	<div class="field">
        	<input type="text" name="userSurname" placeholder="<?php $text->_('surname'); ?>">
    	</div>
    	<div class="field">
        	<input type="text" name="userEmail" placeholder="<?php $text->_('email-address'); ?>">
    	</div>
    	<div class="field">
        	<input type="text" name="userPhone" placeholder="<?php $text->_('mobile'); ?>">
        	<div class="formInfoText"><?php $text->_('fone-number-international'); ?></div>
    	</div>

        <div class="ui horizontal divider">
        	<?php $text->_('access-data'); ?>
        </div>

    	<div class="field" id="fieldUsername">
       		<input type="text" id="username" name="userUsername" placeholder="<?php $text->_('username'); ?>" onBlur="checkSelectedUsername();">
    	</div>
    	<div class="field">
        	<input type="password" name="password1" placeholder="<?php $text->_('password'); ?>">
    	</div>
    	<div class="field">
        	<input type="password" name="password2" placeholder="<?php $text->_('password-confirmation'); ?>">
    	</div>

        <div class="field">
            <div class="ui checkbox">
                <input type="checkbox" name="dataprotection">
                <label>
                	<?php $text->_('register-accept-privacy-1'); ?><a class="markedLink" href="/bo/datenschutz.php" target="_blank"><?php $text->_('register-accept-privacy-2'); ?></a><?php $text->_('register-accept-privacy-3'); ?>
                </label>
            </div>
        </div>

		<input type="hidden" name="registerKey" value="<?php echo $_GET['code']; ?>" />
		<input type="hidden" name="language" value="<?php echo $_GET['language']; ?>" />

		<button class="ui primary button" id="registerButton"><?php $text->_('register'); ?></button>
	</form>
</div>