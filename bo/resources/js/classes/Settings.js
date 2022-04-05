define(function() {

	/*
	 *	Klasse Settings - Funktionalität der Seite Settings
	 */
	var Settings = function() {
		var constructor, that = {}, my = {};
	
		my.CONTROLLER = '../components/controller/settings/';
		my.USER_CONTROLLER = '../components/controller/user/';
	
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
		that.openDetails = function(type, element) {
			$.get('../views/settings/' + type + '.view.php', function(data) {
				$('#mainColMiddle').html(data);
				$('.searchResultRow').removeClass('active');
				if(element) {
					$(element).parent().addClass('active');
				}
				else {
					$('.searchResultRow').first().addClass('active');
				}
			});			
		}

		/*
		 *	Schickt einem neuen Benutzer die Einladung zur Registrierung zu
		 */	
		that.inviteUser = function(projectID) {
			event.preventDefault();
			inviteUserValidate = new FormValidate($('#inviteUser').serializeArray());
			$.post(my.CONTROLLER + 'inviteUser', {projectID: projectID, data: inviteUserValidate.getFormData()}, 
				function(data) {
					$('#email').val('');
					$('#message').html('Einladung wurde verschickt.');
					setTimeout(function(){
					    $('#message').html('');
					}, 4000);

				});		
		}

		/*
		 *	Öffnet das Formular zum Anlegen eines neuen Benutzers
		 */			
		that.newUser = function(userID, edit, projectID) {
			if(typeof projectID === 'undefined') {
				projectID = '';
			}
			if(edit && !userID) {
				alert('Bitte zuerst einen Benutzer auswählen.');
			}
			else {
				$.get('../views/settings/addUser.view.php?id=' + userID + '&projectID=' + projectID, function(data) {
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
			
			if(falseFields = newUserValidate.fieldsNotEmpty(Array('userFirstName', 'userSurname', 'userEmail'))) {
				formValidate.setError(falseFields);
				formValidate.setErrorMessage('Bitte alle Pflichtfelder ausfüllen.');
				return;
			}
	
			if(parseInt(newUserValidate.getFormData().userLevel) != 2) {
				if(falseFields = newUserValidate.fieldsNotEmpty(Array('userPhone'))) {
					formValidate.setError(falseFields);
					formValidate.setErrorMessage('Bei einem Benutzerlevel ungleich "Foreign Port" muss eine Handynummer angegeben werden.');
					return;
				}				
			}

			if(parseInt(newUserValidate.getFormData().userLevel) == 2) {
				if(falseFields = newUserValidate.fieldsNotEmpty(Array('foreignPort'))) {
					formValidate.setError(falseFields);
					formValidate.setErrorMessage('Bei einem "Foreign Port" Mitarbeiter muss der Name des Hafens angegeben werden.');
					return;
				}				
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
			
			$.post(my.USER_CONTROLLER + 'addUser', {id: userID, data: data}, 
				function(data) {
					if(data.type == "error") {
						formValidate.setError(Array(data.msg.field));
						formValidate.setErrorMessage(data.msg.msg);
					}
					else {
						if(newUserValidate.getFormData().projectID) {
							that.openDetails('projects', $('#settingsProjekte').get(0));
						}
						else {
							that.openDetails('users');
						}					
						closeWindow();
					}
				}, 'json');
		}

		/*
		 *	Löscht bzw deaktiviert einen bestehenden User
		 */
		that.deleteUser = function(userID) {
			if(userID) {
				if(confirm("Möchtest du den gewählten Benutzer wirklich löschen?")) {
					$.post(my.USER_CONTROLLER + 'deleteUser', {id: userID}, 
						function() {
							that.openDetails('users');
						});
				}
			}
			else {
				alert('Bitte zuerst einen Benutzer auswählen.');
			}			
		}

		/*
		 *	Schickt an einen Benutzer erneut eine Einladungsmail
		 */			
		that.sendInvitationMail = function(userID) {
			event.preventDefault();
			
			if(!confirm("Möchtest wirklich eine neue Einladungsmail verschicken? Das Passwort des Benutzers wird dabei zurückgesetzt.")) {
				return;
			}
			
			$.post(my.USER_CONTROLLER + 'sendInvitationMail', {id: userID}, 
				function() {
					that.openDetails('users');
					closeWindow();
				});
		}

		/*
		 *	Blendet die Funktion zum Hinzufügen eines Kalenders ein
		 */			
		that.showAddKalender = function() {
			event.preventDefault();
			$('#addKalender').show();
		}

		/*
		 *	Fügt für den Benutzer eine Kalender auf özg hinzu
		 */			
		that.addUserKalender = function(userID, projectID) {
			event.preventDefault();
			newUserKalender = new FormValidate($('#addKalenderForm').serializeArray());
			
			$.post(my.USER_CONTROLLER + 'addUserKalender', {id: userID, projectID: projectID, kalender: newUserKalender.getFormData().kalender}, 
				function() {
					that.openDetails('users');
					closeWindow();
				});
		}
		
		/*
		 *	Öffnet das Formular zum Anlegen eines neuen externen Links
		 */			
		that.newLink = function(linkID, edit) {
			if(edit && !linkID) {
				alert('Bitte zuerst einen bestehenden Link auswählen.');
			}
			else {
				$.get('../views/settings/addLink.view.php?id=' + linkID, function(data) {
					$('#windowLabel').html("Neuen Link hinzufügen");
					$('#windowBody').html(data);
				});
				showWindow();
			}
		}
		
		/*
		 *	Speichert einen neuen externen Link in der Datenbank
		 */			
		that.addLink = function(linkID) {
			event.preventDefault();
			newLinkValidate = new FormValidate($('#addLink').serializeArray());
			
			if(falseFields = newLinkValidate.fieldsNotEmpty(Array('linkName', 'linkUrl'))) {
				formValidate.setError(falseFields);
				formValidate.setErrorMessage('Bitte alle Pflichtfelder ausfüllen.');
				return;
			}
			
			$.post(my.CONTROLLER + 'addExternLink', {id: linkID, data: newLinkValidate.getFormData()}, 
				function() {
					that.openDetails('settings', $('#settingsEinstellungen').get(0));
					closeWindow();
				});
		}
		
		/*
		 *	Externen Link löschen
		 */			
		that.deleteLink = function(linkID) {
			if(linkID) {
				if(confirm("Möchtest du den gewählten Link wirklich löschen?")) {
					$.post(my.CONTROLLER + 'deleteExternLink', {linkID: linkID}, 
						function() {
							closeWindow();
							that.openDetails('settings', $('#settingsEinstellungen').get(0));
						});	
				}
			}
			else {
				alert('Bitte zuerst einen Link auswählen.');
			}
		}
		
		/*
		 *	Lädt die Statstiken
		 */		
		that.getStatistics = function() {
			event.preventDefault();
			dateForm = new FormValidate($('#statisticsDate').serializeArray());
			data = dateForm.getFormData();
			
			/*
			$.get('../views/settings/statisticsContend.view.php?startDate=' + dateForm.getFormData().dateFrom + '&endDate=' + dateForm.getFormData().dateTo, function(data) {
					$('#statisticsContend').html(data);
				});
			*/
			$.post('../views/settings/statisticsContend.view.php', {data: data}, 
				function(data) {
					$('#statisticsContend').html(data);
				});
		}

		/*
		 *	Aktiviert bzw. Deaktiviert ein Modul für ein bestimmtes Projekt
		 */
		that.safeModuleSetting = function(element, projectID) {
			$.post(my.CONTROLLER + 'safeModuleSetting', {module: element.attr('name'), value: element.closest('div').checkbox('is checked'), projectID: projectID}, 
				function() {
				});
		}
		
		return constructor.call(null);
	}

	return Settings;
});