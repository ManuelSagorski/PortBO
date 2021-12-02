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
		
		return constructor.call(null);
	}

	return Settings;
});