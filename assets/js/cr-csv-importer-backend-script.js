/**
	* package Cr_CSV_IMPORT_FOR_DMITRIY
	* version 1.00
*/
(function($) {  
	$(function() {  
		$('#info .tab-body:not(:first)').hide();
		
		$('#info-nav li').click(function(event) {
			event.preventDefault();
			$('#info .tab-body').hide();
			$('#info-nav .current').removeClass("current");
			$(this).addClass('current');
			
			var clicked = $(this).find('a:first').attr('href');
			$('#info ' + clicked).fadeIn('fast');
		}).eq(0).addClass('current');
	});
})(jQuery);