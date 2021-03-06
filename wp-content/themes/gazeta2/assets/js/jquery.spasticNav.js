(function($) {

	$.fn.spasticNav = function(options) {
	
		options = $.extend({
			overlap : 20,
			speed : 500,
			reset : 1500,
			color : '#0b2b61',
			easing : 'easeOutExpo'
		}, options);
	
		return this.each(function() {
			
			if( $('ul.mega-menu-horizontal > li.menu-item').hasClass('current-menu-item') ){
				var current_item_menu = '.current-menu-item';
			}
			else{
				var current_item_menu = '.current-menu-parent';
			}
			
		 	var nav = $(this),
		 		currentPageItem = $(current_item_menu, nav),
		 		blob,
		 		reset;
		 		
		 	$('<li id="blob"></li>').css({
		 		width : currentPageItem.outerWidth(),
		 		height : currentPageItem.outerHeight() + options.overlap,
		 		left : currentPageItem.position().left,
		 		top : currentPageItem.position().top - options.overlap / 2,
		 		backgroundColor : options.color
		 	}).appendTo(this);
		 	
		 	blob = $('#blob', nav);
		 	
			$('ul.mega-menu-horizontal > .menu-item').hover(function() {
				// mouse over
				clearTimeout(reset);
				blob.animate(
					{
						left : $(this).position().left,
						width : $(this).width()
					},
					{
						duration : options.speed,
						easing : options.easing,
						queue : false
					}
				);
			}, function() {
				// mouse out	
				reset = setTimeout(function() {
					blob.animate({
						width : currentPageItem.outerWidth(),
						left : currentPageItem.position().left
					}, options.speed)
				}, options.reset);
	
			});
		
		}); // end each
	
	};

})(jQuery);
