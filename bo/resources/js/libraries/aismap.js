(function() {

	function isObject(obj) { 
		return obj != null && obj.constructor.name === "Object"
	}

	var obj = window.map_options_name && window.map_options_name !== '' && isObject(window[window.map_options_name]) 
    	? window[window.map_options_name]
    	: window;

	function parseBoolValue(value) {
		var new_value;
				
		if (value === undefined) { 
			new_value = false; 
		}
		if (value === 'true' || value == true) { 
			new_value = true; 
		}
		else if (value === 'false' || value == false) { 
			new_value = false; 
		}
		else { 
			new_value = false; 
		}
		return new_value;
};

var rh=window.location.href;

if (rh.indexOf('file://') >= 0 || rh.indexOf('file%3A%2F%2F') >= 0) rh = 'testingonly';

rh=encodeURIComponent(rh);

var w = obj.width; 

if (w === undefined) { 
	w = 800; 
}

var ws = w.toString();

if ((parseInt(w) < 480 && ws.charAt(ws.length-1) !=='%')) { 
	w = 480; 
}

if (ws.charAt(ws.length-1) === '%') { 
	obj.width = ws; 
} 
else { 
	obj.width = parseInt(w); 
}

var h = obj.height; if (h === undefined) { h = 600; }
var height = parseInt(h);
if (height < 400) { obj.height = 400; } else { obj.height = height; }

if (obj.names === undefined) { obj.names = false;} else { obj.names = parseBoolValue(obj.names); }

obj.show_track = parseBoolValue(obj.show_track);

obj.click_to_activate = parseBoolValue(obj.click_to_activate);
var f = (typeof(obj.fleet) === 'undefined') ? false : obj.fleet;
if (f!==false && f.indexOf('@') >= 0) {f = false;}
var fn = (typeof(obj.fleet_name) === 'undefined') ? false : encodeURIComponent(obj.fleet_name);
if (fn!==false && fn.indexOf('@') >= 0) {fn = false;}

if (obj.store_position === undefined) { obj.store_position=true; } else { obj.store_position = parseBoolValue(obj.store_position); }
if(obj.mmsi!==undefined && parseInt(obj.mmsi)!=obj.mmsi) { obj.mmsi=undefined; }
if(obj.imo!==undefined && parseInt(obj.imo)!=obj.imo) { obj.imo=undefined; }
if(obj.latitude!==undefined && parseFloat(obj.latitude)!=obj.latitude) { obj.latitude=undefined; }
if(obj.longitude!==undefined && parseFloat(obj.longitude)!=obj.longitude) { obj.longitude=undefined; }
document.write(
'<iframe name="vesselfinder" id="vesselfinder" '
+ ' width="' + obj.width + '"'
+ ' height="' + obj.height + '"'
+ ' frameborder="0"'
+ ' src="https://www.vesselfinder.com/aismap?'
+ 'zoom=' + ((obj.zoom === undefined) ? 'undefined' : obj.zoom)
+ ((obj.latitude === undefined) ? '&amp;lat=undefined' : '&amp;lat='+obj.latitude)
+ ((obj.longitude === undefined) ? '&amp;lon=undefined' : '&amp;lon='+obj.longitude)
+ '&amp;width=' + obj.width
+ '&amp;height=' + obj.height
+ '&amp;names='+obj.names
+ ((obj.mmsi === undefined) ? '' : '&amp;mmsi=' + obj.mmsi)
+ ((obj.imo === undefined) ? '' : '&amp;imo=' + obj.imo)
+ '&amp;track=' + obj.show_track
+ ((obj.fleet === undefined) ? '&amp;fleet=false' : '&amp;fleet='+f)
+ ((obj.fleet_name === undefined) ? '&amp;fleet_name=false' : '&amp;fleet_name='+fn)
+ ((obj.fleet_timespan !== undefined) ? '&amp;fleet_timespan=' + obj.fleet_timespan : '')
+ ((obj.fleet_hide_old_positions === undefined) ? '&amp;fleet_hide_old_positions=false' : '&amp;fleet_hide_old_positions='+obj.fleet_hide_old_positions)
+ '&amp;clicktoact=' + obj.click_to_activate
+ '&amp;store_pos=' + obj.store_position
+ ((obj.fil === undefined) ? '' : '&amp;fil=' + obj.fil)
+ ((obj.default_maptype === undefined) ? '' : '&amp;default_maptype=' + obj.default_maptype)
+ '&amp;ra='+rh
+ '">Browser does not support embedded objects.<br/>Visit directly <a href="https://www.vesselfinder.com" target="_blank">www.vesselfinder.com</a>'
+ '</iframe>');
})();