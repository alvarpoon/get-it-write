jQuery(document).ready(function($) {
    "use strict";
	/** Fitvideo **/
	try {
		$('.media-frame').fitVids();
	}
	catch (e) {
		// TODO: handle exception
		console.log('Could not load fitVids, seem you activated Jetpack, it\'s fine.');
	}    
})