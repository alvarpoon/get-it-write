<?php
/*
 * Plugin Name: G-Shortcodes 2
 * Plugin URI:  https://phpface.net
 * Description: Gazeta Theme Shortcodes, this plugin may not work properly in another theme.
 * Author:      Toan Nguyen
 * Version:     1.0.0
 * Domain:		gazeta
 */
if( !defined( 'GSHORTCODE_PLUGIN_URL' ) ){
	define( 'GSHORTCODE_PLUGIN_URL' , plugin_dir_url(__FILE__));
}
if( !defined( 'GSHORTCODE_PLUGIN_DIR' ) ){
	define( 'GSHORTCODE_PLUGIN_DIR' , plugin_dir_path(__FILE__));
}
require_once ( GSHORTCODE_PLUGIN_DIR . '/includes/shortcodes.php' );
require_once ( GSHORTCODE_PLUGIN_DIR . '/includes/map-shortcodes.php' );
require_once ( GSHORTCODE_PLUGIN_DIR . '/includes/map-widgets.php' );
if(!function_exists('gshortcode_wp_enqueue_script')){
	function gshortcode_wp_enqueue_script() {
		global $gazeta_global_data, $post;
		$min = null;
		if( isset( $gazeta_global_data['minifying'] ) && $gazeta_global_data['minifying'] == 1 ){
			$min = '.min';
		}
		if( isset( $post->ID ) && has_shortcode( $post->post_content, 'gazeta_gmap' ) ){
			wp_enqueue_script('maps.google.com', 'http://maps.google.com/maps/api/js?sensor=true', array('jquery'), '', true);
			wp_enqueue_script('gmap', plugin_dir_url(__FILE__) . 'assets/js/gmaps'.$min.'.js', array('jquery'), '', true);
		}
		wp_enqueue_script('responsive-height',plugin_dir_url(__FILE__) . 'assets/js/grids.min.js', array('jquery'),'', true);
		wp_enqueue_script('scripts',plugin_dir_url(__FILE__) . 'assets/js/scripts'.$min.'.js', array('jquery'),'', true);
	}
	add_action('wp_enqueue_scripts', 'gshortcode_wp_enqueue_script');
}
if(!function_exists('gshortcode_languages')){
	function gshortcode_languages() {
		load_plugin_textdomain('gazeta',false,dirname( plugin_basename( __FILE__ ) ) . '/languages');
	}
	add_action('plugins_loaded', 'gshortcode_languages');
}