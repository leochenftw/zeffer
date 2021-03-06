/* alert('hi'); */

(function($) {
	if($('body').is(':not(.tablet):not(.mobile)')) {
		return false;
	}

	var touched = false;
	
	$('#header a').first().addClass('active');
	
	$('#header a').on('touchstart', function() {
		touched = true;
	});
	
	$('#carousel .next').on('touchend', function(e) {
		$('#header a[href="#ciders"]').trigger('touchend');
		
		return false;
	});
	
	$(document).on('touchend', function(e) {
		var last = null,
			scrollTop = $(window).scrollTop();
			
		if(touched) {
			return false;
		}
		
		if(scrollTop <= ($(window).height() - $('#header').height())) {
			$('#header a').removeClass('active');
			$('#header a').first().addClass('active');
			return false;
		}
		
		$('#header nav li').not('.home').each(function(i) {
			var target = $($(this).find('a')[0].hash);
				
			if(target.offset().top + target.height() >= scrollTop + $('#header').height() + 200) {
				last = $(this).find('a');
				return false;
			}
		});
		
		console.log(last);
		
		
		$('#header a').removeClass('active');
		if(last) {
			last.addClass('active');
		} else {
			$('#header a').last().addClass('active');
		}
	}).on('touchend', '#header a', function(e) {
		touched = true;
		$('#header a').removeClass('active');
		$(this).addClass('active');
		
		if(typeof $.scrollTo !== 'function') {
			$('body,html').animate({
				scrollTop: $(this)[0].hash ? $($(this)[0].hash).offset().top - $('#header').height() : 0
			}, 200, function() {
				touched = false;
				$(document).trigger('touchend');
			});
		
			return;
		}
		
		$.scrollTo({
			endY: $(this)[0].hash ? $($(this)[0].hash).offset().top - $('#header').height() : 0,
			duration: 200,
			callback: function() {
				touched = false;
				$(document).trigger('touchend');
			}
		});
	});
	
	$(window).on('scroll', function() {
		$(document).trigger('touchend');
	});
})(typeof Zepto !== 'undefined' ? Zepto : jQuery);