(function($) {
	var map,
		activeInfoWindow = false;
		
	$('select').chosen().change(function(e) {
		var city = $('#search option:selected').data('city'),
			type = $('#search option:selected').data('type'),
			data = null;
			
		if(type === 'suburb') {
			data = window.storeData[city][$('#search option:selected').val()];
		} else {
			data = window.storeData[city]['default'];
		}
		
		
		if(data && map) {
			if(data.Data.length > 0) {
				var latlngbounds = new google.maps.LatLngBounds();
				for(var i=0; i<data.Data.length; i++) {
				  latlngbounds.extend(new google.maps.LatLng(data.Data[i].Lat, data.Data[i].Lon));
				}
				map.setCenter(new google.maps.LatLng(data.Lat, data.Lon));
				map.fitBounds(latlngbounds);
				map.setZoom(map.getZoom() > 16 ? 16 : map.getZoom());
			} else {
				map.panTo(new google.maps.LatLng(data.Lat, data.Lon));
				map.setZoom(12);
			}
			
		}
			
		//console.log(data, window.storeData, $('#search option:selected').val());
	});
	
	function initialize() {
		google.maps.visualRefresh = true;
		
		var mapOptions = {
			zoom: 5,
			center: new google.maps.LatLng(-41.24437, 174.761855),
			mapTypeId: google.maps.MapTypeId.ROADMAP,
			scrollwheel: false,
			mapTypeControl: false,
/* 			maxZoom: 16 */
		};
		map = new google.maps.Map(document.getElementById('map'), mapOptions);
		map.set('styles', [
		{
		"featureType": "water",
		"elementType": "geometry",
		"stylers": [
		  { "visibility": "on" },
		  { "color": "#6ea5aa" }
		]
		},{
		"featureType": "landscape.natural",
		"stylers": [
		  { "color": "#f0e6c8" }
		]
		},{
		"featureType": "poi.park",
		"stylers": [
		  { "color": "#c4d82d" },
		  {'visibility': 'simplified'}
		]
		}
		]);
		
		loadStores();
	}
	
	function loadStores() {
		$.ajax('/home/stockists', {dataType: 'json'}).success(function(data, status, xhr) {
			var stores = data,
				i = 0,
				markers = [],
				searchStrings = [];
				
				function arePositionsEqual(loc1, loc2) {
					return loc1.lat() == loc2.lat() && loc1.lng() == loc2.lng();
				}
				
			window.storeData = stores;
			
			for(i in stores) {
				var store = stores[i];
				
				for(var j in store) {
					
					for(var k in store[j].Data) {
						
						loc = new google.maps.LatLng(store[j].Data[k].Lat, store[j].Data[k].Lon),
						marker = new google.maps.Marker({
							position: loc,
							map: map,
							title: store[j].Data[k].SearchString,
							icon: '/themes/default/img/stockist_marker.png',
							shadow: {
								url: '/themes/default/img/stockists_shadow.png',
								size: new google.maps.Size(31,30),
								origin: new google.maps.Point(0,0),
								anchor: new google.maps.Point(2, 30)
							}
						});
							
						marker.StoreData = store[j].Data[k];
						
						markers.push(marker);
						
					}
				}
			}
			
			for(var j in markers) {
				
				google.maps.event.addDomListener(markers[j], 'click', function(e) {
					var current = false,
						i = 0;
					
					for(i in markers) {
						
						if(arePositionsEqual(this.getPosition(), markers[i].getPosition())) {
							current = markers[i];
							break;
						}
					}
					
					if(current) {
						if(activeInfoWindow) {
							activeInfoWindow.close();
						}
						
						var store = current.StoreData,
							details = $('<div class="infobox"/>')
								.append('<span class="title">' + store.Name + '</span>')
								.append('<span class="address">' + store.Address + ', ' + (store.Suburb !== 'default' ? store.Suburb + ', ' + store.City : store.City));
					
						info = new InfoBox({
							content: details.html(),
							closeBoxURL: '/themes/default/img/close.png',
							pixelOffset: new google.maps.Size(-110, -90)
						});
						info.open(map, current);
						activeInfoWindow = info;
						
						map.panTo(this.getPosition());
						map.setCenter(this.getPosition());
					}
				});
			}
			
			var clusterer = new MarkerClusterer(map, markers, {
				maxZoom: 16,
				gridSize: 65
			});
			
			clusterer.setStyles([{
					url: '/themes/default/img/cluster.png',
					height: 88,
					width: 90,
					anchor: [0,0],
					textColor: '#f0e8c9',
					textSize: 40
				}]);
				
			/*
$('form').submit(function() {
				for(var i in markers) {
					if(markers[i].StoreData.SearchString == $('#search').val()) {
						google.maps.event.trigger(markers[i], 'click');
					}
				}
				return false;
			});
			
			$('#search').autocomplete(searchStrings, {
				formatItem: function(item) {
					return item.text;
				},
				max: 4,
				matchContains: true,
				inputFocus: false
			}).result(function(e, item) {
				if(item.text != '' && typeof _gaq != 'undefined') {
					_gaq.push(['_trackEvent', 'Stockists', 'Result', item.text]);
				}
				google.maps.event.trigger(item.location, 'click');
			}).keyup(function(e) {
				if($(e.currentTarget).val().length > 4 && typeof _gaq != 'undefined') {
					_gaq.push(['_trackEvent', 'Stockists', 'Search', $(e.currentTarget).val()]);
				}
			});
*/
		});
	}
	
	initialize();
	
	google.maps.event.addDomListener(window, 'load', initialize);
})(jQuery);