jQuery(document).ready(function($) {
    "use strict";
    try {
    	$(".responsive-height").responsiveEqualHeightGrid();
	} catch (e) {
		// TODO: handle exception
		console.log('responsiveEqualHeightGrid error');
	}
    try {
		// gmap.
    	var gmap = '#map';
    	var latitude = $(gmap).attr('data-latitude');
    	var longitude = $(gmap).attr('data-longitude');
    	var zoommap = $(gmap).attr('data-zoom');
    	var marker = $(gmap).attr('data-marker');
    	var color = $(gmap).attr('data-color');
    	var saturation = $(gmap).attr('data-saturation');
    	var map = new GMaps({
    		el: gmap,
	        lat: latitude,
	        lng: longitude,
	          zoom: parseInt( zoommap ), 
	          zoomControl : false,
			  scrollwheel: false,
			controls : false,
	          zoomControlOpt: {
	            style : "BIG",
	            position: "TOP_LEFT"
	          },
	          panControl : false,
	          streetViewControl : false,
	          mapTypeControl: false,
	          overviewMapControl: false
    	 });
	      var styles = [
	            {
	              stylers: [
	                { hue: color },
	                { saturation: saturation }
	              ]
	            }
	      ];
	        
	      map.addStyle({
            styledMapName:"Styled Map",
            styles: styles,
            mapTypeId: "map_style"  
	      });
	        
	      map.setStyle("map_style");

	      map.addMarker({
	        lat: latitude,
	        lng: longitude,
	        icon: marker
	      });
	} catch (e) {
		// TODO: handle exception
		//console.log('Gmap error');
	}	
})