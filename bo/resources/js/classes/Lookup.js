define(function() {

	/*
	 *	Klasse Lookup - Funktionalität der Seite Lookup
	 */
	var Lookup = function() {
		var constructor, that = {}, my = {};

		my.SEARCH_CONTROLLER = '../components/controller/searchController.php?';
		my.VESSEL_CONTROLLER = '../components/controller/vesselController.php?';
		my.USER_CONTROLLER = '../components/controller/userController.php';
		my.SEARCH_VIEW = '../views/lookup/search.view.php?';
		my.DETAILS_VIEW = '../views/lookup/details.view.php?';	
	
		constructor = function() {
			return that;
		}
		
		/*
		 *	Öffnet die Seite Vessel
		 */
		that.open = function() {
			clearContend();
			prepareCol($('#mainColLeft'), 'lookupSearchCol');
			
			$.get(my.SEARCH_VIEW, function(data) {
				$('#mainColLeft').html(data);
			});
			
			$.get(my.DETAILS_VIEW, function(data) {
				$('#mainColMiddle').html(data);				
			});

		}
		
		/*
		 *	Sidebar Suche nach Schiffen
		 */
		that.searchVessel = function(expression) {
			$.get(my.SEARCH_CONTROLLER + 'type=vesselLookup&expression=' + expression, function(data) {
				$('#searchResult').html(data);
			});				
		}
		
		/*
		 *	Öffnet die Details zu einem bestimmten Schiff
		 */		
		that.openDetails = function(id) {
			$.get(my.DETAILS_VIEW + 'id=' + id, function(data) {
				$('#mainColMiddle').html(data);
				$('#mainColMiddle').get(0).scrollIntoView();
			});			
		}

		/*
		 *	Anfragen von mehr Informationen zu diesem Schiff
		 */			
		that.requestInformation = function(vesselID) {
			if(!confirm("Do you realy want to request more information about this vessel from the North German Harbour Group?")) {
				return;
			}
			$.post(my.VESSEL_CONTROLLER, {
					type: 'lookupRequestInformation', 
					id: vesselID
				}, 
				function() {
					$('#requestMsg').html('Your request has been transmitted. We will answer you as soon as possible!');
				});	
		}
		
		that.changeProfile = function() {
			$.get('../views/lookup/profile.view.php', function(data) {
				$('#windowLabel').html("Update profile");
				$('#windowBody').html(data);
			});
			showWindow();
		}
		
		that.changePassword = function(formID) {
			event.preventDefault();
			changePasswordValidate = new FormValidate(null, formID);
			
			if(!changePasswordValidate.fieldsNotAllEmpty(Array('secretNew1', 'secretNew2'))) {
				changePasswordValidate.setError(Array('secretNew1', 'secretNew2'));
				changePasswordValidate.setErrorMessage('Please two times enter your new password.');
				return;
			}
			
			if(!changePasswordValidate.fieldsEqual()) {
				return;
			}
			
			$.post(my.USER_CONTROLLER, { type: 'userChangePassword', data: changePasswordValidate.getFormData() }, 
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
				changeMailValidate.setErrorMessage('Please enter a valid email adress.');
				return;
			}
			
			$.post(my.USER_CONTROLLER, { type: 'userChangeMail', data: changeMailValidate.getFormData() }, 
				function() {
					changeMailValidate.setSuccessMessage();
					$('#emailNew').val('');
					$('#actualEmail').html(changeMailValidate.getFormData().emailNew);
				});
		}
		
		return constructor.call(null);
	}

	return Lookup;
});