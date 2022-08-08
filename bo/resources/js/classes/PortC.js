define(function() {

	/*
	 *	Klasse Port - Funktionalität der Seite Port
	 */
	var PortC = function() {
		var constructor, that = {}, my = {};
	
		my.CONTROLLER = '../components/controller/port/';

		that.forecastPortOpen = null;
		that.forecastScrollPosition = null;
	
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
				$('#windowLabel').html(t('add-port'));
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
	
			if(falseFields = newPortValidate.fieldsNotEmpty(Array('portName', 'portShort',))) {
				formValidate.setError(falseFields);
				formValidate.setErrorMessage(t('insert-mendatory'));
				return;
			}
			
			$.post(my.CONTROLLER + 'addPort', {id: id, data: newPortValidate.getFormData()}, 
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
		 *	Löscht einen bestehenden Hafen
		 */	
		that.deletePort = function(portID) {
			if(confirm(t('confirm-delete-port'))) {
				$.post(my.CONTROLLER + 'deletePort', {id: portID}, 
					function() {
						that.open();
					});
			}
		}

		/*
		 *	Öffnet das Fenster zum hinzufügen eines neuen Liegeplatzes
		 */				
		that.newCompany = function(portID, companyID) {
			$.get('../views/port/addPortCompany.view.php?portID=' + portID  + '&id=' + companyID, function(data) {
				$('#windowLabel').html(t('add-company'));
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
				formValidate.setError(Array('companyName'));
				formValidate.setErrorMessage(t('insert-name'));
				return;
			}
			
			$.post(my.CONTROLLER + 'addPortCompany', {companyID: companyID, data: newPortCompanyValidate.getFormData()}, 
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
				if(confirm(t('confirm-delete-company'))) {
					$.post(my.CONTROLLER + 'deletePortCompany', {id: companyID}, 
						function() {
							that.openDetails(portID);
							closeWindow();
						});
				}
			}
			else {
				alert(t('choose-element'));
			}
		}

		/*
		 *	Lädt den Forecast für einen bestimmten Hafen
		 */		
		that.loadForecast = function(portID, scrollTo) {
			that.forecastPortOpen = portID;
			
			$('.portForecastButton').removeClass('active');
			$('#portButton' + portID).addClass('active');
			
			$.get('../views/port/portLiveMap.view.php?portID=' + portID, function(data) {
				$('#vesselFinderMap').html(data);
			});
			$.get('../views/port/portForecast.view.php?portID=' + portID, function(data) {
				$('#portForecast').html(data);
				
				if(scrollTo !== null) {
					window.scrollTo(0, scrollTo);
					that.forecastScrollPosition = null;
				}
			});
		}

		/*
		 *	Speichert die aktuelle Scroll-Position im Forecast
		 */		
		that.safeForecastPosition = function() {
			that.forecastScrollPosition = window.pageYOffset;
		}
		
		return constructor.call(null);
	}

	return PortC;
});