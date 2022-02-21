define(function() {

	/*
	 *	Klasse Vessel - Funktionalität der Seite Vessel
	 */
	var Vessel = function() {
		var constructor, that = {}, my = {};
	
		my.CONTROLLER = '../components/controller/vessel/';
		my.SEARCH_CONTROLLER = '../components/controller/search/';
		my.VIEW_FOLDER = '../views/vessel/';
		
		my.SEARCH_VIEW = my.VIEW_FOLDER + 'search.view.php?';
		my.DETAILS_VIEW = my.VIEW_FOLDER + 'details.view.php?';
		my.OPEN_CONTACTS_VIEW = my.VIEW_FOLDER + 'openContacts.view.php?';
		my.FORECAST_VIEW = my.VIEW_FOLDER + 'forecast.view.php';
		my.LINKS_VIEW = my.VIEW_FOLDER + 'externLinks.view.php';

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
			
			$.get(my.SEARCH_VIEW, function(data) {
				$('#mainColLeft').html(data);
			});
			
			$.get(my.DETAILS_VIEW, function(data) {
				$('#mainColMiddle').html(data);				
				that.getForecast();
				that.getExternLinks();
			});
			
			$.get(my.OPEN_CONTACTS_VIEW, function(data) {
				$('#mainColRight').html(data);
			});
		}

		/*
		 *	Lädt den Forecast
		 */		
		that.getForecast = function(accordionID) {
			$('#forecastLoader').show();
			
			$.get(my.FORECAST_VIEW, function(data) {
				$('#vesselForecast').html(data);
				
				$('#forecastLoader').hide();
				
				if(scrollPosition !== null && forecastAccordionOpen !== null) {
					$('.ui.accordion').accordion('open', forecastAccordionOpen);
					window.scrollTo(0, scrollPosition);

					scrollPosition = null;
				}
			});			
		}

		/*
		 *	Lädt die externen Links
		 */		
		that.getExternLinks = function() {
			$.get(my.LINKS_VIEW, function(data) {
				$('#externLinks').html(data);
			});			
		}

		/*
		 *	Erledigt eine Forecast Position
		 */			
		that.forecastItemDone = function(id, that) {
			$.post(my.CONTROLLER + 'forecastItemDone', { id: id }, 
				function() {
					$(that).closest("tr").addClass('forecastDisabled');
					$(that).closest("td").append('<a onClick="vessel.forecastItemReopen(' + id + ', this);"><i class="undo icon"></i></a>');
					$(that).remove();
				});			
		}

		/*
		 *	Öffnet eine Forecast Position
		 */			
		that.forecastItemReopen = function(id, that) {
			$.post(my.CONTROLLER + 'forecastItemReopen', { id: id }, 
				function() {
					$(that).closest("tr").removeClass('forecastDisabled');
					$(that).closest("td").append('<a onClick="vessel.forecastItemDone(' + id + ', this);"><i class="check icon"></i></a>');
					$(that).remove();
				});			
		}

		/*
		 *	Löscht eine Forecast Position
		 */			
		that.forecastItemRemove = function(id, that) {
			$.post(my.CONTROLLER + 'forecastItemRemove', { id: id }, 
				function() {
					$(that).closest("tr").remove();
				});			
		}
		
		/*
		 *	Sidebar Suche nach Schiffen
		 */
		that.searchVessel = function(expression) {
			var searchLimit = 20;
			if(!mql.matches) {
				searchLimit = 3;
			}
			
			$.get(my.SEARCH_CONTROLLER + 'vessel/?searchLimit=' + searchLimit + '&expression=' + expression, function(data) {
				$('#searchResult').html(data);
			});
			if(expression != "") {
				$.get(my.SEARCH_CONTROLLER + 'vesselDrySearch/?expression=' + expression, function(data) {
					$('#drySearchResult').html(data);
				});
			}
			else {
				$('#drySearchResult').html("");
			}
		}

		/*
		 *	Öffnet die Details zu einem bestimmten Schiff
		 */		
		that.openDetails = function(id) {
			$.get(my.DETAILS_VIEW + 'id=' + id, function(data) {
				$('#mainColMiddle').html(data);
				$(window).scrollTop(0);
				//$('#mainColMiddle').get(0).scrollIntoView();
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
				formValidate.setError(Array('vesselName'));
				formValidate.setErrorMessage('Bitte einen Namen eingeben.');
				return;
			}			
			if(!newVesselValidate.fieldsNotAllEmpty(Array('vesselIMO', 'vesselENI'))) {
				formValidate.setError(Array('vesselIMO', 'vesselENI'));
				formValidate.setErrorMessage('Bitte eine IMO oder ENI eingeben.');
				return;
			}

			$.post(my.CONTROLLER + 'addVessel', {
					id: id, 
					data: newVesselValidate.getFormData()
				}, 
				function(data) {
					if(data.type == "error") {
						formValidate.setError(Array(data.msg.field));
						formValidate.setErrorMessage(data.msg.msg);
					}
					else {
						if(data.type == "added") {
							that.searchVessel(data.imo);
							that.openDetails(data.id);
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
				formValidate.setError(Array('vesselIMO', 'vesselMMSI'));
				formValidate.setErrorMessage('Bitte eine IMO oder MMSI eingeben.');
				return;
			}
			else {
				$('#addVesselLoader').addClass("active");
				
				$.post(my.CONTROLLER + 'getVesselData', { parameter: number }, 
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
				formValidate.setError(Array('vesselIMO'));
				formValidate.setErrorMessage('Bitte eine IMO eingeben.');
				return;
			}
			else {
				$('#addVesselLoader').addClass("active");
				
				$.post(my.CONTROLLER + 'getVesselLanguages', { parameter: imo }, 
					function(data) {
						alert(data);
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
				formValidate.setError(Array('vesselInfo'));
				formValidate.setErrorMessage('Bitte eine Info eingeben.');
				return;
			}

			$.post(my.CONTROLLER + 'addVesselInfo', {
					data: newVesselInfoValidate.getFormData(), 
					infoID: infoID
				}, 
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
					$.post(my.CONTROLLER + 'deleteVesselInfo', { infoID: infoID }, 
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

			$.post(my.CONTROLLER + 'addVesselContact', {
					data: vesselContactValidate.getFormData(), 
					contactID: ContactID
				},
				function(data) {
					if (!$.isEmptyObject(data) && data.status == 'error') {
						formValidate.setError(Array(data.msg.field));
						formValidate.setErrorMessage(data.msg.msg);
						return;
					}

					closeWindow();
					that.openDetails(vesselID);
					
					$.get(my.OPEN_CONTACTS_VIEW, function(data) {
						$('#mainColRight').html(data);
					});

				}, 'json');
		}

		/*
		 *	Vessel Contact löschen
		 */			
		that.deleteVesselContact = function(vesselID, contactID) {
			if(contactID) {
				if(confirm("Möchtest du das gewählte Element wirklich löschen?")) {
					$.post(my.CONTROLLER + 'deleteVesselContact', { contactID: contactID }, 
						function() {
							closeWindow();
							that.openDetails(vesselID);
							
							$.get(my.OPEN_CONTACTS_VIEW, function(data) {
								$('#mainColRight').html(data);
							});							
						});	
				}
			}
			else {
				alert('Bitte zuerst ein Element auswählen.');
			}
		}
		
		/*************************************************** Vessel Contact Details ***************************************************/
		
		/*
		 *	Neue Kontaktinformation zu einem Schiff hinzufügen
		 */			
		that.newVesselContactDetail = function(vesselID, contactDetailID, edit) {
			if(edit && !contactDetailID) {
				alert('Bitte zuerst ein Element auswählen.');
			}
			else {
				$.get('../views/vessel/addVesselContactDetail.view.php?vesselID=' + vesselID + '&contactDetailID=' + contactDetailID, function(data) {
					$('#windowLabel').html("Neue Kontaktinformation hinzufügen");
					$('#windowBody').html(data);
				});
				showWindow();	
			}		
		}
		
		/*
		 *	Neue Kontaktinformation in der Datenbank speichern
		 */			
		that.addVesselContactDetail = function(vesselID, contactDetailID) {
			event.preventDefault();			
			vesselContactDetailValidate = new FormValidate($('#addVesselContactDetail').serializeArray());

			if(!vesselContactDetailValidate.fieldsNotAllEmpty(Array('contactDetail'))) {
				formValidate.setError(Array('contactDetail'));
				formValidate.setErrorMessage('Bitte eine Kontaktinformation eingeben.');
				return;
			}	

			$.post(my.CONTROLLER + 'addVesselContactDetail', {
					data: vesselContactDetailValidate.getFormData(), 
					contactDetailID: contactDetailID
				},
				function(data) {
					closeWindow();
					that.openDetails(vesselID);
				});
		}

		/*
		 *	Kontaktinformation in der Datenbank löschen
		 */			
		that.deleteVesselContactDetail = function(vesselID, contactDetailID) {
			if(contactDetailID) {
				if(confirm("Möchtest du das gewählte Element wirklich löschen?")) {
					$.post(my.CONTROLLER + 'deleteVesselContactDetail', { contactDetailID: contactDetailID }, 
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
		
		/*************************************************** Forecast ***************************************************/
		
		that.addForecast = function(formID) {
			event.preventDefault();
			newForecastValidate = new FormValidate($('#' + formID).serializeArray());

			if(falseFields = newForecastValidate.fieldsNotEmpty(Array('name'))) {
				alert('Bitte einen Namen eingeben.');
				return;
			}
			
			$.post(my.CONTROLLER + 'addForecast', { data: newForecastValidate.getFormData() },
				function(data) {
					that.getForecast(newForecastValidate.getFormData().accordionID);				
				});
		}
		
		return constructor.call(null);
	}

	return Vessel;
});