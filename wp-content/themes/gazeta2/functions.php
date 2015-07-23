<?php
if( !defined('ABSPATH') ) exit; // Don't access me directly.
if ( !isset( $content_width ) ) $content_width = 900;

if( !defined( 'GAZETA_TEMPLATE_DIRECTORY' ) ){
	define( 'GAZETA_TEMPLATE_DIRECTORY' , get_template_directory());
}
if( !defined( 'GAZETA_TEMPLATE_DIRECTORY_URI' ) ){
	define( 'GAZETA_TEMPLATE_DIRECTORY_URI' , get_template_directory_uri());
}
if( !defined( 'GAZETA_POST_VIEWS_FIELD_NAME' ) ){
	define('GAZETA_POST_VIEWS_FIELD_NAME', 'post_views');
}
if( !defined( 'GAZETA_POST_FACEBOOK_COMMENTS_FIELD_NAME' ) ){
	define('GAZETA_POST_FACEBOOK_COMMENTS_FIELD_NAME', 'post_facebook_comments');
}
require_once ( GAZETA_TEMPLATE_DIRECTORY . '/includes/class-tgm-plugin-activation.php');
require_once ( GAZETA_TEMPLATE_DIRECTORY . '/includes/functions.php');
require_once ( GAZETA_TEMPLATE_DIRECTORY . '/includes/templates.php');
require_once ( GAZETA_TEMPLATE_DIRECTORY . '/includes/widgets.php');
require_once ( GAZETA_TEMPLATE_DIRECTORY . '/includes/metaboxes.php');
require_once ( GAZETA_TEMPLATE_DIRECTORY . '/includes/custom-header.php');
require_once ( GAZETA_TEMPLATE_DIRECTORY . '/includes/theme-options.php');
if( !function_exists( 'gazeta_after_setup_theme' ) ){
	function gazeta_after_setup_theme() {
		// Loading theme textdomain.
		load_theme_textdomain( 'gazeta', GAZETA_TEMPLATE_DIRECTORY . '/languages' );
		// Adding html5 support.
		add_theme_support( 'html5', array(
			'search-form', 'comment-form', 'comment-list', 'gallery', 'caption'
		) );
		// V4.1
		add_theme_support( 'title-tag' );
		// jetpack fully video responsive
		add_theme_support( 'jetpack-responsive-videos' );
		// adding woocommerce support.
		add_theme_support( 'woocommerce' );
		// adding menu support.
		add_theme_support('menus');
		// adding thumbnail support.
		add_theme_support('post-thumbnails');
		// adding custom background support.
		add_theme_support('custom-background', array(
			'default-color'          => '',
			'default-image'          => '',
			'admin-head-callback'    => '',
			'admin-preview-callback' => ''
		));
		// adding post format support.
		add_theme_support( 'post-formats', array(
			'audio', 'gallery', 'image', 'video'
		) );
		add_theme_support( 'infinite-scroll', apply_filters( 'gazeta_infinite_scroll' , array(
					'container' => 'main-content',
					'footer' => 'footer',
					'type'	=>	'scroll',
					'posts_per_page' => get_option( 'posts_per_page' )
				)
			)
		);
		add_theme_support( 'automatic-feed-links' );
		add_image_size( 'image-110-81', 110, 81, true );
		add_image_size( 'image-278-186', 278, 186, true );
		add_image_size( 'image-585-472', 585, 472, true );
		add_image_size( 'image-280-435', 280, 435, true ); // category block
		add_image_size( 'image-355-193', 355, 193, true );
		add_image_size( 'image-770-460', 770, 460, true ); // gallery post
		add_image_size( 'image-370-243', 370, 243, true ); // gallery page
		add_image_size( 'image-1170-460', 1170, 460, true );
	}
	add_action( 'after_setup_theme' , 'gazeta_after_setup_theme');
}

