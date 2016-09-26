define(['zepto', 'location', 'async!http://maps.google.com/maps/api/js?sensor=false&v=3'], function($, Location) {
	///
	/// Maps.
	///
	
	var Observer = function() {
	}
	
	Observer.prototype.update = function() {
		var location = window.__LOCATION__,
			closest = null;
			
/* 		console.log(location.message); */
		
		/*
if(!location.success) {
			return false;
		}
*/
			
		if (typeof(Number.prototype.toRad) === "undefined") {
			Number.prototype.toRad = function() {
				return this * Math.PI / 180;
			}
		}
		
		function distance(loc1, loc2) {
			var R = 6371; // km
			var dLat = (loc2.lat()-loc1.lat()).toRad();
			var dLon = (loc2.lng()-loc1.lng()).toRad();
			var lat1 = (loc1.lat()).toRad();
			var lat2 = (loc2.lat()).toRad();
			
			var a = Math.sin(dLat/2) * Math.sin(dLat/2) +
			        Math.sin(dLon/2) * Math.sin(dLon/2) * Math.cos(lat1) * Math.cos(lat2); 
			var c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a)); 
			
			return R * c;
		}
		
		function callback() {
			if(!window.__LOCATION__.success) {
				return false;
			}
			
			var userLocation = new google.maps.LatLng(window.__LOCATION__.lat, window.__LOCATION__.lon);
			
			for(var i in window.storeData) {
				
				for(var j in window.storeData[i]) {
					var current = window.storeData[i][j];
					
					for(var k in current.Data) {
						
						if(current.Data[k].Lat != 0 && current.Data[k].Lon != 0) {
							var dist = distance(userLocation, new google.maps.LatLng(current.Data[k].Lat, current.Data[k].Lon));
							
							if(closest == null || parseFloat(dist) < parseFloat(closest.distance)) {
								closest = {
									distance: dist,
									data: current.Data[k]
								};
							}
						}
					}
				}
			}
			
			
			if(closest) {
				closest = closest.data;
				addTemplate(closest);
				$('#search').val(closest.Suburb == 'default' ? closest.City : closest.Suburb);
					
			}
		}
		
		
		loadStores(callback);
	};
	
	var observer = new Observer();
	
	var _template = $('<div class="stockist"/>'),
		location = new Location([observer]);
	
	_template.append($('<a target="_blank"/>'));
	_template.find('a').append($('<img/>'));
	_template.find('a').append($('<strong/>'));
	_template.find('a').append($('<span/>'));
	
	function addTemplate(data) {
		newTemplate = _template.clone();
					
		newTemplate.find('a').attr('href', 'https://maps.google.co.nz/maps?q=' + data.Name + ',' + data.Address + (data.Suburb != 'default' ? ', ' + data.Suburb : '') + ',' + data.City + ',New Zealand&ll=' + data.Lat + ',' + data.Lon);
		newTemplate.find('strong').text(data.Name);
		newTemplate.find('span').text(data.Address + (data.Suburb != 'default' ? ', ' + data.Suburb : ''));
		
		$('#map').append(newTemplate);
		newTemplate.find('img').attr('src', 
			'http://maps.googleapis.com/maps/api/staticmap?' +
			'center=' + data.Lat + ',' + data.Lon +
			'&zoom=18&size=480x160&sensor=false' +
			'&markers=icon:http://www.zeffer.co.nz/themes/default/img/stockist_marker.png|shadow:true|' + data.Lat + ',' + data.Lon
		);
	}
	
	
	
	$('select').on('change', function(e) {
		var sel = document.getElementById("search"),
			city = $(sel.options[sel.selectedIndex]).data('city'),
			type = $(sel.options[sel.selectedIndex]).data('type'),
			data = null;
			
		if(type === 'suburb') {
			data = window.storeData[city][$(this).val()];
		} else {
			data = window.storeData[city]['default'];
		}
		
		if(data) {
			if(data.Data.length > 0) {
				$('#map').empty();
				
				for(var i=0; i<data.Data.length; i++) {
					var current = data.Data[i];
					addTemplate(current);
				}
			}
		}
	});
	
	function loadStores(callback) {
		$.ajax({
			type: 'GET',
			url: '/themes/default/json/stockists.json',
			dataType: 'json',
			success: function(data) {
				var stores = data,
					i = 0,
					markers = [],
					searchStrings = [];
				
				window.storeData = stores;
				callback();
			}
		});
	}
});