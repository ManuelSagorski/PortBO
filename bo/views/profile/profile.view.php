<?php
namespace bo\views\profile;
include '../../components/config.php';
?>

<div class="ui grid profile">
	<div class="four wide column">
    	<div class="ui raised segment">
    		<h4 class="ui header"><?php $t->_('change-password'); ?></h4>
			<form id="changePassword" class="ui form" autocomplete="off">
			    <div class="ui error message">
            		<div id="errorMessage"></div>
                </div>
                
                <div class="ui success message">
                	<div id="successMessage"><?php $t->_('password-changed'); ?></div>
                </div>
                
                <div id="input_secretNew1" class="required field">
                	<label><?php $t->_('new-password'); ?></label>
                	<input 
                		type="password" 
                		id="secretNew1" 
                		name="secretNew1" 
                		onkeyup="formValidate.clearAllError();"
                	>
                </div>
                
                <div id="input_secretNew2" class="required field">
                	<label><?php $t->_('password-confirmation'); ?></label>
                	<input 
                		type="password" 
                		id="secretNew2" 
                		name="secretNew2" 
                		onkeyup="formValidate.clearAllError();"
                	>
                </div>
                
                <button class="ui button" type="submit"><?php $t->_('change-password'); ?></button>
			</form>
        </div>
	</div>
	
	<div class="four wide column">
    	<div class="ui raised segment">
    		<h4 class="ui header"><?php $t->_('change-email'); ?></h4>
			<form id="changeEmail" class="ui form" autocomplete="off">
			    <div class="ui error message">
            		<div id="errorMessage"></div>
                </div>
                                
                <div class="ui success message">
                	<p><?php $t->_('email-changed'); ?></p>
                </div>
                
    			<div><?php $t->_('current-email'); ?>:</div>
        		<div id="actualEmail"><?php echo $user->getEmail(); ?></div>
        		<div class="ui divider"></div>
                
                <div id="input_emailNew" class="required field">
                	<label><?php $t->_('new-email'); ?></label>
                	<input 
                		type="text" 
                		id="emailNew" 
                		name="emailNew" 
                		onkeyup="formValidate.clearAllError();"
                	>
                </div>
               
                <button class="ui button" type="submit"><?php $t->_('safe'); ?></button>
			</form>
        </div>
	</div>

	<div class="eight wide column">
    	<div class="ui raised segment">
    		<h4 class="ui header"><?php $t->_('contact'); ?></h4>
			<form id="sendMessage" class="ui form" autocomplete="off">
				<div class="ui error message">
            		<div id="errorMessage"></div>
                </div>
                
                <div class="ui success message">
                	<p><?php $t->_('message-sended'); ?></p>
                </div>
                
			    <div id="input_message" class="required field">
                	<label><?php $t->_('message-coordination'); ?></label>
                	<textarea rows="4" id="message" name="message"></textarea>
                </div>
				<button class="ui button" type="submit"><?php $t->_('send'); ?></button>
			</form>
        </div>
	</div>
	
	<div class="four wide column">
    	<div class="ui raised segment">
    		<h4 class="ui header"><?php $t->_('change-mobile'); ?></h4>
			<form id="changePhone" class="ui form" autocomplete="off">
			    <div class="ui error message">
            		<div id="errorMessage"></div>
                </div>
                                                
                <div class="ui success message">
                	<p><?php $t->_('mobile-changed'); ?></p>
                </div>

        		<div><?php $t->_('current-mobile'); ?>:</div>
        		<div id="actualPhone"><?php echo $user->getPhone(); ?></div>
        		<div class="ui divider"></div>
                
                <div id="input_phoneNew" class="required field">
                	<label><?php $t->_('new-mobile'); ?></label>
                	<input 
                		type="tel" 
                		id="phoneNew" 
                		name="phoneNew" 
                		onkeyup="formValidate.clearAllError();"
                	>
                </div>
               
                <button class="ui button" type="submit"><?php $t->_('safe'); ?></button>
			</form>
        </div>
	</div>
</div>

<script>
$("#changePassword").submit(function(event){ profile.changePassword(this.id); });
$("#changeEmail").submit(function(event){ profile.changeMail(this.id); });
$("#changePhone").submit(function(event){ profile.changePhone(this.id); });
$("#sendMessage").submit(function(event){ profile.sendMessage(this.id); });
</script>