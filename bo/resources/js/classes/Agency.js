define(function() {

	/*
	 *	Klasse Agency - Funktionalität der Seite Agency
	 */
	var Agency = function() {
		var constructor, that = {}, my = {};
	
		my.CONTROLLER = '../components/controller/agency/';
		my.SEARCH_CONTROLLER = '../components/controller/search/';
	
		constructor = function() {
			return that;
		}
		
		/*
		 *	Öffnet die Seite Agency
		 */
		that.open = function() {
			clearContend();
			prepareCol($('#mainColLeft'), 'agencySearchCol');
			prepareCol($('#mainColRight'), 'notNeededCol');
			
			$.get('../views/agency/search.view.php', function(data) {
				$('#mainColLeft').html(data);
			});
			
			$.get('../views/agency/details.view.php', function(data) {
				$('#mainColMiddle').html(data);
			});
		}
		
		/*
		 *	Sidebar Suche nach Agenten
		 */
		that.searchAgency = function(expression) {
			$.get(my.SEARCH_CONTROLLER + 'agency/?expression=' + expression, function(data) {
				$('#searchResult').html(data);
			});				
		}
		
		/*
		 *	Öffnet die Details zu einer bestimmten Agentur
		 */		
		that.openDetails = function(id) {
			$.get('../views/agency/details.view.php?id=' + id, function(data) {
				$('#mainColMiddle').html(data);
			});			
		}
		
		/*
		 *	Öffnet das Formular zum Anlegen einer neuen Agentur
		 */			
		that.newAgency = function(id, searchValue) {
			$.get('../views/agency/addAgency.view.php?id=' + id + '&searchValue=' + searchValue, function(data) {
				$('#windowLabel').html(t('add-agency'));
				$('#windowBody').html(data);
			});
			showWindow();
		}

		/*
		 *	Speichert eine neue Agentur in der Datenbank 
		 */		
		that.addAgency = function(id) {
			event.preventDefault();
			newAgencyValidate = new FormValidate($('#addAgency').serializeArray()); 

			if(!newAgencyValidate.fieldsNotAllEmpty(Array('agencyName'))) {
				formValidate.setError(Array('agencyName'));
				formValidate.setErrorMessage(t('insert-name'));
				return;
			}			
			if(!newAgencyValidate.fieldsNotAllEmpty(Array('agencyShort'))) {
				formValidate.setError(Array('agencyShort'));
				formValidate.setErrorMessage(t('insert-short'));
				return;
			}

			$.post(my.CONTROLLER + 'addAgency', {id: id, data: newAgencyValidate.getFormData()}, 
				function(data) {
					if(data.type == "error") {
						formValidate.setError(Array(data.msg.field));
						formValidate.setErrorMessage(data.msg.msg);
					}
					else {
						if(data.type == "added") {
							that.searchAgency(data.name);
							closeWindow();
						}
						if(data.type == "changed") {
							that.openDetails(id);
							closeWindow();
						}
					}

				}, 'json');
		}

		/*
		 *	Öffnet das Formular zum Anlegen einer neuen Kontaktinformationen für eine Agentur
		 */			
		that.newAgencyPortInfo = function(agencyID, contactID) {
			$.get('../views/agency/addAgencyContactInformation.view.php?id=' + contactID + '&agencyID=' + agencyID, function(data) {
				$('#windowLabel').html(t('add-agency-contactinfo'));
				$('#windowBody').html(data);
			});
			showWindow();
		}

		/*
		 *	Speichert die Kontaktinformationen der Agentur in der Datenbank
		 */			
		that.addAgencyPortInfo = function(contactID) {
			event.preventDefault();
			newAgencyContactValidate = new FormValidate($('#addAgencyPortInfo').serializeArray());
			
			$.post(my.CONTROLLER + 'addAgencyPortInfo', {id: contactID, data: newAgencyContactValidate.getFormData()}, 
				function() {
					that.openDetails(newAgencyContactValidate.getFormData().agencyID);
					closeWindow();
				});
		}
		
		/*
		 *	Löscht Kontaktinformationen der Agentur aus der Datenbank
		 */	
		that.deleteAgencyPortInfo = function(agencyID, contactID) {
			if(contactID) {
				if(confirm(t('confirm-delete-contactinfo'))) {
					$.post(my.CONTROLLER + 'deleteAgencyPortInfo', {id: contactID}, 
						function() {
							that.openDetails(agencyID);
							closeWindow();
						});
				}
			}
			else {
				alert(t('choose-element'));
			}
		}
		
		/*
		 *	Lädt für ein Formular Informationen zu einem bestimmten Agenten nach
		 */		
		that.loadAgencyInfoForInput = function(agency) {
			$.post(my.CONTROLLER + "getAgencyInfo", {agency: agency, port: $('#contactPort').val()}, 
				function(data) {
					if(Object.keys(data).length === 0) {
						var heading = "<div class='agentInfoHead'>" + t('no-contactinfo') + " " + agency + " - " + $('#contactPort :selected').html() + "</div>";
						$('#agentInfoContainer').html(heading);
					}
					else {			
						$.each(data, function(i, val) {
							if(i==0) {
								var heading = "<div class='agentInfoHead'>" + t('contactinformation-for') + " " + val.agencyName + " - " + val.portName + "</div>";
								$('#agentInfoContainer').html(heading);
								var lastContactInfo = "<div class='agentInfoRow'>" + t('last-contact') + " " + val.lastContact + "</div>";
								$('#agentInfoContainer').append(lastContactInfo);
							}
							var agentInfoValue = "<div class='agentInfoRow'><div>" + val.email  + "</div><div>" + val.info + "</div></div>";
							$('#agentInfoContainer').append(agentInfoValue);
						});
					}
		
					$('#agentInfoContainer').show('slow');
				}, 'json');			
		}
		
		return constructor.call(null);
	}

	return Agency;
});