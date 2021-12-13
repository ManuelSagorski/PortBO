define(function() {

	/*
	 *	Klasse Settings - Funktionalität der Seite Settings
	 */
	var Settings = function() {
		var constructor, that = {}, my = {};
	
		constructor = function() {
			return that;
		}
		
		/*
		 *	Öffnet die Seite Settings
		 */
		that.open = function() {
			clearContend();
			prepareCol($('#mainColLeft'), 'settingsSearchCol');
			prepareCol($('#mainColRight'), 'notNeededCol');
			
			$.get('../views/settings/search.view.php', function(data) {
				$('#mainColLeft').html(data);
			});
			
			that.openDetails('users');
		}
		
		/*
		 *	Öffnet die Unterseiten der Einstellungen
		 */		
		that.openDetails = function(type) {
			$.get('../views/settings/' + type + '.view.php', function(data) {
				$('#mainColMiddle').html(data);
			});			
		}

		/*
		 *	Öffnet das Formular zum Anlegen eines neuen Benutzers
		 */			
		that.newUser = function(userID, edit) {
			if(edit && !userID) {
				alert('Bitte zuerst einen Benutzer auswählen.');
			}
			else {
				$.get('../views/settings/addUser.view.php?id=' + userID, function(data) {
					$('#windowLabel').html("Neuen Benutzer hinzufügen");
					$('#windowBody').html(data);
				});
				showWindow();
			}
		}

		/*
		 *	Speichert einen neuen Benutzer in der Datenbank
		 */			
		that.addUser = function(userID) {
			event.preventDefault();
			newUserValidate = new FormValidate($('#addUser').serializeArray());
			
			if(falseFields = newUserValidate.fieldsNotEmpty(Array('userFirstName', 'userSurname', 'userPhone', 'userEmail'))) {
				formValidate.setError(falseFields);
				formValidate.setErrorMessage('Bitte alle Pflichtfelder ausfüllen.');
				return;
			}
		
			if(parseInt(newUserValidate.getFormData().userLevel) > 1) {
				if(!newUserValidate.fieldsNotAllEmpty(Array('userUsername'))) {
					formValidate.setError(Array('userUsername'));
					formValidate.setErrorMessage('Bei einem Benutzerlevel ungleich "Verkündiger" muss ein Benutzername angegeben werden.');
					return;
				}
			}
			
			data = newUserValidate.getFormData();
			data.userLanguages = $("#userLanguage").dropdown("get value");
			data.userPorts = $("#userPort").dropdown("get value");
			
			$.post('../components/controller/userController.php', {type: 'addUser', id: userID, data: data}, 
				function() {
					that.openDetails('users');
					closeWindow();
				});
		}
		
		return constructor.call(null);
	}

	return Settings;
});