jQuery(document).ready(function($) {
    "use strict";
    try {
        /* Responsive Menu */
        $(".menu-trigger").on('click', function() {
            $(".main-nagivation").slideToggle("slow", function() {});
        });
	} catch (e) {
		// TODO: handle exception
	}
})