/*
 *	index.js
 *
 *	Javascript für die Login-Seite
 *
 */

require(['classes/FormValidate'], function() {
	FormValidate = require('classes/FormValidate');
});

function loadPwReset() {
	$.get('views/askPwReset.view.php', function(data) {
		$('#indexWrapper').html(data);	
	});
}

function loadRegister() {
	event.preventDefault();
	$.get('views/register.view.php', function(data) {
		$('#indexWrapper').html(data);	
	});	
}

function loadImprint() {
	$.get('views/imprint.view.php', function(data) {
		$('#indexWrapper').html(data);	
	});
}

function askPwResetLink() {
	formValidate = new FormValidate($('#sendLink').serializeArray());
	
	if(formValidate.validateNotEmpty('usernameReset', 'Benutzername')) {
		$.post('index.php', {formData: formValidate.getFormData()},
		function(data) {
			$('#pwResetBody').html(data);
		});
	}
}

function pwReset() {
	formValidate = new FormValidate($('#userNewPW').serializeArray());
	
	if(formValidate.validateNotEmpty('secretNew1') 
	 && formValidate.validateNotEmpty('secretNew2') 
	 && formValidate.validateEqual('secretNew1', 'secretNew2')) {
		$.post('index.php', {formData: formValidate.getFormData()},
		function(data) {
			$('#indexWrapper').html(data);
		});
	}
}

function checkSelectedUsername() {
	$.post('components/controller/Login/checkSelectedUsername', {username: $('#username').val()}, 
		function(data) {
			if(data) {
				$('#registerForm').addClass("error");
				$('#fieldUsername').addClass("error");
				$('#registerButton').addClass("disabled");
				$('.ui.error.message').html('Dieser Benutzername ist bereits vergeben. Bitte wähle einen anderen.');
			}
			else {
				$('#registerButton').removeClass("disabled");
				$('#registerForm').removeClass("error");
				$('.ui.error.message').html('');
			}
		});	
}

function registerNewUser() {
	formValidate = new FormValidate($('#registerForm').serializeArray());
	
	$.post('components/controller/Login/registerNewUser', {formData: formValidate.getFormData()},
		function(data) {
			
		});
}

$('.ui.form.login')
  .form({
    fields: {
      username: {identifier: 'username', rules: [{type   : 'empty', prompt : 'Bitte gebe deinen Benutzernamen ein.'}]},
      secret: {identifier: 'secret', rules: [{type   : 'empty', prompt : 'Bitte gebe dein Passwort ein.'}]}
    }
  });

$('.ui.form.register')
  .form({
    fields: {
		dataprotection: {identifier: 'dataprotection', rules: [{type   : 'checked', prompt : 'Bitte stimme den Datenschutzbedingungen zu.'}]},
		userFirstName: {identifier: 'userFirstName', rules: [{type   : 'empty', prompt : 'Bitte gebe deinen Vornamen ein.'}]},
		userSurname: {identifier: 'userSurname', rules: [{type   : 'empty', prompt : 'Bitte gebe deinen Nachnamen ein.'}]},
		userEmail: {identifier: 'userEmail', rules: [{type   : 'email', prompt : 'Bitte gebe eine gültige Emailadresse ein.'}]},
		userPhone: {identifier: 'userPhone', rules: [{type   : 'empty', prompt : 'Bitte gebe deinen Handynummer ein.'}]},
		userUsername: {identifier: 'userUsername', rules: [{type   : 'empty', prompt : 'Bitte gebe einen Benutzernamen ein.'}]},
    	password1: {identifier: 'password1', rules: [
				{type   : 'empty', prompt : 'Bitte gebe ein Passwort ein.'}, 
              	{type   : 'match[password2]', prompt : 'Die eingegebenen Passwörter stimmen nicht überein.'}]}
    }
  });