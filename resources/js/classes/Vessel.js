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
		 *	Öffnet das Overlay Windowszur Eingabe eines neuen Schiffes 
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
		
		return constructor.call(null);
	}

	return Vessel;
});