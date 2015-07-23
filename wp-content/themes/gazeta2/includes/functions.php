<?php
if( !function_exists( 'gazeta_install_required_plugins' ) ){
	function gazeta_install_required_plugins() {
		/**
		 * Array of plugin arrays. Required keys are name and slug.
		 * If the source is NOT from the .org repo, then source is also required.
		 */
		$plugins = array(
			array(
				'name'					=>	'Redux Framework',
				'slug'					=>	'redux-framework',
				'force_activation'		=>	true,
				'force_deactivation'	=>	true,
				'required'				=>	true
			),
			array(
				'name'					=>	'WPBakery Visual Composer',
				'slug'					=>	'js_composer',
				'source'				=>	GAZETA_TEMPLATE_DIRECTORY . '/plugins/js_composer.zip',
				'required'				=>	true
			),
			array(
				'name'					=>	'TwitterOauth',
				'slug'					=>	'twitteroauth',
				'source'				=>	GAZETA_TEMPLATE_DIRECTORY . '/plugins/twitteroauth.zip',
				'required'				=>	true
			),
			array(
				'name'					=>	'G-Shortcodes',
				'slug'					=>	'gshortcodes2',
				'source'				=>	GAZETA_TEMPLATE_DIRECTORY . '/plugins/gshortcodes2.zip',
				'force_activation'		=>	true,
				'force_deactivation'	=>	true,
				'required'				=>	true
			),
			array(
				'name'					=>	'Max Mega Menu',
				'slug'					=>	'megamenu',
				'required'				=>	false
			),
			array(
				'name'					=>	'WP-Polls',
				'slug'					=>	'wp-polls',
				'required'				=>	false
			),
			array(
				'name'					=>	'Vafpress Post Formats UI',
				'slug'					=>	'vafpress-post-formats-ui-develop',
				'source'				=>	GAZETA_TEMPLATE_DIRECTORY . '/plugins/vafpress-post-formats-ui-develop.zip',
				'force_activation'		=>	true,
				'force_deactivation'	=>	true,
				'required'				=>	true
			)
		);
		/**
		 * Array of configuration settings. Amend each line as needed.
		 * If you want the default strings to be available under your own theme domain,
		 * leave the strings uncommented.
		 * Some of the strings are added into a sprintf, so see the comments at the
		 * end of each line for what each argument will be.
		 */
		$config = array(
				'domain'       		=> 'gazeta',         	// Text domain - likely want to be the same as your theme.
				'default_path' 		=> '',                         	// Default absolute path to pre-packaged plugins
				'parent_menu_slug' 	=> 'themes.php', 				// Default parent menu slug
				'parent_url_slug' 	=> 'themes.php', 				// Default parent URL slug
				'menu'         		=> 'install-required-plugins', 	// Menu slug
				'has_notices'      	=> true,                       	// Show admin notices or not
				'is_automatic'    	=> true,					   	// Automatically activate plugins after installation or not
				'message' 			=> '',							// Message to output right before the plugins table
				'strings'      		=> array(
				'page_title'                       			=> __( 'Install Required Plugins', 'gazeta' ),
				'menu_title'                       			=> __( 'Install Plugins', 'gazeta' ),
				'installing'                       			=> __( 'Installing Plugin: %s', 'gazeta' ), // %1$s = plugin name
				'oops'                             			=> __( 'Something went wrong with the plugin API.', 'gazeta' ),
				'notice_can_install_required'     			=> _n_noop( 'This theme requires the following plugin: %1$s.', 'This theme requires the following plugins: %1$s.' ), // %1$s = plugin name(s)
				'notice_can_install_recommended'			=> _n_noop( 'This theme recommends the following plugin: %1$s.', 'This theme recommends the following plugins: %1$s.' ), // %1$s = plugin name(s)
				'notice_cannot_install'  					=> _n_noop( 'Sorry, but you do not have the correct permissions to install the %s plugin. Contact the administrator of this site for help on getting the plugin installed.', 'Sorry, but you do not have the correct permissions to install the %s plugins. Contact the administrator of this site for help on getting the plugins installed.' ), // %1$s = plugin name(s)
				'notice_can_activate_required'    			=> _n_noop( 'The following required plugin is currently inactive: %1$s.', 'The following required plugins are currently inactive: %1$s.' ), // %1$s = plugin name(s)
				'notice_can_activate_recommended'			=> _n_noop( 'The following recommended plugin is currently inactive: %1$s.', 'The following recommended plugins are currently inactive: %1$s.' ), // %1$s = plugin name(s)
				'notice_cannot_activate' 					=> _n_noop( 'Sorry, but you do not have the correct permissions to activate the %s plugin. Contact the administrator of this site for help on getting the plugin activated.', 'Sorry, but you do not have the correct permissions to activate the %s plugins. Contact the administrator of this site for help on getting the plugins activated.' ), // %1$s = plugin name(s)
				'notice_ask_to_update' 						=> _n_noop( 'The following plugin needs to be updated to its latest version to ensure maximum compatibility with this theme: %1$s.', 'The following plugins need to be updated to their latest version to ensure maximum compatibility with this theme: %1$s.' ), // %1$s = plugin name(s)
				'notice_cannot_update' 						=> _n_noop( 'Sorry, but you do not have the correct permissions to update the %s plugin. Contact the administrator of this site for help on getting the plugin updated.', 'Sorry, but you do not have the correct permissions to update the %s plugins. Contact the administrator of this site for help on getting the plugins updated.' ), // %1$s = plugin name(s)
				'install_link' 					  			=> _n_noop( 'Begin installing plugin', 'Begin installing plugins' ),
				'activate_link' 				  			=> _n_noop( 'Activate installed plugin', 'Activate installed plugins' ),
				'return'                           			=> __( 'Return to Required Plugins Installer', 'gazeta' ),
				'plugin_activated'                 			=> __( 'Plugin activated successfully.', 'gazeta' ),
				'complete' 									=> __( 'All plugins installed and activated successfully. %s', 'gazeta' ), // %1$s = dashboard link
				'nag_type'									=> 'updated' // Determines admin notice type - can only be 'updated' or 'error'
				)
			);
		tgmpa( $plugins, $config );
	}
	add_action( 'tgmpa_register', 'gazeta_install_required_plugins' );
}
if( !function_exists( 'gazeta_user_contactmethods' ) ){
	function gazeta_user_contactmethods( $fields ) {
		$fields['google-plus'] = __( 'Google Plus','gazeta' );
		$fields['facebook'] = __( 'Facebook','gazeta' );
		$fields['twitter'] = __( 'Twitter','gazeta' );
		$fields['instagram'] = __( 'Instagram','gazeta' );
		$fields['tumblr'] = __( 'Tumblr','gazeta' );
		$fields['youtube'] = __( 'Youtube','gazeta' );
		$fields['linkedin'] = __( 'LinkedIn','gazeta' );
		$fields['flickr'] = __( 'Flickr','gazeta' );
		$fields['weibo'] = __( 'Weibo','gazeta' );
		$fields['pinterest'] = __( 'Pinterest','gazeta' );
		return apply_filters( 'gazeta_user_contactmethods' , $fields);
	}
}

