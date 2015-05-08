/* inspirations.js */
( function($){

	$(window).load( function(){

		var h = $(".front-page-widgets").height();
		console.log(h);


		$(".front-page-widget").each(function() {
			$(this).height( h ); 
		});

	});

})(jQuery);