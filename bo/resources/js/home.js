var scrollPosition = null;
var forecastAccordionOpen = null;

let mql = window.matchMedia("(min-width:600px)");

require.config({
    urlArgs: "bust=" + (new Date()).getTime()
});

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
	$('#window').css("width", 500);
}

$( function() {
	$( "#window" ).draggable({
		cancel: '.windowBody'
	});
});

function inputSearch(type, expression, parameter){
	$.get('../components/controller/search/' + type + '/?expression=' + expression + '&parameter=' + parameter, function(data) {
		switch (type) {
			case 'userForContact':
				$('#userSuggest').html(data);
				$('#userSuggest').addClass('visible');					
				break;
			case 'agentForContact':
				$('#agentSuggest').html(data);
				$('#agentSuggest').addClass('visible');					
				break;
			case 'companyForContact':
				$('#companySuggest').html(data);
				$('#companySuggest').addClass('visible');					
				break;
		}	
	});			
}

function hideInputSearchResults() {
	setTimeout(function(){ $("[id$=Suggest]").removeClass('visible'); }, 500);	
}

function selectSuggested(type, value) {
	switch (type) {
		case 'user':
			$('#contactName').val(value);
			break;
		case 'agent':
			$('#contactAgent').val(value);
			agency.loadAgencyInfoForInput(value);
			break;
		case 'company':
			$('#contactCompany').val(value);
			break;
	}
}

function safeForecastPosition() {
	scrollPosition = window.pageYOffset;
}

function acceptDataprotection() {
	$.post('../components/controller/user/acceptDataprotection', {}, 
	function() {
		$('#dataProtectionLayer').hide();
	});
}

$('.ui.dropdown.language').dropdown('setting', 'onChange', function(){
	$.post('../components/controller/login/changeLanguage', { language: $('.ui.dropdown.language').dropdown('get value') }, 
		function() {
			location.reload();
		});
});