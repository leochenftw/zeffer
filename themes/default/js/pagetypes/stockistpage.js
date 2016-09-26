require.config({
	paths: {
		'jquery': '../lib/jquery',
		'underscore': '../lib/underscore',
		'backbone': '../lib/backbone',
		'modernizr': '../lib/modernizr'
	},
	shim: {
		backbone: {
			deps: ['jquery', 'underscore'],
			exports: 'Backbone'
		},
		underscore: {
			exports: '_'
		}
	}
});

require(['jquery', 'backbone', 'underscore'], function($, Backbone, _) {
	
	$(function() {
		console.log('stockistpage page');
	});
	
});