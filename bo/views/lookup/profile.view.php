<?php 
namespace bo\views\lookup;
include '../../components/config.php';
?>

<div class="ui styled accordion">
    <div class="active title">
        <i class="dropdown icon"></i>
        Change password
    </div>
    <div class="active content">
    	<form id="changePassword" class="ui form" autocomplete="off">
    	    <div class="ui error message">
        		<div id="errorMessage"></div>
            </div>
            
            <div class="ui success message">
            	<div id="successMessage">Your password has been successfully changed.</div>
            </div>
            
            <div id="input_secretNew1" class="required field">
            	<label>New password</label>
            	<input 
            		type="password" 
            		id="secretNew1" 
            		name="secretNew1" 
            		onkeyup="formValidate.clearAllError();"
            	>
            </div>
            
            <div id="input_secretNew2" class="required field">
            	<label>Confirm new password</label>
            	<input 
            		type="password" 
            		id="secretNew2" 
            		name="secretNew2" 
            		onkeyup="formValidate.clearAllError();"
            	>
            </div>
            
            <button class="ui button" type="submit">Change Password</button>
    	</form>
    </div>
    
    <div class="title">
        <i class="dropdown icon"></i>
        Change email
    </div>
    <div class="content">
		<form id="changeEmail" class="ui form" autocomplete="off">
		    <div class="ui error message">
        		<div id="errorMessage"></div>
            </div>
                            
            <div class="ui success message">
            	<p>Your email has been successfully changed.</p>
            </div>
            
			<div>Current email:</div>
    		<div id="actualEmail"><?php echo $user->getEmail(); ?></div>
    		<div class="ui divider"></div>
            
            <div id="input_emailNew" class="required field">
            	<label>New email</label>
            	<input 
            		type="text" 
            		id="emailNew" 
            		name="emailNew" 
            		onkeyup="formValidate.clearAllError();"
            	>
            </div>
           
            <button class="ui button" type="submit">Safe</button>
		</form>
    </div>
</div>

<script>
$("#changePassword").submit(function(event){ lookup.changePassword(this.id); });
$("#changeEmail").submit(function(event){ lookup.changeMail(this.id); });

$('.ui.accordion')
  .accordion()
;
</script>