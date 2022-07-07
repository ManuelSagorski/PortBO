define(function() {

	/*
	 *	Klasse formValidate - Funktionen zur Validierung von Formularen
	 */
	var FormValidate = function(formData, formID) {
		var constructor, that = {}, my = {};
	
		constructor = function(formData, formID) {
			if(formData) {
				my.formData = formData;				
			}
			if(formID) {
				my.formID = formID;
				my.formData = $('#' + formID).serializeArray();
			}
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

		/****************************** SemanticUI Forms ******************************/
		
		/*
		 *	Entfernt die Error Markierung bei allen angezeigten Input Feldern
		 */		
		that.clearAllError = function() {
			$('[id^=input_]').removeClass("error");
			
			$('form').each(function() {
				$(this).find('#errorMessage').html(null);
				$(this).removeClass("error");
			});
		}

		/*
		 *	Setzt auf die übergebenen Felder eine Error Markierung
		 */			
		that.setError = function(fields) {
			fields.forEach(field => $('#input_' + field).addClass("error"));
		}

		/*
		 *	Setzt für das Formular eine Fehlermeldung
		 */			
		that.setErrorMessage = function(message) {
			if(my.formID) {
				$('#' + my.formID).find('#errorMessage').html(message);
				$('#' + my.formID).addClass("error");
			}else {
				$('#errorMessage').html(message);
				$('#errorMessage').parent().parent().addClass("error");
			}
		}

		/*
		 *	Setzt für das Formular eine Success Meldung
		 */			
		that.setSuccessMessage = function(message) {
			if(my.formID) {
				if(message) {
					$('#' + my.formID).find('#successMessage').html(message);
				}
				$('#' + my.formID).addClass("success");
			}else {
				if(message) {
					$('#successMessage').html(message);
				}
				$('#errorMessage').parent().parent().addClass("success");
			}
		}

		/*
		 *	Überprüft das alle übergebenen Felder nicht leer sind
		 */
		that.fieldsNotEmpty = function(fields) {
			var returnValue = [];
			
			fields.forEach(function(field){
				validateField = my.formData.filter(p => p.name == field);
				if(validateField[0].value == '') {
					returnValue.push(field);
				}
			});
			
			if($.isEmptyObject(returnValue)) {
				return false;
			}
			else {
				return returnValue;
			}
		}

		/*
		 *	Überprüft das mindestens eines der übergebenen Felder nicht leer ist
		 */
		that.fieldsNotAllEmpty = function(fields) {
			var returnValue = false;
			
			fields.forEach(function(field){
				validateField = my.formData.filter(p => p.name == field);
				if(validateField[0].value != '') {
					returnValue = true;
				}
			});
			return returnValue;
		}

		/*
		 *	Überprüft das zwei Felder den gleichen Inhalt haben (Passwortänderung)
		 */
		that.fieldsEqual = function() {
			if(my.formData.filter(p => p.name == 'secretNew1')[0].value != my.formData.filter(p => p.name == 'secretNew2')[0].value) {
				that.setError(['secretNew1', 'secretNew2']);
				that.setErrorMessage('Die eingegebenen Passwörter stimmen nicht überein.');
				
				return false;
			}
			else {
				return true;
			}
		}

		/*
		 *	Überprüft das in einem Feld eine valide Email Adresse eingegeben wurde
		 */
		that.fieldEmail = function(field) {
			return String(my.formData.filter(p => p.name == field)[0].value).toLowerCase()
				.match(/[a-z0-9!#$%&'*+/=?^_`{|}~-]+(?:\.[a-z0-9!#$%&'*+/=?^_`{|}~-]+)*@(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\.)+[a-z0-9](?:[a-z0-9-]*[a-z0-9])?/);
		}

		/**
		 * Setzt bei den über _selector_ ausgewählten Inputs das type- und das pattern-Attribut
		 * in Abhängigkeit von _type_.
		 * @param {string} selector
		 * @param {string} type
		 */
		that.setInputValidation = function (selector, type) {
			if (!selector) return;
			if (!type) type = '';
			var element = $(selector).filter('input');

			switch (type.toLowerCase()) {
				case 'email':
					element
						.attr('type', 'email')
						.removeAttr('pattern');
					break;

				case 'tel':
				case 'telefon':
					element
						.attr('type', 'tel')
						.attr('pattern', '\\+[0-9/ -]+');
					break;

				default:
					element
						.attr('type', 'text')
						.removeAttr('pattern');
					break;
			}
		}

		return constructor.call(null, formData, formID);
	}

	return FormValidate;
});