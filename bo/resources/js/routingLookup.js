require(['classes/Lookup'], function() {
	Lookup = require('classes/Lookup');
	lookup = new Lookup();
	
	lookup.open();
});

/* Router wird initialisiert */
var router = new Router({
    mode: 'history',
	root: '/bo/public/',
    page404: function (path) {
		alert(path);
	
        console.log('"/' + path + '" Page not found');
    }
});

router.add('profile', function () {
   lookup.changeProfile();
});

router.add('logout', function () {
    window.location.href = "../index.php?logout";
});

router.addUriListener();
$('a').on('click', (event) => {
	event.preventDefault();
	const target = $(event.target);
	const path = target.attr('href');
	router.navigateTo(path);
});

function prepareCol(col, newClass) {
	col.removeClass();
	col.addClass('mainCol ' + newClass);
}

function clearContend() {
	$('#mainColLeft').html(null);
	$('#mainColMiddle').html(null);
	$('#mainColRicht').html(null);
}

function getUrlParam(name) {
    var url_string = window.location;
    var url = new URL(url_string);
    var c = url.searchParams.get("name");
    return c;
}