if( !function_exists( 'gazeta_enqueue_scripts' ) ){
	function gazeta_enqueue_scripts() {
		global $gazeta_global_data;
		$min = null;
		if( isset( $gazeta_global_data['minifying'] ) && $gazeta_global_data['minifying'] == 1 ){
			$min = '.min';
		}
		wp_enqueue_script('jquery');
		if( is_singular() && comments_open() ){
			wp_enqueue_script('comment-reply');
		}
		wp_enqueue_script('bootstrap.min', GAZETA_TEMPLATE_DIRECTORY_URI . '/assets/js/bootstrap.min.js', array('jquery'), '', true);
		wp_enqueue_script('slick', GAZETA_TEMPLATE_DIRECTORY_URI . '/assets/js/vendor/slick/slick'.$min.'.js', array('jquery'), '', true);
		if( !wp_is_mobile() ){
			wp_enqueue_script('jquery.nicescroll', GAZETA_TEMPLATE_DIRECTORY_URI . '/assets/js/jquery.nicescroll'.$min.'.js', array('jquery'), '', true);
			wp_enqueue_script('jquery-ui', GAZETA_TEMPLATE_DIRECTORY_URI . '/assets/js/jquery-ui'.$min.'.js', array('jquery'), '', true);
		}
		wp_enqueue_script('css3-mediaqueries', GAZETA_TEMPLATE_DIRECTORY_URI . '/assets/js/css3-mediaqueries'.$min.'.js', array('jquery'), '', true);
		wp_enqueue_script('jquery.accordion', GAZETA_TEMPLATE_DIRECTORY_URI . '/assets/js/vendor/accordion/accordion.js', array('jquery'), '', true);
		wp_enqueue_script('jquery.prettyphoto', GAZETA_TEMPLATE_DIRECTORY_URI . '/assets/js/jquery.prettyphoto.js', array('jquery'), '', true);

		wp_enqueue_script('jquery.fitvids', GAZETA_TEMPLATE_DIRECTORY_URI . '/assets/js/jquery.fitvids'.$min.'.js', array('jquery'), '', true);
		wp_enqueue_script('jquery.responsive-video', GAZETA_TEMPLATE_DIRECTORY_URI . '/assets/js/responsive-video'.$min.'.js', array('jquery'), '', true);
			
		$mega_menu_settings = get_site_option( 'megamenu_settings' );
		if( !isset( $mega_menu_settings['main_navigation']['enabled'] ) && !wp_is_mobile() ):
			wp_enqueue_script('jquery.spasticNav', GAZETA_TEMPLATE_DIRECTORY_URI . '/assets/js/jquery.spasticNav'.$min.'.js', array('jquery'), '', true);
			wp_enqueue_script('mobile-menu', GAZETA_TEMPLATE_DIRECTORY_URI . '/assets/js/mobile-menu'.$min.'.js', array('jquery'), '', true);
		endif;		
		wp_enqueue_script('main', GAZETA_TEMPLATE_DIRECTORY_URI . '/assets/js/main'.$min.'.js', array('jquery'), '', true);
		// end scripts.
		wp_enqueue_style('font-awesome.min', GAZETA_TEMPLATE_DIRECTORY_URI . '/assets/css/font-awesome.min.css', array(), null);
		wp_enqueue_style('raleway', '//fonts.googleapis.com/css?family=Raleway:400,100,200,300,500,600,700,900,800', array(), null);
		wp_enqueue_style('oswald', '//fonts.googleapis.com/css?family=Oswald:400,700,300', array(), null);
		wp_enqueue_style('josefin', '//fonts.googleapis.com/css?family=Josefin+Sans:400,100,300,300italic,100italic,400italic,600,600italic,700,700italic', array(), null);
		wp_enqueue_style('open-sans', '//fonts.googleapis.com/css?family=Open+Sans:400,100,300,300italic,100italic,400italic,600,600italic,700,700italic', array(), null);
		wp_enqueue_style('bootstrap', GAZETA_TEMPLATE_DIRECTORY_URI . '/assets/css/bootstrap'.$min.'.css', array(), null);
		wp_enqueue_style('slick', GAZETA_TEMPLATE_DIRECTORY_URI . '/assets/js/vendor/slick/slick'.$min.'.css', array(), null);
		wp_enqueue_style('prettyphoto', GAZETA_TEMPLATE_DIRECTORY_URI . '/assets/css/prettyphoto'.$min.'.css', array(), null);
		wp_enqueue_style('restyle.css', GAZETA_TEMPLATE_DIRECTORY_URI . '/assets/css/restyle'.$min.'.css', array(), null);
		wp_enqueue_style('woocommerce', GAZETA_TEMPLATE_DIRECTORY_URI . '/assets/css/woocommerce'.$min.'.css', array( 'woocommerce-general' ), null);
		wp_enqueue_style( 'style', get_bloginfo( 'stylesheet_url' ), array(), '' );
	}
	add_action( 'wp_enqueue_scripts' , 'gazeta_enqueue_scripts');
}

