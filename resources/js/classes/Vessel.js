define(function() {

	/*
	 *	Klasse Vessel - Funktionalität der Seite Vessel
	 */
	var Vessel = function() {
		var constructor, that = {}, my = {};
	
		constructor = function() {
			return that;
		}
		
		/*
		 *	Öffnet die Seite Vessel
		 */
		that.open = function() {
			clearContend();
			prepareCol($('#mainColLeft'), 'vesselSearchCol');
			prepareCol($('#mainColRight'), 'vesselContactCol');
			
			$.get('../views/vessel/search.view.php', function(data) {
				$('#mainColLeft').html(data);
			});
			
			$.get('../views/vessel/details.view.php', function(data) {
				$('#mainColMiddle').html(data);
			});
			
			$.get('../views/vessel/openContacts.view.php', function(data) {
				$('#mainColRight').html(data);
			});
		}
		
		/*
		 *	Sidebar Suche nach Schiffen
		 */
		that.searchVessel = function(expression) {
			$.get('../components/controller/searchController.php?type=vessel&expression=' + expression, function(data) {
				$('#searchResult').html(data);
			});				
		}

		/*
		 *	Öffnet die Details zu einem bestimmten Schiff
		 */		
		that.openDetails = function(id) {
			$.get('../views/vessel/details.view.php?id=' + id, function(data) {
				$('#mainColMiddle').html(data);
			});			
		}

		/*
		 *	Öffnet das Overlay Windows zur Eingabe eines neuen Schiffes 
		 */
		that.newVessel = function(id, searchValue) {
			$.get('../views/vessel/addVessel.view.php?id=' + id + '&searchValue=' + searchValue, function(data) {
				$('#windowLabel').html("Neues Schiff hinzufügen");
				$('#windowBody').html(data);
			});
			showWindow();	
		}

		/*
		 *	Speichert ein neues Schiff in der Datenbank 
		 */		
		that.addVessel = function(id) {
			event.preventDefault();
			newVesselValidate = new FormValidate($('#addVessel').serializeArray()); 

			if(!newVesselValidate.fieldsNotAllEmpty(Array('vesselName'))) {
				formValidate.setError(Array('Name'));
				formValidate.setErrorMessage('Bitte einen Namen eingeben.');
				return;
			}			
			if(!newVesselValidate.fieldsNotAllEmpty(Array('vesselIMO', 'vesselENI'))) {
				formValidate.setError(Array('IMO', 'ENI'));
				formValidate.setErrorMessage('Bitte eine IMO oder ENI eingeben.');
				return;
			}

			$.post('../components/controller/vesselController.php', {type: 'addVessel', id: id, data: newVesselValidate.getFormData()}, 
				function(data) {
					if(data.type == "error") {
						formValidate.setError(Array(data.msg.field));
						formValidate.setErrorMessage(data.msg.msg);
					}
					else {
						if(data.type == "added") {
							that.searchVessel(data.name);
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
		 *	Lädt über IMO oder MMSI des Schiffes Informationen nach
		 */		
		that.getData = function(number) {
			event.preventDefault();
			formValidate.clearAllError();

			if(!number) {
				formValidate.setError(Array('IMO', 'MMSI'));
				formValidate.setErrorMessage('Bitte eine IMO oder MMSI eingeben.');
				return;
			}
			else {
				$('#addVesselLoader').addClass("active");
				
				$.post('../components/controller/vesselController.php', {type: 'getVesselData', parameter: number}, 
					function(data) {
						$('#vesselName').val(data.name);
						$('#vesselIMO').val(data.imo);
						$('#vesselMMSI').val(data.mmsi);
						$('#vesselLanguage').append(data.language);
						$('#vesselTyp').dropdown('set selected', data.shipType)						
						$('#addVesselLoader').removeClass("active");
					}, "json");				
			}
		}

		/*
		 *	Lädt über die IMO Informationen zur Sprache der Crew
		 */			
		that.getLanguages = function(imo) {
			event.preventDefault();
			formValidate.clearAllError();

			if(!imo) {
				formValidate.setError(Array('IMO'));
				formValidate.setErrorMessage('Bitte eine IMO eingeben.');
				return;
			}
			else {
				$('#addVesselLoader').addClass("active");
				
				$.post('../components/controller/vesselController.php', {type: 'getVesselLanguages', parameter: imo}, 
					function(data) {
						$('#vesselLanguage').append(data);						
						$('#addVesselLoader').removeClass("active");
					});				
			}			
		}
		
		/*************************************************** Vessel Info ***************************************************/

		/*
		 *	Neue Allgemeine Information zu einem Schiff hinzufügen
		 */			
		that.newVesselInfo = function(vesselID, infoID, edit) {
			if(edit && !infoID) {
				alert('Bitte zuerst ein Element auswählen.');
			}
			else {
				$.get('../views/vessel/addVesselInfo.view.php?vesselID=' + vesselID + '&infoID=' + infoID, function(data) {
					$('#windowLabel').html("Neues Allgemeine Information hinzufügen");
					$('#windowBody').html(data);
				});
				showWindow();	
			}		
		}

		/*
		 *	Neue Allgemeine Information speichern
		 */			
		that.addVesselInfo = function(infoID) {
			event.preventDefault();
			newVesselInfoValidate = new FormValidate($('#addVesselInfo').serializeArray());
			
			if(!newVesselInfoValidate.fieldsNotAllEmpty(Array('vesselInfo'))) {
				formValidate.setError(Array('Info'));
				formValidate.setErrorMessage('Bitte eine Info eingeben.');
				return;
			}

			$.post('../components/controller/vesselController.php', {type: 'addVesselInfo', data: newVesselInfoValidate.getFormData(), infoID: infoID}, 
				function() {
					closeWindow();
					that.openDetails(newVesselInfoValidate.getFormData().vesselID);
				});				
		}

		/*
		 *	Vessel Info löschen
		 */			
		that.deleteVesselInfo = function(vesselID, infoID) {
			if(infoID) {
				if(confirm("Möchtest du das gewählte Element wirklich löschen?")) {
					$.post('../components/controller/vesselController.php', {type: 'deleteVesselInfo', infoID: infoID}, 
						function() {
							closeWindow();
							that.openDetails(vesselID);
						});	
				}
			}
			else {
				alert('Bitte zuerst ein Element auswählen.');
			}
		}
		
		/*************************************************** Vessel Contact ***************************************************/
		
		/*
		 *	Neuen Kontakt zu einem Schiff hinzufügen
		 */			
		that.newVesselContact = function(vesselID, contactID, edit) {
			if(edit && !contactID) {
				alert('Bitte zuerst ein Element auswählen.');
			}
			else {
				$.get('../views/vessel/addVesselContact.view.php?vesselID=' + vesselID + '&contactID=' + contactID, function(data) {
					$('#windowLabel').html("Neuen Kontakt hinzufügen");
					$('#windowBody').html(data);
				});
				showWindow();	
			}		
		}

		/*
		 *	Neuen Kontakt für ein Schiff speichern
		 */			
		that.addVesselContact = function(vesselID, ContactID) {
			event.preventDefault();			
			vesselContactValidate = new FormValidate($('#addVesselContact').serializeArray());

			$.post('../components/controller/vesselController.php', {type: "addVesselContact", data: vesselContactValidate.getFormData(), contactID: ContactID},
				function(data) {
					if (!$.isEmptyObject(data) && data.status == 'error') {
						formValidate.setError(Array(data.msg.field));
						formValidate.setErrorMessage(data.msg.msg);
						return;
					}

					closeWindow();
					that.openDetails(vesselID);
					// showOpenContacts();
				}, 'json');
		}

		/*
		 *	Vessel Contact löschen
		 */			
		that.deleteVesselContact = function(vesselID, contactID) {
			if(contactID) {
				if(confirm("Möchtest du das gewählte Element wirklich löschen?")) {
					$.post('../components/controller/vesselController.php', {type: 'deleteVesselContact', contactID: contactID}, 
						function() {
							closeWindow();
							that.openDetails(vesselID);
						});	
				}
			}
			else {
				alert('Bitte zuerst ein Element auswählen.');
			}
		}
		
		return constructor.call(null);
	}

	return Vessel;
});