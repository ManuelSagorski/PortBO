require(['routing']);

require(['classes/FormValidate'], function() {
	FormValidate = require('classes/FormValidate');
	formValidate = new FormValidate();
});

function showWindow() {
	$('body,html').animate({ scrollTop: 0 }, 400);
	$('#window').show();
}

function closeWindow() {
	$('#windowBody').html('');
	$('#window').hide();
}

$( function() {
	$( "#window" ).draggable({
		cancel: '.windowBody'
	});
});