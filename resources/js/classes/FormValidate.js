define(function() {

	/*
	 *	Klasse formValidate - Funktionen zur Validierung von Formularen
	 */
	var FormValidate = function(formData) {
		var constructor, that = {}, my = {};
	
		constructor = function(formData) {
			my.formData = formData;
			return that;
		}
		
		/*
		 *	Funktion überprüft das ein Input-Feld nicht leer ist
		 */
		that.validateNotEmpty = function(field, defaultValue) {
			my.clearMessages();
			
			validateField = my.formData.filter(p => p.name == field);

			if(!validateField[0].value || validateField[0].value == defaultValue) {
				$('#msg' + my.ucFirst(validateField[0].name)).addClass("errorMsgForm");
				$('#msg' + my.ucFirst(validateField[0].name)).html("Bitte dieses Feld befüllen.");
				
				return false;
			}
			
			return true;
		}

		/*
		 *	Funktion zur Überprüfung der Passwort Änderung.
		 *	Prüft das die eingegebenen neuen Passworter übereinstimmen.
		 */		
		that.validateEqual = function(field1, field2) {
			my.clearMessages();
			
			validateField1 = my.formData.filter(p => p.name == field1);
			validateField2 = my.formData.filter(p => p.name == field2);
			
			if(validateField1[0].value != validateField2[0].value) {
				$('[id^=msgSecretNew]').addClass("errorMsgForm");
				$('[id^=msgSecretNew]').html("Die eingegebenen Passwörter stimmen nicht überein.");
				
				return false;
			}
			
			return true;
		}
		
		/*
		 *	Umwandelung der Formulardaten in ein Array
		 */
		that.getFormData = function(){
		    var unindexed_array = my.formData;
		    var indexed_array = {};
		
		    $.map(unindexed_array, function(n, i){
		        indexed_array[n['name']] = n['value'];
		    });
		
		    return indexed_array;
		}

		/*
		 *	Setzt alle bestehenden Fehlermeldungen zurück
		 */
		my.clearMessages = function() {
			$('[id^=msg]').removeClass("errorMsgForm");
			$('[id^=msg]').html(null);			
		}

		/*
		 *	Erster Buchstabe in einem String groß schreiben
		 */
		my.ucFirst = function(string) {
		    return string.substring(0, 1).toUpperCase() + string.substring(1);
		}

		return constructor.call(null, formData);
	}

	return FormValidate;
});