/* inspirations.js */
( function($){

	var v = w = $(".main-content > .container ").width() + 30;

	var setColHeight = function(){
		if ( $(".row.front-page-widgets").length > 0 ) {
			var m = -1;
			$(".front-page-widget").each(function() {
				var h = $(this).find(".widget_text").height();
				m = h > m ? h : m;
			});
			$(".front-page-widget").each(function() {
				$(this).find(".widget_text").height( m );
			});
		}
	}

	$(window).load( function(){
		setColHeight();
		w = $(".row.front-page-widgets").width();
		//console.log(w);
	});

	$(window).resize(function() {
		w = $(".row.front-page-widgets").width();
		if ( v != w ) {
			
			$(".front-page-widget").each(function() {
				$(this).find(".widget_text").css( 'height', '' );
			});
			
			setColHeight();
			v = w;
		}
	});

})(jQuery);


( function($){
	// Testimonials Slider
	$('.bxslider').bxSlider();
})(jQuery);