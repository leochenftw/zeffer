var _BASEIMAGEFOLDER = '/themes/default/img/',
	_qr = $('<div />').attr('id', 'the-qr');
_qr.html('<img src="' + _BASEIMAGEFOLDER + 'qr-code.jpg" />');
jQuery(document).ready(function($) {
	
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
			
			$(this).find('.col:eq('+i+')').append(imgCross);
		}
    });
	
	$('.rects').each(function(index, element) {
        if ($(this).find('.rect').length < 3) {
			var n = 3 - $(this).find('.rect').length;
			for (var i = 0; i < n; i++){
				$(this).append('<div class="rect">&nbsp;</div>');
			}
		}
    });
	
	$('.equal-heights').equalHeights();
	
	$(window).resize(function() {
		
		$('#ciders').css({
			marginTop: $(window).height()
		});
		setActive();
		
		$('#map').css({
			height: $(window).height() - $('#map').prev('header').innerHeight(),
			top: $('#map').prev('header').innerHeight()
		});
	});
	
	$(window).resize();
	
	
	// =============== //
	// Section Locator //
	// =============== //
	// a panel at the bottom of the screen indicating
	// whereabouts in the document a user is.
	(function() {
		// nothing to see here folks
		if($('body').is('.mobile, .tablet')) {
			return;
		}
	
		$('#nextsection a').click(function(e) {
			e.preventDefault();
			
			_gaq.push(['_trackEvent', 'Footer Navigation', 'Click', $(this).text()]);
			
			var offset = 0,
				that = this;
			
			if($(this).attr('href') != '#carousel') {
				offset += $($(this).attr('href')).offset().top;
				
				if($($(this).attr('href')).is('.background')) {
					offset += parseInt($($(this).attr('href')).css('padding-top'), 10);
				} else if($(this).attr('href').match(/apple|pear|slackmagirdle/) !== null) {
					ciders = $('.new-ciders').filter(function() {
						return $(this).offset().top > $($(that).attr('href')).offset().top;
					});
					
					var _h = 0;
					ciders.each(function() {
						_h += $(this).outerHeight(true);
					});
					
					offset -= _h + $('#ciders header').height() + 40;
				}
			}
			
			$('html,body').animate({
				scrollTop: offset
			}, 500);
		});
		
		$.sectionsLocator = function() {
			var section;
			
			if($(window).scrollTop() === 0) {
				section = $('#carousel');
			} else if($(window).scrollTop() + $(window).height() >= $(document).height()) { 
				$('#nextsection').addClass('up');
				$('#nextsection li').removeClass('active');
				$('#nextsection li:last').addClass('active');
				return;
			} else {
				section = $('section, .new-cider').filter(function() {
					return $(this).offset().top <= $(window).scrollTop();
				}).filter(':last');
				
				if(section.is('#ciders')) {
					section = $('.new-cider:first');
				} else if(section.is('.new-cider')) {
					section = section.next('.new-cider') || section.parents('section').next('section');
				}
			}
			
			
			if(section.length > 0) {
				var found = false;
				
				$('section, .new-cider').each(function(i, el) {
					
					if(found) {
						found = $(el);
						return false;
					}
					
					if($(el).attr('id') == section.attr('id')) {
						found = true;
					}
				});
				
				if(!found || (found && typeof found.attr == 'undefined')) {
					$('#nextsection').addClass('up');
					$('#nextsection li').removeClass('active');
					$('#nextsection li:last').addClass('active');
				} else {
					$('#nextsection').removeClass('up');
					$('#nextsection li').removeClass('active');
					$('#nextsection a[href="#' + found.attr('id') + '"]').parent('li').addClass('active');
				}
			}
		};
		
		$(window).scroll($.sectionsLocator);
		/*if(!$('body').is('.mobile, .tablet')) {
			$(window).scroll(function(e) {
				$('.new-cider').each(function(index, element) {
					if ($(this).visible(true)) {
						var img = $(this).find('.grid_4.alpha img'),
							topoffset = $(window).scrollTop() + $('#header').outerHeight() + $('#ciders header').outerHeight();
						if ($(this).offset().top < topoffset) {
							var curTop = parseInt(img.css('margin-top').replace('px','')),
								marginTop = Math.abs($(this).offset().top - topoffset),
								increment = marginTop - curTop;
						
							if ($(this).find('.grid_4.alpha').outerHeight() + increment < $(this).find('.grid_8').outerHeight()) {
								img.css('margin-top', marginTop);
							}else{
								img.css('margin-top', $(this).find('.grid_8').outerHeight() - img.height());
							}
						}else{
							img.removeAttr('style');
						}
						
					}
				});
			});
		}*/
		//$.sectionsLocator();
	})();


	// ========= //
	// Main Menu //
	// ========= //
	var prevScroll = 0;
	$('#header nav a').click(function(e) {
		e.preventDefault();
		
		_gaq.push(['_trackEvent', 'Main Navigation', 'Click', $(this).text()]);
		
		var target = $($(this).attr('href')),
			offset = typeof target.offset() != 'undefined' ? target.offset().top + 1 : 0;
		
		$(this).parent().siblings().each(function() { 
			$(this).find('a').removeClass('active'); 
		});
		$(this).addClass('active');
		
		
		if(target.is('.background')) {
			offset += parseInt(target.css('padding-top')) - 10;
		}
		

		if($('body').is('.mobile, .tablet')) {
			$('html,body').animate({
				scrollTop: offset
			}, 500);
		} else {
			$('html,body').animate({
				scrollTop: offset
			}, 500, 'easeInExpo');
		}
	});
	
	
	//
	// Sets the highlighted menu item to the section that the
	// user has scrolled to.
	// Note: the highlighted item does not necessarily reflect 
	// the <section> in the document. e.g. "story" in the menu
	// relates to several <section>s in the doc.
	//
	function setActive() {
		var found = {},
			target = '',
			list = $('#carousel,#ciders,#statement,#contact,#stockists'),
			sections = list.each(function() {
				found[$(this).attr('id')] = {
					top: $(this).offset().top,
					bottom: $(this).offset().top + $(this).outerHeight(true)
				}
			});
	
		for(var i in found) {
			if(found[i].top <= $(window).scrollTop()) {
				target = i;
			}
		}
		
		
		// hack to compensate for crappy mac elastic scroll nonsense.
		if($(window).scrollTop() + $(window).height() >= $(document).height()) {
			target = 'stockists';
		}
		
		$('#header a').removeClass('active');
		$('#header a[href="#' + ((target == '' || target == 'carousel') ? '' : target) + '"]').addClass('active');
	}	
	
	
	// ======== //
	// Carousel //
	// ======== //
	var carouselTimeout = 1000,
		carouselI = 0,
		interval = null,
		images = {
			baseImgURL: '/themes/default/img/jumbo/',
			slides: [{
				img: 'Jumbo_2_ApplePear.jpg',
				copy: 'We make a mighty tasty drop or two',
				link: '#ciders',
				css: '_2'
			}, {
				img: 'Jumbo_3_SMG.jpg',
				copy: 'We even make brews that are a little more -- FANCY -- than your usual',
				link: '#slack-ma-girdle',
				css: '_3'
			}, {
				img: 'Jumbo_4_PakiriDune.jpg',
				copy: 'We\'re based in SUMMER COUNTRY - stunning Matakana',
				link: '#statement',
				css: '_4'
			}, {
				img: 'Jumbo_5_Friends.jpg',
				copy: 'We like you already. Let\'s be <b>FRIENDS</b>',
				link: '#contact',
				css: '_5'
			}, {
				img: 'Jumbo_6_Markets.jpg',
				copy: 'If you fancy a taste, we\'re stocked in A HEAP OF PLACES (there\'s probably one down the road)',
				link: '#stockists',
				css: '_6'
			}]
		},
		imagesLoaded = 0;
		
	function carousel() {
		$('#carousel .next').click();
	}
	
	function increment() {
		if(carouselI++ >= carouselTimeout) {
			carousel();
			carouselI = 0;
		}
	}
	
	function setup() {
		clearInterval(interval);
		interval = setInterval(increment, 0);
	}
	
	function resetInterval() {
		carouselI = 0;
		interval = setInterval(increment, 0);
	}
	
	//setup();
	
	// Load in all the images for the slider.
	//setTimeout(function() {
		
	
		for(var i=0; i<images.slides.length; i++) {
			var slide = images.slides[i];
			
			$('<img/>').load(function(response, status, xhr) {
				var target = images.slides[imagesLoaded++];
					li = $('<li/>').addClass(target.css),
					a = $('<a/>').attr('href', target.link).html(target.copy);
					
					a.click(function(e) {
						e.preventDefault();
						var offset = $(this).attr('href') == '#slack-ma-girdle' ? -150 : 0;
						
						$('html,body').animate({
							scrollTop: $($(this).attr('href')).offset().top + offset
						});
						
						//clearInterval(interval);
		
					}).hover(function() {
						//clearInterval(interval);
					},function() {
						//resetInterval();
					});
				li.append(a);
				
				$('#carousel ul').append(li);
				
				//if(imagesLoaded >= images.slides.length) {
					//alert($('#carousel ul li').length);
					$('#carousel nav a').css('opacity', '1');
					if(!$('body').is('.mobile, .tablet')) {
						setup();
					}
					$(window).scroll(function() {
						if($('body').is('.mobile, .tablet')) {
							return;
						}
						if($(window).scrollTop() >= 200) {
							clearInterval(interval);
						} else {
							setup();
						}
					});
				//}
			}).attr('src', images.baseImgURL + slide.img);
		}
	//}, 3000);
	
	
	$('#carousel .next').click(function(e) {
		e.preventDefault();
		
		clearInterval(interval);
		
		if($('#carousel li:animated').length > 0) {
			return;
		}
		
		if($('#carousel .active').next().length == 0) {
			var clone = $('#carousel li:first').clone(true, true);
			$('#carousel .active').after(clone);
			$('#carousel li:first').remove();
		}
		
		$('#carousel .active').next().css({
			left: $(window).width(),
			display: 'block'
		});
		
		$('#carousel .active').next().find('a, span').removeAttr('style');
		
		$('#carousel li.active a, #carousel li.active span').animate({
			left: $('#carousel li.active a, #carousel li.active span').offset().left - $(window).width()
		}, 1000, 'easeInCubic');
		
		if(!$('body').is('.mobile, .tablet')) {
			setTimeout(function() {
				animateNext();
			}, 500);		
		} else {
			animateNext();
		}		
	});
	
	function animateNext() {
		$('#carousel .active').animate({
			left: -$(window).width()
		}, 1500, 'easeOutExpo');
		
		$('#carousel .active').next().find('a,span').each(function() {
			$(this).css({
				'margin-left': $(window).width()
			});
		});
		
		$('#carousel .active').next().css({
			left: 0
		});
		
		$('#carousel .active').next().find('a,span').animate({
			'margin-left': 0 - ($('#carousel .active').next().find('a,span').width()/2)
		}, 1500, 'easeOutCubic', function() {
			$(this).parent().addClass('active').siblings().removeClass('active');
			if(!$('body').is('.mobile, .tablet')) {
				resetInterval();
			}
		});
	}
	
	$('#carousel .previous').click(function(e) {
		e.preventDefault();
		
		clearInterval(interval);
		
		if($('#carousel li:animated').length > 0) {
			return;
		}
		
		if($('#carousel .active').prev().length == 0) {
			var clone = $('#carousel li:last').clone(true, true);
			$('#carousel .active').before(clone);
			$('#carousel li:last').remove();
		}
		
		$('#carousel .active').prev().css({
			left: -$(window).width(),
			display: 'block'
		});
		
		$('#carousel .active').prev().find('a, span').removeAttr('style');
		
		$('#carousel li.active span, #carousel li.active a').animate({
			left: $('#carousel li.active a, #carousel li.active span').offset().left + $(window).width()
		}, 1500, 'easeInCubic');
		
		if(!$('body').is('.mobile, .tablet')) {
			setTimeout(function() {
				animatePrevious();
			}, 500);
		} else {
			animatePrevious();
		}
	});
	
	function animatePrevious() {
		$('#carousel .active').animate({
			left: $(window).width()
		}, 1500, 'easeOutCubic');
		
		$('#carousel .active').prev().find('a,span').each(function() {
			$(this).css({
				'margin-left': 0 - $(window).width()
			});
		});
		
		$('#carousel .active').prev().css({
			left: 0
		});
		
		$('#carousel .active').prev().find('a,span').animate({
			'margin-left': 0 - ($('#carousel .active').next().find('a,span').width()/2)
		}, 1500, 'easeOutCubic', function() {
			$(this).parent().addClass('active').siblings().removeClass('active');
			if(!$('body').is('.mobile, .tablet')) {
				resetInterval();
			}
		});
	}
	
	
	
	// ============== //
	// CIDERS
	// ============== //
	var origScrollTop = 0,
		offset = 100,
		top = 230,
		_height = $('.new-cider:first .grid_4 img').height() + $('.new-cider:first').position().top + 300,
		cidersOffset = $('#ciders').offset().top,
		cidersHeight = $('#ciders').height(),
		cidersHeaderOffset = $('#ciders header').offset().top,
		cidersHeaderHeight = $('#ciders header').outerHeight(true),
		eachCiderOffset = {},
		prevHeight = 0;
	
	function reset() {
		top = $('.new-cider:first .grid_4 img').position().top;
		prevHeight = 0;//$('.new-cider:first .grid_4 img').height();
		
		$('#ciders .new-cider').each(function(i, e) {
			/*
if(i > 0) {
				_height += $(this).outerHeight(true);
			}
*/
			//if(typeof $('#ciders').data('height') == 'undefined') {
				//$(this).height($(this).find('.grid_3 img').height());
			//}
			/*
$(this).css({
				'margin-top': prevHeight
			});
*/
			prevHeight += $(this).outerHeight(true);
			
			/*
$(this).find('.grid_3').css({
				position: 'absolute',
				//left: 0 - $(this).find('.grid_3').width(),
				top: ((i+1) * $(this).find('.grid_3').outerHeight(true)),
				opacity: 1.0
			});
*/
			
			/*
$(this).find('.grid_9').css({
				marginLeft: $(this).find('.grid_3').outerWidth(true),
				opacity: 1.0
			});
			
			$(this).find('.grid_9').children().css('opacity', 1);
*/
		});
	}
	
	reset();
	
	
	$('#ciders nav a').click(function(e) {
		e.preventDefault();
		
		_gaq.push(['_trackEvent', 'Ciders Links', 'Click', $(this).text()]);
		
		$('html,body').animate({
			scrollTop: $($(this).attr('href')).offset().top - (
				$('#ciders header').height() + $('#ciders header').position().top + 20)
		}, 500);
	});
	var started = false;
	$(window).scroll(function(e) {
		// nothing to see here folks
		if(!$('body').is('.mobile, .tablet')) {
			setActive();
		}
		
		if($(window).scrollTop() <= 0) {
			reset();
		}
		
		if(!started) {
			started = true;
			
			$('html, body').animate({
				scrollTop: 0
			}, 0, reset);
			
			return;
		}
		
		// nothing to see here folks
		if($('body').is('.mobile, .tablet')) {
			return;
		}
		
		// =================== //
		// Lock the ciders nav //
		// =================== //
		if($(window).scrollTop() > cidersOffset && $(window).scrollTop() < $('#ciders .new-cider:last').offset().top - cidersHeaderHeight - 40) {
			$('#ciders').addClass('fixed').removeClass('absolute');
			$('#ciders .new-cider:first').css({
				marginTop: cidersHeaderHeight
			});
			$('#ciders .container_12').css('padding-top', 0);
			$('#ciders header').attr('style', '');
		} else if($(window).scrollTop() >= $('#ciders .new-cider:last').offset().top - cidersHeaderHeight - 40) {

			$('#ciders').addClass('absolute').removeClass('fixed');//.attr('style', '');
			$('#ciders .new-cider:first').css('margin-top', 0);
			$('#ciders .container_12').css('padding-top', cidersHeaderHeight);
			$('#ciders header').css('top', $('#ciders .new-cider:last').position().top - cidersHeaderHeight + 10);
		} else {
			$('#ciders').removeClass('absolute').removeClass('fixed');
			$('#ciders .new-cider:first').css('margin-top', 0);
			$('#ciders .container_12').css('padding-top', 0);
			$('#ciders header').attr('style', '');
		}
		
		// ==================== //
		// Set Active Menu Item //
		// ==================== //
		var target = null;
		$('#ciders .new-ciders .new-cider').each(function() {
			var offset = $(this).offset().top,
				height = $(this).height(),
				cidersTop = $('#ciders header').offset().top + $('#ciders header').height();
			
			if((offset - 70) < cidersTop) {
				target = $(this).attr('id');
			}
		});
		
		if(target) {
			$('#ciders nav a').removeClass('active');
			$('#ciders nav a[href="#' + target +'"]').addClass('active');
		}
		
		var prevHeight = top,
			scrolledDown = $(window).scrollTop() > (cidersOffset + cidersHeight);
		$('#ciders .new-cider').each(function(i, e) {
			/*
var grid3Top = parseInt($(this).find('.grid_3').css('top'));
			
			if($(window).scrollTop() >= ($(this).position().top) && $(this).find('.grid_3').offset().top > $(this).find('.grid_9').offset().top) {
				$(this).find('.grid_3').css({
					top: grid3Top - ($(window).scrollTop() - prevScroll),
					opacity: 1.0
				});	
			}
*/
			
			prevHeight += $(this).outerHeight(true);
		});
		
		prevScroll = $(window).scrollTop();
	});
	
	$(window).scroll();
	
	
	// ========================= //
	// Various GA event tracking //
	// ========================= //
	$('a[href^="mailto:josh"]').click(function() {
		_gaq.push(['_trackEvent', 'Contact Links', 'Click', 'Sales & Marketing']);
	});
	$('.facebook a').click(function() {
		_gaq.push(['_trackEvent', 'Contact Links', 'Click', 'Facebook']);
	});
	$('.twitter a').click(function() {
		_gaq.push(['_trackEvent', 'Contact Links', 'Click', 'Twitter']);
	});
	$('.instagram a').click(function() {
		_gaq.push(['_trackEvent', 'Contact Links', 'Click', 'Instagram']);
	});
	$('.email a').click(function() {
		_gaq.push(['_trackEvent', 'Contact Links', 'Click', 'Email']);
	});
	
	$('#pop-qr').click(function(e) {
        e.preventDefault();
		if ($('#the-qr').length > 0) {
			_qr.remove();
			$(window).unbind('mousedown');
		} else {
			_qr.appendTo('body');
			$(window).mousedown(function(e) {
                if (!$(e.target).is('#the-qr') && !$(e.target).parent().is('#the-qr')) {
					_qr.remove();
					$(window).unbind('mousedown');
				}
            });
		}
		_gaq.push(['_trackEvent', 'Contact Links', 'Click', 'WeChat']);
		return false;
    });
});

function rand(min, max) {
    return Math.floor(Math.random() * (max - min + 1)) + min;
}