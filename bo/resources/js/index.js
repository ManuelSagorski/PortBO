/*
 *	index.js
 *
 *	Javascript für die Login-Seite
 *
 */

require(['classes/FormValidate'], function() {
	FormValidate = require('classes/FormValidate');
});

function validateLogin() {
	formValidate = new FormValidate($('#loginForm').serializeArray());
	
	if(!formValidate.validateNotEmpty('username', 'Benutzername') || !formValidate.validateNotEmpty('secret', 'Passwort')) {
		event.preventDefault();
	}
}

function loadPwReset() {
	$.get('views/askPwReset.view.php', function(data) {
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