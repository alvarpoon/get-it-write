<?php
if( !defined('ABSPATH') ) exit; // Don't access me directly.

if( !function_exists( 'gazeta_map_subscription_widget' ) ){
	function gazeta_map_subscription_widget() {
		add_shortcode( 'gazeta_subscription' , 'gazeta_subscription_widget_shortcode');
		$args = array(
			'name'	=>	__('Jetpack Subscription','gazeta'),
			'base'	=>	'gazeta_subscription',
			'category'	=>	__('WordPress Widgets','gazeta'),
			'class'	=>	'gazeta',
			'icon'	=>	'gazeta',
			'description'	=>	__('Display the Jetpack\'s Subscription widget, require Jetpack installed.','gazeta'),
			'params'	=>	array(
				array(
					'type'	=>	'textfield',
					'holder'	=>	'div',
					'class'	=>	'',
					'heading'	=>	__('Heading','gazeta'),
					'param_name'	=>	'title'
				),
			)
		);
		if( function_exists( 'vc_map' ) ){
			vc_map( $args );
		}
	}
	add_action( 'init' , 'gazeta_map_subscription_widget');
}

if( !function_exists( 'gazeta_subscription_widget_shortcode' ) ){
	function gazeta_subscription_widget_shortcode( $atts, $content = null ){
		if( !class_exists( 'Jetpack_Subscriptions_Widget' ) ){
			return;
		}
		$output = $title = $el_class = '';
		extract( shortcode_atts( array(
			'title' => '',
		), $atts ) );

		ob_start();
		the_widget( 'Jetpack_Subscriptions_Widget', $atts, array(
			'before_widget' => '<div class="gazeta-widget side-widget jetpack_subscription_widget">',
			'after_widget'  => '</div>',
			'before_title'  => '<h5 class="widget-title"><span>',
			'after_title' => '</span></h5>'
		) );
		$output .= ob_get_clean();
		return $output;
	}
}

if( !function_exists( 'gazeta_map_polls_widget' ) ){
	function gazeta_map_polls_widget() {
		if( !class_exists( 'WP_Widget_Polls' ) )
			return;
		global $wpdb;
		$poll_array = array(
			'-1'	=>	__('Do NOT Display Poll (Disable)','gazeta'),
			'-2'	=>	__('Display Random Poll','gazeta'),
			'0'		=>	__('Display Latest Poll','gazeta')
		);
		$polls = $wpdb->get_results("SELECT pollq_id, pollq_question FROM $wpdb->pollsq ORDER BY pollq_id DESC");
		if($polls) {
			foreach($polls as $poll) {
				$pollq_question = stripslashes($poll->pollq_question);
				$pollq_id = intval($poll->pollq_id);
				$poll_array[$pollq_id] = $pollq_question;
			}
		}
		add_shortcode( 'gazeta_polls' , 'gazeta_map_polls_widget_shortcode');
		$args = array(
			'name'	=>	__('WP Polls','gazeta'),
			'base'	=>	'gazeta_polls',
			'category'	=>	__('WordPress Widgets','gazeta'),
			'class'	=>	'gazeta',
			'icon'	=>	'gazeta',
			'description'	=>	__('Display the Polls widget.','gazeta'),
			'params'	=>	array(
				array(
					'type'	=>	'textfield',
					'holder'	=>	'div',
					'class'	=>	'',
					'heading'	=>	__('Heading','gazeta'),
					'param_name'	=>	'title'
				),
				array(
					'type'	=>	'dropdown',
					'holder'	=>	'div',
					'class'	=>	'',
					'heading'	=>	__('Display Polls Archive Link Below Poll?','gazeta'),
					'param_name'	=>	'display_pollarchive',
					'value'	=>	array(
						'0'	=> __('No','gazeta'),
						'1'	=>	__('Yes','gazeta')			
					)
				),
				array(
					'type'	=>	'dropdown',
					'holder'	=>	'div',
					'class'	=>	'',
					'heading'	=>	__('Poll To Display:','gazeta'),
					'param_name'	=>	'poll_id',
					'value'	=>	$poll_array
				),					
			)
		);
		if( function_exists( 'vc_map' ) ){
			vc_map( $args );
		}
	}
	add_action( 'init' , 'gazeta_map_polls_widget');
}

