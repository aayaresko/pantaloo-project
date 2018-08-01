//Animate CSS + WayPoints javaScript Plugin
//Example: $(".element").animated("zoomInUp", "zoomOutDown");
//Author URL: http://webdesign-master.ru
(function($) {
		$.fn.animated = function(inEffect, outEffect) {
			$(this).css("opacity", "0").addClass("animated")
			if ($(this).is(':in-viewport(0)')){
				$(this).addClass(inEffect).css("opacity", "1");
			}
		};
})(jQuery);