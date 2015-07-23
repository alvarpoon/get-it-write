<?php
/**
 * Shortcode mapping.
 * JS Composer activated required.
 */
if( !function_exists( 'gshortcode_attribute' ) ){
	function gshortcode_attribute() {
		require_once ( 'attribute/orderby.php' );
		require_once ( 'attribute/order.php' );
		require_once ( 'attribute/post-category.php' );
	}
	add_action( 'init' , 'gshortcode_attribute', 9999);
}

if( !function_exists( 'gazeta_map_featured_posts_shortcode' ) ){
	function gazeta_map_featured_posts_shortcode() {
		// map the widget.
		$args = array(
			'name'	=>	__('Featured Posts','gazeta'),
			'base'	=>	'gazeta_featured_posts',
			'category'	=>	__('Gazeta','gazeta'),
			'class'	=>	'gazeta',
			'icon'	=>	'gazeta',
			'description'	=>	__('Display the Slider Posts.','gazeta'),
			'params'	=>	array(
				array(
					'type'	=>	'textfield',
					'holder'	=>	'div',
					'class'	=>	'',
					'heading'	=>	__('Heading','gazeta'),
					'param_name'	=>	'heading',
					'description'	=>	__('This heading is not shown in frontend.','gazeta')
				),
				array(
					'type'	=>	'checkbox',
					'holder'	=>	'div',
					'class'	=>	'',
					'param_name'	=>	'is_grid',
					'value'	=>	array( __('Is Grid layout? (Slider is default)','gazeta') => 'yes' )
				),					
				array(
					'type'	=>	'textfield',
					'holder'	=>	'div',
					'class'	=>	'',
					'heading'	=>	__('Columns','gazeta'),
					'param_name'	=>	'columns',
					'value'	=>	4,
					'dependency'	=>	array(
						'element'	=>	'is_grid',
						'value'	=>	'yes',
						'compare'	=>	'='
					)
				),
				array(
					'type'	=>	'textfield',
					'holder'	=>	'div',
					'class'	=>	'',
					'heading'	=>	__('ID','gazeta'),
					'param_name'	=>	'id',
					'value'	=>	'featured-posts-' . rand(1000, 9999),
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
					'type' => 'textfield',
					'heading' => __( 'Thumbnail size', 'gazeta' ),
					'param_name' => 'thumbnail_size',
					'description' => sprintf(__( 'Enter image size. Example: "thumbnail", "medium", "large", "full" or other sizes defined by current theme size: <strong>%s</strong>".', 'gazeta' ), implode(", ", gazeta_get_thumbnail_image_sizes()) ),
					'value'	=>	'image-585-472'
				),
				array(
					'type'	=>	'checkbox',
					'holder'	=>	'div',
					'class'	=>	'',
					'param_name'	=>	'ignore_sticky_posts',
					'value'	=>array( __('Ignore Sticky Posts','gazeta') => 'yes' )
				),
				array(
					'type'	=>	'checkbox',
					'holder'	=>	'div',
					'class'	=>	'',
					'param_name'	=>	'hide_post_no_featured_image',
					'value'	=>	array( __('Hide Post with no Featured Image.','gazeta') => 'yes' )
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
					'heading'	=>	__('Categories','gazeta'),
					'param_name'	=>	'categories',
					'description'	=>	sprintf( __('Specify Post Categories to retrieve, use %s, separated by comma(,).','gazeta'), '<a href="'.admin_url('edit-tags.php?taxonomy=category').'">'.__('Category IDs','gazeta').'</a>' )
				),
				array(
					'type'	=>	'orderby',
					'holder'	=>	'div',
					'class'	=>	'',
					'heading'	=>	__('Order by','gazeta'),
					'param_name'	=>	'orderby',
					'value'	=>	'ID'
				),
				array(
					'type'	=>	'order',
					'holder'	=>	'div',
					'class'	=>	'',
					'heading'	=>	__('Order','gazeta'),
					'param_name'	=>	'order',
					'value'	=>	'DESC'
				),
				array(
					'type'	=>	'textfield',
					'holder'	=>	'div',
					'class'	=>	'',
					'heading'	=>	__('Show Posts:','gazeta'),
					'param_name'	=>	'posts_per_page',
					'value'	=>	get_option( 'posts_per_page' )
				),
				array(
					'type' => 'textfield',
					'heading' => __( 'Extra class name', 'gazeta' ),
					'param_name' => 'el_class',
					'description' => __( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'gazeta' )
				)
			)
		);
		if( function_exists( 'vc_map' ) ){
			vc_map( $args );
		}
	}
	add_action( 'init' , 'gazeta_map_featured_posts_shortcode');
}