if( !function_exists( 'gazeta_admin_enqueue_scripts' ) ){
	function gazeta_admin_enqueue_scripts() {
		global $gazeta_global_data;
		$min = null;
		if( isset( $gazeta_global_data['minifying'] ) && $gazeta_global_data['minifying'] == 1 ){
			$min = '.min';
		}
		wp_enqueue_style('redux-admin', GAZETA_TEMPLATE_DIRECTORY_URI . '/assets/css/redux-admin'.$min.'.css', array(), null);
	}
	add_action( 'admin_enqueue_scripts' , 'gazeta_admin_enqueue_scripts');
}


if( !function_exists( 'gazeta_admin_enqueue_scripts' ) ){
	function gazeta_admin_enqueue_scripts() {
		global $gazeta_global_data;
		$min = null;
		if( isset( $gazeta_global_data['minifying'] ) && $gazeta_global_data['minifying'] == 1 ){
			$min = '.min';
		}
		wp_enqueue_style('redux-admin', GAZETA_TEMPLATE_DIRECTORY_URI . '/assets/css/redux-admin'.$min.'.css', array(), null);
		wp_enqueue_style('metaboxes-admin', GAZETA_TEMPLATE_DIRECTORY_URI . '/assets/css/admin'.$min.'.css', array(), null);
	}
	add_action( 'admin_enqueue_scripts' , 'gazeta_admin_enqueue_scripts');
}


if( !function_exists( 'gazeta_widgets_init' ) ){
	function gazeta_widgets_init() {
		register_sidebar( array(
			'name' => __( 'Primary Sidebar', 'gazeta' ),
			'id' => 'primary-sidebar',
			'description' => __('Appears in the right section of the site.','gazeta'),
			'before_widget' => '<div id="%1$s" class="gazeta-widget side-widget %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h5 class="widget-title"><span>',
			'after_title' => '</span></h5>'
		) );
		register_sidebar( array(
			'name' => __( 'Woocommerce Sidebar', 'gazeta' ),
			'id' => 'woocommerce-sidebar',
			'description' => __('Appears in the right section of the site.','gazeta'),
			'before_widget' => '<div id="%1$s" class="gazeta-widget side-widget %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h5 class="widget-title"><span>',
			'after_title' => '</span></h5>'
		) );
		register_sidebar( array(
			'name' => __( 'Header Sidebar', 'gazeta' ),
			'id' => 'header-sidebar',
			'description' => __('The Banner may be here.','gazeta'),
			'before_widget' => '',
			'after_widget'  => '',
			'before_title'  => '',
			'after_title' => ''
		) );
		$footer_widget_columns = apply_filters( 'footer_widget_columns' , 3);
		register_sidebar( array(
			'name' => __( 'Footer Sidebar', 'gazeta' ),
			'id' => 'footer-sidebar',
			'description' => __('You can go to Appearance/Theme Options for setting the columns number, 4 columns is default','gazeta'),
			'before_widget' => '<div id="%1$s" class="footer-widget responsive-height gazeta-widget col-md-'.$footer_widget_columns.' %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h5 class="widget-title"><span>',
			'after_title' => '</span></h5>'
		) );
	}
	add_action( 'widgets_init', 'gazeta_widgets_init' );
}

if( !function_exists('gazeta_register_menus') ){
	function gazeta_register_menus() {
		register_nav_menus(array(
			'main_navigation' 	=> __('Main Navigation','gazeta'),
			'footer_navigation'	=>	__('Footer Navigation','gazeta')
		));
	}
	add_action( 'init', 'gazeta_register_menus' );
}