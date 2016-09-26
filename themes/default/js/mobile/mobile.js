require.config({
	paths: {
		zepto: '../components/zepto/zepto.min',
		zeptoScroll: '../components/zepto-scroll/static/zepto.scroll',
		async: '../components/requirejs-plugins/src/async.min',
		scroll: '../views/scroll.min'
	},
	shim: {
		zepto: {
			exports: '$'
		},
		zeptoScroll: {
			deps: ['zepto']
		},
		maps: {
			deps: ['zepto', 'location']
		},
		scroll: {
			deps: ['zepto', 'zeptoScroll']
		}
	}
});

if(typeof _gaq == 'undefined') {
	var _gaq = {
		push: function() {}
	};
}

require(['zepto', 'zeptoScroll', 'maps', 'scroll'], function($) {
	$('#statement').insertBefore($('#ciders'));
	$('#header nav .story').insertBefore($('#header nav .ciders'));
	var _BASEIMAGEFOLDER = '/themes/default/img/';
	$('.spec .circles').each(function(index, element) {
        var n = parseInt($(this).attr('data-value'));
		var cross = null;
		var json = $(this).parent().parent().attr('data-cross-json');
		if (json) {
			cross = json.split('+');
		}
		var maxLen = cross?cross.length:0;
		for (var i = 0; i < n; i++){
			var imgCross = $('<img />').attr('src', maxLen > 0 ? (cross[rand(0,maxLen-1)]) : (_BASEIMAGEFOLDER + 'cross/' + rand(1,5) +'.png'));
			$(this).find('.col').eq(i).append(imgCross);
		}
    });
});

function rand(min, max) {
    return Math.floor(Math.random() * (max - min + 1)) + min;
}