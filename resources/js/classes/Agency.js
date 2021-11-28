define(function() {

	/*
	 *	Klasse Agency - Funktionalität der Seite Agency
	 */
	var Agency = function() {
		var constructor, that = {}, my = {};
	
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
		}
		
		/*
		 *	Lädt für ein Formular Informationen zu einem bestimmten Agenten nach
		 */		
		that.loadAgencyInfoForInput = function(agency) {
			$.post('../components/controller/agencyController.php', {type: "getAgencyInfo", agency: agency, port: $('#contactPort').val()}, 
				function(data) {
					if(Object.keys(data).length === 0) {
						var heading = "<div class='agentInfoHead'>Keine Kontaktinformationen für " + agency + " in " + $('#contactPort :selected').html() + " vorhanden</div>";
						$('#agentInfoContainer').html(heading);
					}
					else {			
						$.each(data, function(i, val) {
							if(i==0) {
								var heading = "<div class='agentInfoHead'>Kontaktinformationen für " + val.agencyName + " in " + val.portName + "</div>";
								$('#agentInfoContainer').html(heading);
								var lastContactInfo = "<div class='agentInfoRow'>Letzter Kontakt: " + val.lastContact + "</div>";
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