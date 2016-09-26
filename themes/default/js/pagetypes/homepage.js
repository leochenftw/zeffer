require.config({
	paths: {
		'jquery': '../lib/jquery',
		'visible': '../lib/visible',
		'main': '../views/main',
		'maps': '../views/maps.min',
		'equalheights': '../components/jquery.equalheights/jquery.equalheights.min',
		'easing': '../components/jquery.easing/js/jquery.easing.min',
		async: '../components/requirejs-plugins/src/async.min',
		'autocomplete': '../components/jquery.autocomplete/jquery.autocomplete.min',
		'infobox': '../components/google-maps-utility-library-v3/infobox/src/infobox_packed',
		'clusterer': '../components/google-maps-utility-library-v3/markerclusterer/src/markerclusterer.min',
		chosen: '../lib/chosen_v1.0.0/chosen.jquery.min',
		scroll: '../views/scroll.min'
	},
	shim: {
		backbone: {
			deps: ['jquery', 'underscore'],
			exports: 'Backbone'
		},
		visible: {
			deps: ['jquery']
		},
		underscore: {
			exports: '_'
		},
		main: {
			deps: ['jquery', 'equalheights', 'easing']
		},
		equalheights: {
			deps: ['jquery']
		},
		easing: {
			deps: ['jquery']
		},
		autocomplete: {
			deps: ['jquery']
		},
		infobox: {
			deps: ['async!http://maps.google.com/maps/api/js?sensor=false&v=3']
		},
		clusterer: {
			deps: ['async!http://maps.google.com/maps/api/js?sensor=false&v=3']
		},
		maps: {
			deps: ['jquery', 'autocomplete', 'infobox', 'clusterer', 'chosen']
		},
		chosen: {
			deps: ['jquery']
		},
		scroll: {
			deps: ['jquery']
		}
	}
});

if(typeof _gaq == 'undefined') {
	var _gaq = {
		push: function() {}
	};
}

require(['jquery', 'visible', 'main', 'maps', 'scroll'], function($) {
	
});