if( !function_exists( 'gazeta_map_polls_widget_shortcode' ) ){
	function gazeta_map_polls_widget_shortcode( $atts, $content = null ){
		if( !class_exists( 'WP_Widget_Polls' ) ){
			return;
		}
		$output = $title = $el_class = '';
		extract( shortcode_atts( array(
			'title' => '',
			'display_pollarchive'	=>	'',
			'poll_id'	=>	''
		), $atts ) );
		
		ob_start();
		the_widget( 'WP_Widget_Polls', $atts, array(
			'before_widget' => '<div class="gazeta-widget side-widget p-news">',
			'after_widget'  => '</div>',
			'before_title'  => '<h5 class="widget-title"><span>',
			'after_title' => '</span></h5>'
		) );
		$output .= ob_get_clean();
		return $output;		
	}
}

if( !function_exists( 'gazeta_map_aside_posts_shortcode' ) ){
	// Mapping the Related Posts widget.
	function gazeta_map_aside_posts_shortcode() {
		// add the shortcode.
		add_shortcode( 'gazeta_aside_posts' , 'gazeta_aside_posts_shortcode');
		// map the widget.
		$args = array(
			'name'	=>	__('Aside Posts Widget','gazeta'),
			'base'	=>	'gazeta_aside_posts',
			'category'	=>	__('WordPress Widgets','gazeta'),
			'class'	=>	'gazeta',
			'icon'	=>	'gazeta',
			'description'	=>	__('Display the Aside Posts widget.','gazeta'),
			'params'	=>	array(
				array(
					'type'	=>	'textfield',
					'holder'	=>	'div',
					'class'	=>	'',
					'heading'	=>	__('Heading','gazeta'),
					'param_name'	=>	'title'
				),
				array(
					'type'	=>	'textfield',
					'holder'	=>	'div',
					'class'	=>	'',
					'heading'	=>	__('ID','gazeta'),
					'param_name'	=>	'id',
					'value'	=>	'aside-posts-' . rand(1000, 9999),
					'description'	=>	__('<strong>IMPORTANT!</strong> You should change to an UNIQUE NAME, don\'t keep the default.','gazeta')
				),
				array(
					'type'	=>	'textfield',
					'holder'	=>	'div',
					'class'	=>	'',
					'heading'	=>	__('Caching Expiration','gazeta'),
					'param_name'	=>	'expiration',
					'value'	=>	'300',
					'description'	=>	__('Time until expiration in seconds from now, or 0 for never expires. Ex: For one day, the expiration value would be: (60 * 60 * 24).','gazeta')
				),
				array(
					'type'	=>	'checkbox',
					'holder'	=>	'div',
					'class'	=>	'',
					'param_name'	=>	'ignore_sticky_posts',
					'value'	=>array( __('Ignore Sticky Posts','gazeta') => 'on' )
				),
				array(
					'type'	=>	'checkbox',
					'holder'	=>	'div',
					'class'	=>	'',
					'param_name'	=>	'shows_thumbnail_image',
					'value'	=>array( __('Shows Thumbnail Image','gazeta') => 'on' )
				),				
				array(
					'type' => 'textfield',
					'heading' => __( 'Thumbnail size', 'gazeta' ),
					'param_name' => 'thumbnail_size',
					'description' => sprintf(__( 'Enter image size. Example: "thumbnail", "medium", "large", "full" or other sizes defined by current theme size: <strong>%s</strong>".', 'gazeta' ), implode(", ", gazeta_get_thumbnail_image_sizes()) ),
					'value'	=>	'image-110-81',
					'dependency'	=>	array(
						'element'	=>	'shows_thumbnail_image',
						'value'	=>	'on',
						'compare'	=>	'='
					)					
				),
				array(
					'type'	=>	'textfield',
					'holder'	=>	'div',
					'class'	=>	'',
					'heading'	=>	__('Author','gazeta'),
					'param_name'	=>	'author__in',
					'description'	=>	__('Specify Author to retrieve, use author id, separated by comma(,)','gazeta')
				),
				array(
					'type'	=>	'textfield',
					'holder'	=>	'div',
					'class'	=>	'',
					'heading'	=>	__('Post Format','gazeta'),
					'param_name'	=>	'post_format',
					'description'	=>	__('Specify Post Format to retrieve (post-format-standard, post-format-audio, post-format-gallery,post-format-image,post-format-video), leave blank for all.','gazeta')
				),
				array(
					'type'	=>	'textfield',
					'holder'	=>	'div',
					'class'	=>	'',
					'heading'	=>	__('Post Tags','gazeta'),
					'param_name'	=>	'post_tags',
					'description'	=>	sprintf( __('Specify Post Tags to retrieve, use %s, separated by comma(,).','gazeta'), '<a href="'.admin_url('edit-tags.php?taxonomy=post_tag').'">'.__('Tag Slugs','gazeta').'</a>' )
				),
				array(
					'type'	=>	'textfield',
					'holder'	=>	'div',
					'class'	=>	'',
					'heading'	=>	__('Category','gazeta'),
					'param_name'	=>	'post_categories',
					'description'	=>	sprintf( __('Specify Post Categories to retrieve, use %s, separated by comma(,).','gazeta'), '<a href="'.admin_url('edit-tags.php?taxonomy=category').'">'.__('Category IDs','gazeta').'</a>' )
				),
				array(
					'type'	=>	'orderby',
					'holder'	=>	'div',
					'class'	=>	'',
					'heading'	=>	__('Order by','gazeta'),
					'param_name'	=>	'orderby'
				),
				array(
					'type'	=>	'order',
					'holder'	=>	'div',
					'class'	=>	'',
					'heading'	=>	__('Order','gazeta'),
					'param_name'	=>	'order'
				),
				array(
					'type'	=>	'textfield',
					'holder'	=>	'div',
					'class'	=>	'',
					'heading'	=>	__('Show Posts:','gazeta'),
					'param_name'	=>	'posts_per_page',
					'value'	=>	'5'
				),
			)
		);
		if( function_exists( 'vc_map' ) ){
			vc_map( $args );
		}
	}
	add_action( 'init' , 'gazeta_map_aside_posts_shortcode');
}