if( !function_exists( 'gazeta_map_main_posts_shortcode' ) ){
	function gazeta_map_main_posts_shortcode() {
		// map the widget.
		$args = array(
			'name'	=>	__('Main Posts','gazeta'),
			'base'	=>	'gazeta_main_posts',
			'category'	=>	__('Gazeta','gazeta'),
			'class'	=>	'gazeta',
			'icon'	=>	'gazeta',
			'description'	=>	__('Display the Main Posts.','gazeta'),
			'params'	=>	array(
				array(
					'type'	=>	'textfield',
					'holder'	=>	'div',
					'class'	=>	'',
					'heading'	=>	__('Heading','gazeta'),
					'param_name'	=>	'heading'
				),
				array(
					'type'	=>	'textfield',
					'holder'	=>	'div',
					'class'	=>	'',
					'heading'	=>	__('ID','gazeta'),
					'param_name'	=>	'id',
					'value'	=>	'main-posts-' . rand(1000, 9999),
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
					'type'	=>	'textfield',
					'holder'	=>	'div',
					'class'	=>	'',
					'heading'	=>	__('Columns','gazeta'),
					'param_name'	=>	'columns',
					'value'	=>	1,
					'description'	=>	__('Column number of the list.','gazeta')
				),
				array(
					'type' => 'textfield',
					'heading' => __( 'Thumbnail size', 'gazeta' ),
					'param_name' => 'thumbnail_size',
					'description' => sprintf(__( 'Enter image size. Example: "thumbnail", "medium", "large", "full" or other sizes defined by current theme size: <strong>%s</strong>".', 'gazeta' ), implode(", ", gazeta_get_thumbnail_image_sizes()) ),
					'value'	=>	'image-278-186'
				),
				array(
					'type'	=>	'checkbox',
					'holder'	=>	'div',
					'class'	=>	'',
					'param_name'	=>	'ignore_sticky_posts',
					'value'	=>array( __('Ignore Sticky Posts','gazeta') => 'yes' )
				),
				array(
					'type'	=>	'checkbox',
					'holder'	=>	'div',
					'class'	=>	'',
					'param_name'	=>	'hide_post_no_featured_image',
					'value'	=>	array( __('Hide Post with no Featured Image.','gazeta') => 'yes' )
				),
				array(
					'type'	=>	'checkbox',
					'holder'	=>	'div',
					'class'	=>	'',
					'param_name'	=>	'show_excerpt',
					'value'	=>	array( __('Show Post Excerpt','gazeta') => 'yes' )
				),
				array(
					'type'	=>	'textfield',
					'holder'	=>	'div',
					'class'	=>	'',
					'heading'	=>	__('Excerpt length','gazeta'),
					'param_name'	=>	'excerpt_length',
					'value'	=>	30,
					'dependency'	=>	array(
						'element'	=>	'show_excerpt',
						'value'	=>	'yes',
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
					'heading'	=>	__('Categories','gazeta'),
					'param_name'	=>	'categories',
					'description'	=>	sprintf( __('Specify Post Categories to retrieve, use %s, separated by comma(,).','gazeta'), '<a href="'.admin_url('edit-tags.php?taxonomy=category').'">'.__('Category IDs','gazeta').'</a>' )
				),
				array(
					'type'	=>	'orderby',
					'holder'	=>	'div',
					'class'	=>	'',
					'heading'	=>	__('Order by','gazeta'),
					'param_name'	=>	'orderby',
					'value'	=>	'ID'
				),
				array(
					'type'	=>	'order',
					'holder'	=>	'div',
					'class'	=>	'',
					'heading'	=>	__('Order','gazeta'),
					'param_name'	=>	'order',
					'value'	=>	'DESC'
				),
				array(
					'type'	=>	'textfield',
					'holder'	=>	'div',
					'class'	=>	'',
					'heading'	=>	__('Show Posts:','gazeta'),
					'param_name'	=>	'posts_per_page',
					'value'	=>	get_option( 'posts_per_page' )
				),
				array(
					'type' => 'textfield',
					'heading' => __( 'Extra class name', 'gazeta' ),
					'param_name' => 'el_class',
					'description' => __( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'gazeta' )
				)
			)
		);
		if( function_exists( 'vc_map' ) ){
			vc_map( $args );
		}
	}
	add_action( 'init' , 'gazeta_map_main_posts_shortcode');
}

if( !function_exists( 'gazeta_map_category_block' ) ){
	function gazeta_map_category_block() {
		// map the widget.
		$args = array(
		'name'	=>	__('Category Block','gazeta'),
		'base'	=>	'gazeta_category_block',
		'category'	=>	__('Gazeta','gazeta'),
		'class'	=>	'gazeta',
		'icon'	=>	'gazeta',
		'description'	=>	__('Display the Category Block.','gazeta'),
		'params'	=>	array(
				array(
					'type'	=>	'textfield',
					'holder'	=>	'div',
					'class'	=>	'',
					'heading'	=>	__('Heading','gazeta'),
					'param_name'	=>	'heading'
				),
				array(
					'type'	=>	'textfield',
					'holder'	=>	'div',
					'class'	=>	'',
					'heading'	=>	__('ID','gazeta'),
					'param_name'	=>	'id',
					'value'	=>	'category_block-' . rand(1000, 9999),
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
					'value'	=>array( __('Ignore Sticky Posts','gazeta') => 'yes' )
				),
				array(
					'type'	=>	'checkbox',
					'holder'	=>	'div',
					'class'	=>	'',
					'param_name'	=>	'hide_post_no_featured_image',
					'value'	=>	array( __('Hide Post with no Featured Image.','gazeta') => 'yes' )
				),
				array(
					'type'	=>	'checkbox',
					'holder'	=>	'div',
					'class'	=>	'',
					'param_name'	=>	'show_excerpt',
					'value'	=>	array( __('Show Post Excerpt','gazeta') => 'yes' )
				),
				array(
					'type'	=>	'textfield',
					'holder'	=>	'div',
					'class'	=>	'',
					'heading'	=>	__('Excerpt Length','gazeta'),
					'param_name'	=>	'excerpt_length',
					'value'	=>	30,
					'dependency'	=>	array(
						'element'	=>	'show_excerpt',
						'value'	=>	'yes',
						'compare'	=>	'='
					)
				),
				array(
					'type' => 'textfield',
					'heading' => __( 'Thumbnail size', 'gazeta' ),
					'param_name' => 'thumbnail_size',
					'description' => sprintf(__( 'Enter image size. Example: "thumbnail", "medium", "large", "full" or other sizes defined by current theme size: <strong>%s</strong>".', 'gazeta' ), implode(", ", gazeta_get_thumbnail_image_sizes()) ),
					'value'	=>	'image-280-435'
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
					'type'	=>	'post_category',
					'holder'	=>	'div',
					'class'	=>	'',
					'heading'	=>	__('Category','gazeta'),
					'param_name'	=>	'post_category'
				),
				array(
					'type'	=>	'orderby',
					'holder'	=>	'div',
					'class'	=>	'',
					'heading'	=>	__('Order by','gazeta'),
					'param_name'	=>	'orderby',
					'value'	=>	'ID'
				),
				array(
					'type'	=>	'order',
					'holder'	=>	'div',
					'class'	=>	'',
					'heading'	=>	__('Order','gazeta'),
					'param_name'	=>	'order',
					'value'	=>	'DESC'
				),
				array(
					'type'	=>	'textfield',
					'holder'	=>	'div',
					'class'	=>	'',
					'heading'	=>	__('Show Posts:','gazeta'),
					'param_name'	=>	'posts_per_page',
					'value'	=>	get_option( 'posts_per_page' )
				),
				array(
					'type' => 'textfield',
					'heading' => __( 'Extra class name', 'gazeta' ),
					'param_name' => 'el_class',
					'description' => __( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'gazeta' )
				)
			)
		);
		if( function_exists( 'vc_map' ) ){
			vc_map( $args );
		}
	}
	add_action( 'init' , 'gazeta_map_category_block');
}

if( !function_exists( 'gazeta_map_gallery_posts' ) ){
	function gazeta_map_gallery_posts() {
		$args = array(
			'name'	=>	__('Gallery Posts (Grid)','gazeta'),
			'base'	=>	'gazeta_gallery_posts',
			'category'	=>	__('Gazeta','gazeta'),
			'class'	=>	'gazeta',
			'icon'	=>	'gazeta',
			'description'	=>	__('Display the Gallery Posts.','gazeta'),
			'params'	=>	array(
				array(
					'type'	=>	'checkbox',
					'holder'	=>	'div',
					'class'	=>	'',
					'param_name'	=>	'main_query',
					'value'	=>array( __('Main Content','gazeta') => 'yes' ),
					'description'	=>	__('Make this as Main content, the page navigation will work.','gazeta')
				),
				array(
					'type'	=>	'textfield',
					'holder'	=>	'div',
					'class'	=>	'',
					'heading'	=>	__('Heading','gazeta'),
					'param_name'	=>	'heading'
				),
				array(
					'type'	=>	'textfield',
					'holder'	=>	'div',
					'class'	=>	'',
					'heading'	=>	__('ID','gazeta'),
					'param_name'	=>	'id',
					'value'	=>	'gallery_posts-' . rand(1000, 9999),
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
					'type'	=>	'textfield',
					'holder'	=>	'div',
					'class'	=>	'',
					'heading'	=>	__('Columns','gazeta'),
					'param_name'	=>	'columns',
					'value'	=>	1,
					'description'	=>	__('Column number of the list.','gazeta')
				),
				array(
					'type' => 'textfield',
					'heading' => __( 'Thumbnail size', 'gazeta' ),
					'param_name' => 'thumbnail_size',
					'description' => sprintf(__( 'Enter image size. Example: "thumbnail", "medium", "large", "full" or other sizes defined by current theme size: <strong>%s</strong>".', 'gazeta' ), implode(", ", gazeta_get_thumbnail_image_sizes()) ),
					'value'	=>	'image-370-243'
				),
				array(
					'type'	=>	'checkbox',
					'holder'	=>	'div',
					'class'	=>	'',
					'param_name'	=>	'ignore_sticky_posts',
					'value'	=>array( __('Ignore Sticky Posts','gazeta') => 'yes' )
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
					'heading'	=>	__('Post Tags','gazeta'),
					'param_name'	=>	'post_tags',
					'description'	=>	sprintf( __('Specify Post Tags to retrieve, use %s, separated by comma(,).','gazeta'), '<a href="'.admin_url('edit-tags.php?taxonomy=post_tag').'">'.__('Tag Slugs','gazeta').'</a>' )
				),
				array(
					'type'	=>	'textfield',
					'holder'	=>	'div',
					'class'	=>	'',
					'heading'	=>	__('Categories','gazeta'),
					'param_name'	=>	'categories',
					'description'	=>	sprintf( __('Specify Post Categories to retrieve, use %s, separated by comma(,).','gazeta'), '<a href="'.admin_url('edit-tags.php?taxonomy=category').'">'.__('Category IDs','gazeta').'</a>' )
				),
				array(
					'type'	=>	'orderby',
					'holder'	=>	'div',
					'class'	=>	'',
					'heading'	=>	__('Order by','gazeta'),
					'param_name'	=>	'orderby',
					'value'	=>	'ID'
				),
				array(
					'type'	=>	'order',
					'holder'	=>	'div',
					'class'	=>	'',
					'heading'	=>	__('Order','gazeta'),
					'param_name'	=>	'order',
					'value'	=>	'DESC'
				),
				array(
					'type'	=>	'textfield',
					'holder'	=>	'div',
					'class'	=>	'',
					'heading'	=>	__('Show Posts:','gazeta'),
					'param_name'	=>	'posts_per_page',
					'value'	=>	get_option( 'posts_per_page' )
				),
				array(
					'type' => 'textfield',
					'heading' => __( 'Extra class name', 'gazeta' ),
					'param_name' => 'el_class',
					'description' => __( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'gazeta' )
				)
			)
		);
		if( function_exists( 'vc_map' ) ){
			vc_map( $args );
		}
	}
	add_action( 'init' , 'gazeta_map_gallery_posts');
}

if( !function_exists( 'gazeta_map_grid_posts' ) ){
	function gazeta_map_grid_posts() {
		$args = array(
			'name'	=>	__('Grid Posts','gazeta'),
			'base'	=>	'gazeta_grid_posts',
			'category'	=>	__('Gazeta','gazeta'),
			'class'	=>	'gazeta',
			'icon'	=>	'gazeta',
			'description'	=>	__('Display the Grid Posts.','gazeta'),
			'params'	=>	array(
				array(
					'type'	=>	'checkbox',
					'holder'	=>	'div',
					'class'	=>	'',
					'param_name'	=>	'main_query',
					'value'	=>array( __('Main Content','gazeta') => 'yes' ),
					'description'	=>	__('Make this as Main content, the page navigation will work.','gazeta')
				),
				array(
					'type'	=>	'textfield',
					'holder'	=>	'div',
					'class'	=>	'',
					'heading'	=>	__('Heading','gazeta'),
					'param_name'	=>	'heading'
				),
				array(
					'type'	=>	'textfield',
					'holder'	=>	'div',
					'class'	=>	'',
					'heading'	=>	__('ID','gazeta'),
					'param_name'	=>	'id',
					'value'	=>	'grid_posts-' . rand(1000, 9999),
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
					'type'	=>	'textfield',
					'holder'	=>	'div',
					'class'	=>	'',
					'heading'	=>	__('Columns','gazeta'),
					'param_name'	=>	'columns',
					'value'	=>	3,
					'description'	=>	__('Column number of the list.','gazeta')
				),
				array(
					'type' => 'textfield',
					'heading' => __( 'Thumbnail size', 'gazeta' ),
					'param_name' => 'thumbnail_size',
					'description' => sprintf(__( 'Enter image size. Example: "thumbnail", "medium", "large", "full" or other sizes defined by current theme size: <strong>%s</strong>".', 'gazeta' ), implode(", ", gazeta_get_thumbnail_image_sizes()) ),
					'value'	=>	'image-370-243'
				),
				array(
					'type'	=>	'checkbox',
					'holder'	=>	'div',
					'class'	=>	'',
					'param_name'	=>	'ignore_sticky_posts',
					'value'	=>array( __('Ignore Sticky Posts','gazeta') => 'yes' )
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
					'heading'	=>	__('Author','gazeta'),
					'param_name'	=>	'author__in',
					'description'	=>	__('Specify Author to retrieve, use author id, separated by comma(,)','gazeta')
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
					'heading'	=>	__('Categories','gazeta'),
					'param_name'	=>	'categories',
					'description'	=>	sprintf( __('Specify Post Categories to retrieve, use %s, separated by comma(,).','gazeta'), '<a href="'.admin_url('edit-tags.php?taxonomy=category').'">'.__('Category IDs','gazeta').'</a>' )
				),
				array(
					'type'	=>	'orderby',
					'holder'	=>	'div',
					'class'	=>	'',
					'heading'	=>	__('Order by','gazeta'),
					'param_name'	=>	'orderby',
					'value'	=>	'ID'
				),
				array(
					'type'	=>	'order',
					'holder'	=>	'div',
					'class'	=>	'',
					'heading'	=>	__('Order','gazeta'),
					'param_name'	=>	'order',
					'value'	=>	'DESC'
				),
				array(
					'type'	=>	'textfield',
					'holder'	=>	'div',
					'class'	=>	'',
					'heading'	=>	__('Show Posts:','gazeta'),
					'param_name'	=>	'posts_per_page',
					'value'	=>	get_option( 'posts_per_page' )
				),
				array(
					'type' => 'textfield',
					'heading' => __( 'Extra class name', 'gazeta' ),
					'param_name' => 'el_class',
					'description' => __( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'gazeta' )
				)
			)
		);
		if( function_exists( 'vc_map' ) ){
			vc_map( $args );
		}
	}
	add_action( 'init' , 'gazeta_map_grid_posts');
}
if( !function_exists( 'gazeta_map_gmap' ) ){
	function gazeta_map_gmap() {
		$args = array(
			'name'	=>	__('Contact','gazeta'),
			'base'	=>	'gazeta_gmap',
			'category'	=>	__('Gazeta','gazeta'),
			'class'	=>	'gazeta',
			'icon'	=>	'gazeta',
			'description'	=>	__('Display the Contact form/Google Map.','gazeta'),
			'params'	=>	array(
				array(
					'type' => 'textfield',
					'heading' => __( 'Heading', 'gazeta' ),
					'param_name' => 'heading',
					'value'	=>	__('Get in touch with us','gazeta')
				),
				array(
					'type' => 'textarea_html',
					'heading' => __( 'Content', 'gazeta' ),
					'param_name' => 'content'
				),
				array(
					'type' => 'textfield',
					'heading' => __( 'E-mail address', 'gazeta' ),
					'param_name' => 'email'
				),
				array(
					'type' => 'textfield',
					'heading' => __( 'Phone', 'gazeta' ),
					'param_name' => 'phone'
				),					
				array(
					'type' => 'textfield',
					'heading' => __( 'Address', 'gazeta' ),
					'param_name' => 'address'
				),
				array(
					'type' => 'textfield',
					'heading' => __( 'Latitude', 'gazeta' ),
					'param_name' => 'latitude'
				),					
				array(
					'type' => 'textfield',
					'heading' => __( 'Longitude', 'gazeta' ),
					'param_name' => 'longitude'
				),
				array(
					'type' => 'textfield',
					'heading' => __( 'Zoom', 'gazeta' ),
					'param_name' => 'zoom',
					'value'	=>	13
				),
				array(
					'type' => 'attach_image',
					'heading' => __( 'Marker', 'gazeta' ),
					'param_name' => 'marker'
				),
				array(
					'type' => 'colorpicker',
					'heading' => __( 'Color', 'gazeta' ),
					'param_name' => 'color',
					'value'	=>	'ffff00'
				),
				array(
					'type' => 'textfield',
					'heading' => __( 'Saturation', 'gazeta' ),
					'param_name' => 'saturation',
					'value'	=>	20
				),
				array(
					'type' => 'textfield',
					'heading' => __( 'Extra class name', 'gazeta' ),
					'param_name' => 'el_class',
					'description' => __( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'gazeta' ),
					'value'	=>	'gmap-' . rand(1000, 9999)
				),
				array(
					'type' => 'textfield',
					'heading' => __( 'Contact Form 7 ID', 'gazeta' ),
					'param_name' => 'contactform_id'
				),					
			)
		);
		if( function_exists( 'vc_map' ) ){
			vc_map( $args );
		}
	}
	add_action( 'init' , 'gazeta_map_gmap');
}