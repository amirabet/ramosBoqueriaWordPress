/*-----------------------------------------------------------------------------------*/
/* FIXED HEADER */
/*-----------------------------------------------------------------------------------*/
jQuery(document).ready(function($){

	if( !/Android|webOS|iPhone|iPad|iPod|BlackBerry/i.test(navigator.userAgent) ) {
	    if (jQuery(window).width() > 767) {

	    	var pos = $('#fixed-header').offset().top;
	    	var height = $('#fixed-header').outerHeight();
	    	var wpadmin = $('#wpadminbar').outerHeight();
	    	var topmenu = $('#top').outerHeight();
	    	var bg = $('body').css('background-color');

	    	if ( ! wpadmin ) { wpadmin = 0; }
	    	if ( ! topmenu ) { topmenu = 0; }

		    $('.fixed-header').css( 'margin-top', wpadmin + topmenu );

	    	$(window).scroll(function() {

		    	var top = $(document).scrollTop();

		    	if (top > pos + wpadmin + topmenu - 10) {
			    	$('#fixed-header').addClass('fixed').css({ 'background-color': bg, 'top': wpadmin + topmenu });
			    	$('#logo').addClass('fixed');
			    	$('#navigation').addClass('fixed');
			    	$('#header').addClass('fixed');
			    	$('.fixed-header').css( 'margin-top', height + wpadmin + topmenu );
				} else {
					$('#fixed-header').removeClass('fixed').removeAttr('style');
					$('#logo').removeClass('fixed');
			    	$('#navigation').removeClass('fixed');
			    	$('#header').removeClass('fixed');
			    	$('.fixed-header').css( 'margin-top', wpadmin + topmenu );
			    }

	    	});

	    }
	}

});