if( !function_exists( 'gazeta_get_paged' ) ){
	function gazeta_get_paged() {
		return get_query_var( 'paged' ) ? intval( get_query_var( 'paged' ) ) : 1;
	}
}

if( !function_exists( 'gazeta_get_the_weather_data' ) ){
	function gazeta_get_the_weather_data( $api, $location = null ) {
		if( !$api )
			return;
		global $wp_version;
		if( !$location || empty( $location ) ){
			$visitordata = gazeta_get_user_location();
			$location = $visitordata->city;
		}
		$args = array(
				'timeout'     => 5,
				'redirection' => 5,
				'httpversion' => '1.0',
				'user-agent'  => 'WordPress/' . $wp_version . '; ' . home_url(),
				'compress'    => false,
				'decompress'  => true,
				'sslverify'   => false
		);
		$apilink = 'http://api.wunderground.com/api/'.$api.'/conditions/forecast/q/'.$location.'.json';
		$apilink = apply_filters( 'gazeta_get_the_weather_data/apilink' , $apilink);
		$request = wp_remote_get( $apilink, $args );
		if( !is_wp_error( $request ) ){
			$response_code = wp_remote_retrieve_response_code( $request );
			if( $response_code == '200' ){
				$response_body = wp_remote_retrieve_body( $request );
				$response_body_decode = json_decode( $response_body );
				return $response_body_decode;
			}
		}
	}
}

