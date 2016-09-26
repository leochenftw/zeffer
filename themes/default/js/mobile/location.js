define(function() {
	var Location = function(observers) {
		this.lat = null;
		this.lon = null;
		this.success = false;
		this.message = '';
		this.observers = [];
		
		for(var i in observers) {
			this.addObserver(observers[i]);
		}
		
		this.getLocation();
		
		//console.log(window);
		
		window.__LOCATION__ = this;
		
		//console.log(this);
	};
	
	Location.prototype.displayPosition = function(position) {
		window.__LOCATION__.lat = position.coords.latitude;
		window.__LOCATION__.lon = position.coords.longitude;
		window.__LOCATION__.success = true;
		window.__LOCATION__.notify();
	}
		
	Location.prototype.displayError = function(error) {
		var errors = { 
			1: 'Permission denied',
			2: 'Position unavailable',
			3: 'Request timeout'
		};
		window.__LOCATION__.success = false;
		window.__LOCATION__.message = errors[error.code];
		window.__LOCATION__.notify();
	}
	
	Location.prototype.addObserver = function(observer) {
		this.observers.push(observer);
	}
		
	Location.prototype.notify = function() {
		for(i in this.observers) {
			this.observers[i].update();
		}
	}
	
	Location.prototype.getLocation = function() {
		if (navigator.geolocation) {
			var timeoutVal = 30 * 1000;
			navigator.geolocation.getCurrentPosition(
				this.displayPosition, 
				this.displayError,
				{
					enableHighAccuracy: false,
					timeout: timeoutVal,
					maximumAge: 60 * 60 * 1000
				}
			);
		}
		else {
			this.success = false;
			this.message = 'unsupported';
			this.notify();
		}
	}
	
	return Location;
});