define(function() {

	/*
	 *	Klasse Port - Funktionalität der Seite Port
	 */
	var Port = function() {
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
		}
		
		return constructor.call(null);
	}

	return Port;
});