if( !function_exists( 'gazeta_get_user_location' ) ){
	function gazeta_get_user_location() {
		global $wp_version;
		$args = array(
			'timeout'     => 5,
			'redirection' => 5,
			'httpversion' => '1.0',
			'user-agent'  => 'WordPress/' . $wp_version . '; ' . home_url(),
			'compress'    => false,
			'decompress'  => true,
			'sslverify'   => false
		);
		$request = wp_remote_get( apply_filters( 'gazeta_get_user_location/apilink' , 'http://freegeoip.net/json/' . gazeta_get_client_ip()), $args );
		if( !is_wp_error( $request ) ){
			$response_code = wp_remote_retrieve_response_code( $request );
			if( $response_code == '200' ){
				$response_body = wp_remote_retrieve_body( $request );
				$response_body_decode = json_decode( $response_body );
				return $response_body_decode;
			}
		}
	}
}

if( !function_exists( 'gazeta_get_client_ip' ) ){
	function gazeta_get_client_ip(){
		$ipaddress = '';
		if (getenv('HTTP_CLIENT_IP'))
			$ipaddress = getenv('HTTP_CLIENT_IP');
		else if(getenv('HTTP_X_FORWARDED_FOR'))
			$ipaddress = getenv('HTTP_X_FORWARDED_FOR');
		else if(getenv('HTTP_X_FORWARDED'))
			$ipaddress = getenv('HTTP_X_FORWARDED');
		else if(getenv('HTTP_FORWARDED_FOR'))
			$ipaddress = getenv('HTTP_FORWARDED_FOR');
		else if(getenv('HTTP_FORWARDED'))
			$ipaddress = getenv('HTTP_FORWARDED');
		else if(getenv('REMOTE_ADDR'))
			$ipaddress = getenv('REMOTE_ADDR');
		else
			$ipaddress = 'UNKNOWN';

		return $ipaddress;
	}
}

if( !function_exists( 'getConnectionWithAccessToken' ) ){
	function getConnectionWithAccessToken($cons_key, $cons_secret, $oauth_token, $oauth_token_secret) {
		if( class_exists( 'TwitterOAuth' ) ){
			$connection = new TwitterOAuth($cons_key, $cons_secret, $oauth_token, $oauth_token_secret);
			return $connection;
		}
		return;
	}
}
if( !function_exists( 'gazeta_find_twitter_user' ) ){
	function gazeta_find_twitter_user( $text ) {
		$text = preg_replace('/([\.|\,|\:|\|\|\>|\{|\(]?)@{1}(\w*)([\.|\,|\:|\!|\?|\>|\}|\)]?)\s/i', "$1<a href=\"http://twitter.com/$2\" class=\"twitter-user\">@$2</a>$3 ", $text);
		return $text;
	}
}
if( !function_exists( 'gazeta_get_twitter_profile_link' ) ){
	/**
	 * Get the twitter profile link.
	 * @param unknown_type $username is twitter username
	 * @return void|Ambigous <string, mixed>
	 */
	function gazeta_get_twitter_profile_link( $username ) {
		if( !$username )
			return;
		return esc_url( 'https://twitter.com/' . esc_attr( $username ) );
	}
}

if (!function_exists('gazeta_convert_string_to_link')) {
	/**
	 * Find and convert the string to link.
	 * @param unknown_type $s
	 * @return mixed
	 */
	function gazeta_convert_string_to_link($s) {
		return preg_replace('/https?:\/\/[\w\-\.!~?&+\*\'"(),\/]+/','<a target="_blank" href="$0">$0</a>',$s);
	}
}

if( !function_exists( 'gazeta_get_twitter_bigger_avatar' ) ){
	function gazeta_get_twitter_bigger_avatar( $url ) {
		if( !$url )
			return;
		$url = str_ireplace( "normal" , "bigger", $url);
		return esc_url( $url );
	}
}

if( !function_exists( 'gazeta_conver_twitter_time_to_timeago' ) ){
	function gazeta_conver_twitter_time_to_timeago($twitter_datetime = '') {
		if( !$twitter_datetime )
			return;
		$datetime = new DateTime($twitter_datetime);
		return esc_attr( $datetime->format('U') );
	}
}

