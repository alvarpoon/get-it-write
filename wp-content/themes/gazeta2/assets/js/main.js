jQuery(document).ready(function($) {
    "use strict";
    try {
		$('.widget_calendar table').addClass('table');
	} catch (e) {
		// TODO: handle exception
	}
    try {
   	 /* Nav-lava Lamp */
   	jQuery('header nav ul').spasticNav();

   } catch (e) {
   	// TODO: handle exception
   }
    /* Search */
    $(".search-trigger").on('click', function() {
        $(".search-form").slideToggle("slow", function() {});
	   $("i").toggleClass( "fa-times" , "fa-search");
    });

    // Prettyphoto
    $("a[class^='prettyPhoto']").prettyPhoto({
        theme: 'pp_default'
    });

    /* NewsTicker */
    $('#news-ticker').slick({
        dots: false,
        arrows: true,
        speed: 800,
        autoplay: true,
        vertical: true,
        autoplaySpeed: 5000,
        centerMode: false,
        slidesToShow: 1,
        slidesToScroll: 1
    });

    /* NewsTicker */
    var is_t_rtl = $('.tweet-feed').attr('data-rtl');
    if( is_t_rtl == 'yes' ){
    	is_t_rtl = true;
    }
    else{
    	is_t_rtl = false;
    }    
    $('.tweet-feed').slick({
        dots: false,
        arrows: true,
        speed: 800,
        autoplay: true,
        vertical: false,
        autoplaySpeed: 5000,
        centerMode: false,
        slidesToShow: 1,
        slidesToScroll: 1,
        rtl:is_t_rtl
    });

    /* NewsTicker */
    var is_rtl = $('.bl-featured-slider').attr('data-rtl');
    if( is_rtl == 'yes' ){
    	is_rtl = true;
    }
    else{
    	is_rtl = false;
    }
    $('.bl-featured-slider').slick({
        dots: true,
        arrows: false,
        speed: 800,
        autoplay: true,
        vertical: false,
        autoplaySpeed: 5000,
        centerMode: false,
        slidesToShow: 1,
        slidesToScroll: 1,
        rtl:is_rtl
    });

    /* Popular News */
    $('#pnews-slider').slick({
        arrows: true,
        speed: 300,
        centerMode: false,
        autoplay: true,
        autoplaySpeed: 2000,
        slidesToShow: 3,
        slidesToScroll: 1,
        dots: false,
        responsive: [{
            breakpoint: 1024,
            settings: {
                slidesToShow: 3,
                slidesToScroll: 1,
                infinite: true
            }
        }, {
            breakpoint: 600,
            settings: {
                slidesToShow: 2,
                slidesToScroll: 1
            }
        }, {
            breakpoint: 480,
            settings: {
                slidesToShow: 1,
                slidesToScroll: 1
            }
        }]
    });

    try {
        /* Nicescroll */
        $("html").niceScroll();		
	} catch (e) {
		// TODO: handle exception
	}
    
    /* Simple tabs */
    $('#tabs li a').on('click', function(e) {
        $('#tabs li, #content .current').removeClass('current').removeClass('fadeInLeft');
        $(this).parent().addClass('current');
        var currentTab = $(this).attr('href');
        $(currentTab).addClass('current fadeInLeft');
        e.preventDefault();

    });

    /* Backtotop */
    $('.copy1 a').on('click', function() {
        $('html, body').animate({
            scrollTop: 0
        }, 'slow');
    });  

});
