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
			prepareCol($('#mainColLeft'), 'agencySearchCol');
			prepareCol($('#mainColRight'), 'notNeededCol');
		}
		
		return constructor.call(null);
	}

	return Agency;
});