if( !function_exists( 'gazeta_get_post_archive_link' ) ){
	/**
	 * Get the post archive link.
	 * return the link.
	 * @param int $post_id
	 */
	function gazeta_get_post_archive_link( $post_id ) {
		if( !$post_id )
			return;
		$post_day = get_the_date('d', $post_id);
		$post_month = get_the_date('m', $post_id);
		$post_year = get_the_date('Y', $post_id);
		$date_archive_link	=	get_day_link($post_year, $post_month, $post_day);
		return esc_url( $date_archive_link );
	}
}
if( !function_exists( 'gazeta_get_thumbnail_image_sizes' ) ){
	function gazeta_get_thumbnail_image_sizes() {
		global $_wp_additional_image_sizes;
		$image_size = array();
		if( is_array( $_wp_additional_image_sizes ) ){
			foreach ($_wp_additional_image_sizes as $k=>$v) {
				$image_size[]	=	$k;
			}
		}
		if( is_array( $image_size ) )
			return $image_size;
		return;
	}
}

if( !function_exists('gazeta_post_orderby') ){
	function gazeta_post_orderby() {
		$orderby = array(
			'ID'	=>	__('Order by Post ID','gazeta'),
			'author'	=>	__('Order by Author','gazeta'),
			'title'	=>	__('Order by Title','gazeta'),
			'name'	=>	__('Order by Post name (Post slug)','gazeta'),
			'date'	=>	__('Order by Date','gazeta'),
			'modified'	=>	__('Order by Last modified date','gazeta'),
			'rand'	=>	__('Order by Random','gazeta'),
			'comment_count'	=>	__('Order by number of Comments.','gazeta')
		);
		if( function_exists( 'stats_get_csv' ) ){
			$orderby['view']	=	__('Order by Views (require Jetpack\'s WP Stat addon activated)','gazeta');
		}
		return apply_filters( 'gazeta_post_orderby' , $orderby);
	}
}
if( !function_exists('gazeta_post_order') ){
	function gazeta_post_order(){
		$order = array(
			'ASC'	=>	__('Ascending ','gazeta'),
			'DESC'	=>	__('Descending','gazeta')
		);
		return apply_filters( 'gazeta_post_order' , $order);
	}
}
if( !function_exists( 'gazeta_get_post_views' ) ){
	function gazeta_get_post_views( $post_id ){
		if( gazeta_is_allow_viewing() !== true )
			return;
		// update view_count from wp stats
		if( isset( $post_id ) && function_exists( 'stats_get_csv' ) ){
			$random = mt_rand( 9999, 999999999 ); // hack to break cache bug

			$args = array(
				'days' => $random,
				'post_id' => $post_id,
			);

			$stats = stats_get_csv( 'postviews', $args );
			$views = ( isset( $stats['0']['views'] ) &&  $stats['0']['views'] > 0 ) ? $stats['0']['views'] : 0;
			return absint( $views );
		}
		return 0;
	}
}

if( !function_exists( 'gazeta_update_post_views' ) ){
	function gazeta_update_post_views() {
		global $post;
		if( !is_single() )
			return;
		if( gazeta_is_allow_viewing() !== true )
			return;
		$views = function_exists( 'gazeta_get_post_views' ) ? gazeta_get_post_views( $post->ID ) : 0;
		if( defined( 'GAZETA_POST_VIEWS_FIELD_NAME' ) ){
			update_post_meta( $post->ID , GAZETA_POST_VIEWS_FIELD_NAME, $views);
		}
		return;
	}
	add_action( 'wp' , 'gazeta_update_post_views', 100);
}

if( !function_exists( 'gazeta_get_post_views_text' ) ){
	function gazeta_get_post_views_text( $post_id ) {
		if( !$post_id || gazeta_get_post_views( $post_id ) == 0 )
			return;
		if( gazeta_get_post_views( $post_id ) == 1 ){
			return __('1 View','gazeta');
		}
		else{
			return sprintf( __('%s Views','gazeta'), gazeta_get_post_views( $post_id ) );
		}
	}
}
if( !function_exists( 'gazeta_is_allow_viewing' ) ){
	function gazeta_is_allow_viewing() {
		global $gazeta_global_data;
		if( !$gazeta_global_data )
			return;
		return isset( $gazeta_global_data['viewing'] ) && $gazeta_global_data['viewing'] == 1 ? true : false;
	}
}
if( !function_exists( 'gazeta_get_bt_grid_columns' ) ){
	function gazeta_get_bt_grid_columns( $columns = null ) {
		if( !$columns || $columns >= 12)
			return 12;
		if( 12%$columns == 0 ){
			return 12/$columns;
		}
		else{
			return 12;
		}
	}
}