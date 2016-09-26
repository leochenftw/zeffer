(function($) {
	window.jQuery = jQuery = $;
	
	
	
	require(['chosen'], function(chosen) {
		$('select').each(function() {
/* 			console.log($(this)); */
/* 			$(this).data('chosen', new Chosen(this)); */
			$(this).chosen();
			
			//console.log($(this).data());
		});
		
/* 		console.log(Chosen); */
	});
})($);