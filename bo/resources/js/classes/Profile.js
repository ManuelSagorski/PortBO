define(function() {

	/*
	 *	Klasse Profile - Funktionalität der Seite Profil
	 */
	var Profile = function() {
		var constructor, that = {}, my = {};

		my.CONTROLLER = '../components/controller/user/';

		constructor = function() {
			return that;
		}
		
		/*
		 *	Öffnet die Seite Profil
		 */
		that.open = function() {
			clearContend();
			prepareCol($('#mainColLeft'), 'notNeededCol');
			prepareCol($('#mainColRight'), 'notNeededCol');
			
			$.get('../views/profile/profile.view.php', function(data) {
				$('#mainColMiddle').html(data);
			});
		}
	
		that.changePassword = function(formID) {
			event.preventDefault();
			changePasswordValidate = new FormValidate(null, formID);
			
			if(!changePasswordValidate.fieldsNotAllEmpty(Array('secretNew1', 'secretNew2'))) {
				changePasswordValidate.setError(Array('secretNew1', 'secretNew2'));
				changePasswordValidate.setErrorMessage(t('insert-password'));
				return;
			}
			
			if(!changePasswordValidate.fieldsEqual()) {
				return;
			}
			
			$.post(my.CONTROLLER + 'userChangePassword', { data: changePasswordValidate.getFormData() }, 
				function() {
					changePasswordValidate.setSuccessMessage();
					$('#secretNew1').val('');
					$('#secretNew2').val('');
				});			
		}
		
		that.changeMail = function(formID) {
			event.preventDefault();
			changeMailValidate = new FormValidate(null, formID);			

			if(!changeMailValidate.fieldEmail(Array('emailNew'))) {
				changeMailValidate.setError(Array('emailNew'));
				changeMailValidate.setErrorMessage(t('insert-email'));
				return;
			}
			
			$.post(my.CONTROLLER + 'userChangeMail', { data: changeMailValidate.getFormData() }, 
				function() {
					changeMailValidate.setSuccessMessage();
					$('#emailNew').val('');
					$('#actualEmail').html(changeMailValidate.getFormData().emailNew);
				});
		}

		that.changePhone = function(formID) {
			event.preventDefault();
			changePhoneValidate = new FormValidate(null, formID);			

			if(!changePhoneValidate.fieldsNotAllEmpty(Array('phoneNew'))) {
				changePhoneValidate.setError(Array('phoneNew'));
				changePhoneValidate.setErrorMessage(t('insert-number'));
				return;
			}
			
			$.post(my.CONTROLLER + 'userChangePhone', { data: changePhoneValidate.getFormData() }, 
				function() {
					changePhoneValidate.setSuccessMessage();
					$('#phoneNew').val('');
					$('#actualPhone').html(changePhoneValidate.getFormData().phoneNew);
				});
		}
	
		that.sendMessage = function(formID) {
			event.preventDefault();
			sendMessageValidate = new FormValidate(null, formID);
			
			if(!sendMessageValidate.fieldsNotAllEmpty(Array('message'))) {
				sendMessageValidate.setError(Array('message'));
				sendMessageValidate.setErrorMessage(t('insert-message'));
				return;
			}
			
			$.post(my.CONTROLLER + 'userSendMessage', { data: sendMessageValidate.getFormData() }, 
				function() {
					sendMessageValidate.setSuccessMessage();
					$('#message').val('');
				});
		}
	
		that.createTelegramCode = function() {
			$.post(my.CONTROLLER + 'createTelegramCode', { }, 
				function() {
					that.open();
				});			
		}
	
		return constructor.call(null);
	}

	return Profile;
});