if( !function_exists( 'gazeta_aside_posts_shortcode' ) ){
	function gazeta_aside_posts_shortcode( $atts, $content = null ) {
		$output = $title = $el_class = '';
		extract( shortcode_atts( array(
			'title' => '',
			'ignore_sticky_posts'	=>	'',
			'shows_thumbnail_image'	=>	'',
			'thumbnail_size'	=>	'',
			'author__in'	=>	'',
			'post_format'	=>	'',
			'post_tags'	=>	'',
			'post_categories'	=>	'',
			'orderby'	=>	'',
			'order'	=>	'',
			'posts_per_page'	=>	'',
			'el_class' => '',
			'id'	=>	'',
			'expiration'	=>	300
		), $atts ) );

		ob_start();
		the_widget( 'Gazeta_Aside_Posts', $atts, array(
			'before_widget' => '<div class="gazeta-widget side-widget p-news">',
			'after_widget'  => '</div>',
			'before_title'  => '<h5 class="widget-title"><span>',
			'after_title' => '</span></h5>'
		) );
		$output .= ob_get_clean();
		return $output;
	}
}

if( !function_exists( 'gazeta_map_twitter_feed' ) ){
	function gazeta_map_twitter_feed() {
		add_shortcode( 'gazeta_twitter_feed' , 'gazeta_twitter_feed_shortcode');
		$args = array(
			'name'	=>	__('Twitter Feeds','gazeta'),
			'base'	=>	'gazeta_twitter_feed',
			'category'	=>	__('WordPress Widgets','gazeta'),
			'class'	=>	'gazeta',
			'icon'	=>	'gazeta',
			'description'	=>	__('Display the Twitter Feeds widget.','gazeta'),
			'params'	=>	array(
				array(
					'type'	=>	'textfield',
					'holder'	=>	'div',
					'class'	=>	'',
					'heading'	=>	__('Title','gazeta'),
					'param_name'	=>	'title'
				),
				array(
					'type'	=>	'textfield',
					'holder'	=>	'div',
					'class'	=>	'',
					'heading'	=>	__('Caching Expiration','gazeta'),
					'param_name'	=>	'expiration',
					'value'	=>	'300',
					'description'	=>	__('Time until expiration in seconds from now, or 0 for never expires. Ex: For one day, the expiration value would be: (60 * 60 * 24).','gazeta')
				),
				array(
					'type'	=>	'dropdown',
					'holder'	=>	'div',
					'class'	=>	'',
					'heading'	=>	__('Layout','gazeta'),
					'param_name'	=>	'layout',
					'value'	=>	array(
						__('List','gazeta')	=>	'list',
						__('Block','gazeta')	=>	'block'
					)
				),
				array(
					'type'	=>	'checkbox',
					'holder'	=>	'div',
					'class'	=>	'',
					'param_name'	=>	'thumbnail',
					'value'	=>array( __('Display Twitter Avatar','gazeta') => 'on' )
				),
				array(
					'type'	=>	'textfield',
					'holder'	=>	'div',
					'class'	=>	'',
					'heading'	=>	__('Twitter Username:','gazeta'),
					'param_name'	=>	'username'
				),
				array(
					'type'	=>	'textfield',
					'holder'	=>	'div',
					'class'	=>	'',
					'heading'	=>	__('Consumer Key:','gazeta'),
					'param_name'	=>	'consumerkey'
				),
				array(
					'type'	=>	'textfield',
					'holder'	=>	'div',
					'class'	=>	'',
					'heading'	=>	__('Consumer Secret:','gazeta'),
					'param_name'	=>	'consumersecret'
				),
				array(
					'type'	=>	'textfield',
					'holder'	=>	'div',
					'class'	=>	'',
					'heading'	=>	__('Access Token:','gazeta'),
					'param_name'	=>	'accesstoken'
				),
				array(
					'type'	=>	'textfield',
					'holder'	=>	'div',
					'class'	=>	'',
					'heading'	=>	__('Access Token Secret:','gazeta'),
					'param_name'	=>	'accesstokensecret'
				),
				array(
					'type'	=>	'textfield',
					'holder'	=>	'div',
					'class'	=>	'',
					'heading'	=>	__('How many tweets will be shown?','gazeta'),
					'param_name'	=>	'shows',
					'value'		=>	5
				)
			)
		);
		if( function_exists( 'vc_map' ) ){
			vc_map( $args );
		}
	}
	add_action( 'init' , 'gazeta_map_twitter_feed');
}

if( !function_exists( 'gazeta_twitter_feed_shortcode' ) ){
	function gazeta_twitter_feed_shortcode( $atts, $content = null ) {
		$output = $title = $el_class = '';
		extract( shortcode_atts( array(
			'title' => '',
			'el_class' => '',
			'expiration'	=>	300
		), $atts ) );

		ob_start();
		the_widget( 'Gazeta_Twitters', $atts, array(
			'before_widget' => '<div class="gazeta-widget side-widget twitter-feeds">',
			'after_widget'  => '</div>',
			'before_title'  => '<h5 class="widget-title"><span>',
			'after_title' => '</span></h5>'
		));
		$output .= ob_get_clean();
		return $output;
	}
}