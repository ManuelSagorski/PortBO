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
			prepareCol($('#mainColLeft'), 'portSearchCol');
			prepareCol($('#mainColRight'), 'notNeededCol');
		}
		
		return constructor.call(null);
	}

	return Port;
});