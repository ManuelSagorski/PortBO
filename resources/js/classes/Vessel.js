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
			prepareCol($('#mainColLeft'), 'vesselSearchCol');
			prepareCol($('#mainColRight'), 'vesselContactCol');
		}
		
		return constructor.call(null);
	}

	return Vessel;
});