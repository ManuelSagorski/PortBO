define(function() {

	/*
	 *	Klasse Port - Funktionalität der Seite Port
	 */
	var PortC = function() {
		var constructor, that = {}, my = {};
	
		constructor = function() {
			return that;
		}
		
		/*
		 *	Öffnet die Seite Port
		 */
		that.open = function() {
			clearContend();
			prepareCol($('#mainColLeft'), 'portSearchCol');
			prepareCol($('#mainColRight'), 'notNeededCol');
			
			$.get('../views/port/search.view.php', function(data) {
				$('#mainColLeft').html(data);
			});
			
			$.get('../views/port/details.view.php', function(data) {
				$('#mainColMiddle').html(data);
			});
		}

		/*
		 *	Öffnet die Details zu einem Hafen
		 */		
		that.openDetails = function(portID) {
			$.get('../views/port/details.view.php?id=' + portID, function(data) {
				$('#mainColMiddle').html(data);
			});	
		}
		
		/*
		 *	Öffnet das Fenster zum hinzufügen eines neuen Hafens
		 */			
		that.newPort = function(id) {
			$.get('../views/port/addPort.view.php?id=' + id, function(data) {
				$('#windowLabel').html("Neuen Hafen hinzufügen");
				$('#windowBody').html(data);
			});
			showWindow();			
		}

		/*
		 *	Speichert einen neuen Hafen in der Datenbank
		 */			
		that.addPort = function(id) {
			event.preventDefault();
			newPortValidate = new FormValidate($('#addPort').serializeArray());
			
			if(!newPortValidate.fieldsNotAllEmpty(Array('portName'))) {
				formValidate.setError(Array('Name'));
				formValidate.setErrorMessage('Bitte einen Namen eingeben.');
				return;
			}
			if(!newPortValidate.fieldsNotAllEmpty(Array('portShort'))) {
				formValidate.setError(Array('Short'));
				formValidate.setErrorMessage('Bitte ein Kürzel eingeben.');
				return;
			}
			
			$.post('../components/controller/portController.php', {type: 'addPort', id: id, data: newPortValidate.getFormData()}, 
				function() {
					$.get('../views/port/search.view.php', function(data) {
						$('#mainColLeft').html(data);
					});
					if(id) {
						that.openDetails(id);
					}					
					closeWindow();
				});
		}

		/*
		 *	Öffnet das Fenster zum hinzufügen eines neuen Liegeplatzes
		 */				
		that.newCompany = function(portID, companyID) {
			$.get('../views/port/addPortCompany.view.php?portID=' + portID  + '&id=' + companyID, function(data) {
				$('#windowLabel').html("Neuen Liegeplatz hinzufügen");
				$('#windowBody').html(data);
			});
			showWindow();
		}

		/*
		 *	Speichert einen neuen Liegeplatz in der Datenbank
		 */			
		that.addPortCompany = function(companyID) {
			event.preventDefault();
			newPortCompanyValidate = new FormValidate($('#addPortCompany').serializeArray());
			
			if(!newPortCompanyValidate.fieldsNotAllEmpty(Array('companyName'))) {
				formValidate.setError(Array('Name'));
				formValidate.setErrorMessage('Bitte einen Namen eingeben.');
				return;
			}
			
			$.post('../components/controller/portController.php', {type: 'addPortCompany', companyID: companyID, data: newPortCompanyValidate.getFormData()}, 
				function() {
					that.openDetails(newPortCompanyValidate.getFormData().portID);
					closeWindow();
				});
		}

		/*
		 *	Löscht einen Liegeplatz
		 */			
		that.deleteCompany = function(portID, companyID) {
			if(companyID) {
				if(confirm("Möchtest du den gewählten Liegeplatz wirklich löschen?")) {
					$.post('../components/controller/portController.php', {type: 'deletePortCompany', id: companyID}, 
						function() {
							that.openDetails(portID);
							closeWindow();
						});
				}
			}
			else {
				alert('Bitte zuerst einen Liegeplatz auswählen.');
			}
		}
		
		return constructor.call(null);
	}

	return PortC;
});