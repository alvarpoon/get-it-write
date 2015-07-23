<?php
if( !function_exists( 'gazeta_the_posts_pagination' ) ){
	function gazeta_the_posts_pagination( $query, $echo = true ) {
		$pagination = '';
		global $wp_query;

		if( empty( $query ) )
			$query = $wp_query;
		if ( $query->max_num_pages < 2 ) {
			return;
		}

		$paged        = get_query_var( 'paged' ) ? intval( get_query_var( 'paged' ) ) : 1;

		$pagenum_link = html_entity_decode( get_pagenum_link() );
		$query_args   = array();
		$url_parts    = explode( '?', $pagenum_link );

		if ( isset( $url_parts[1] ) ) {
			wp_parse_str( $url_parts[1], $query_args );
		}

		$pagenum_link = remove_query_arg( array_keys( $query_args ), $pagenum_link );
		$pagenum_link = trailingslashit( $pagenum_link ) . '%_%';

		$format  = $GLOBALS['wp_rewrite']->using_index_permalinks() && ! strpos( $pagenum_link, 'index.php' ) ? 'index.php/' : '';
		$format .= $GLOBALS['wp_rewrite']->using_permalinks() ? user_trailingslashit( 'page/%#%', 'paged' ) : '?paged=%#%';

		$args = array(
			'base'     => $pagenum_link,
			'format'   => $format,
			'total'    => $query->max_num_pages,
			'current'  => $paged,
			'mid_size' => 3,
			'type'	=>	'list',
			'add_args' => array_map( 'urlencode', $query_args ),
			'prev_text' => '<i class="fa fa-angle-double-left"></i>',
			'next_text' => '<i class="fa fa-angle-double-right"></i>',
			'before_page_number'	=>	'<span>',
			'after_page_number'	=>	'</span>'
		);

		// Set up paginated links.
		$pagination = paginate_links( apply_filters( 'gazeta_old_navigation_args' , $args) );

		if ( $pagination ) :
		if( $echo === false ){
			return '<nav class="navigation pagination"><div class="nav-links">' . $pagination .'</div></nav>';
		}
		else{
			echo '<nav class="navigation pagination"><div class="nav-links">';
				echo $pagination;
			echo '</div></nav>';
		}
		endif;
	}
}

if( !function_exists( 'gazeta_custom_sidebar' ) ){
	function gazeta_custom_sidebar( $sidebar ) {
		global $post;
		if( ( is_single() || is_page() ) && isset( $post->ID )){
			$custom_sidebar = get_post_meta( $post->ID, 'custom_sidebar', true );
			
			if( empty( $custom_sidebar ) ){
				return $sidebar;
			}
			else{
				$sidebar = $custom_sidebar;
			}

		}
		return $sidebar;
	}
	add_filter( 'gazeta_custom_sidebar' , 'gazeta_custom_sidebar', 10, 1);
}

if( !function_exists( 'gazeta_post_class' ) ){
	/**
	 * Hooking into post_class filter.
	 * @param array $classes
	 * @return array
	 */
	function gazeta_post_class( $classes ) {
		global $post;
		if( !has_post_thumbnail( $post->ID ) ){
			$classes[] = 'no-post-thumbnail';
		}
		return $classes;
	}
	add_filter( 'post_class' , 'gazeta_post_class', 10, 1);
}

if( !function_exists( 'gazeta_comments_template' ) ){
	function gazeta_comments_template( $comment, $args, $depth ) {
		$GLOBALS['comment'] = $comment;
		extract($args, EXTR_SKIP);
		?>
			<li <?php comment_class(); ?> id="comment-<?php comment_ID();?>">
				<article id="div-comment-<?php comment_ID();?>" class="comment-body">
					<?php echo get_avatar( $comment->comment_author_email, $args['avatar_size'] );?>
					<div class="nc-inner">
						<h6><?php print $comment->comment_author;?> <span><?php printf( __('%s ago','gazeta'), human_time_diff( get_comment_time('U'), current_time('timestamp') ));?></span></h6>
						<div class="comment-content entry-content">
							<?php comment_text() ?>
						</div>						
						<?php comment_reply_link(array_merge( $args, array('add_below' => null, 'depth' => $depth, 'max_depth' => $args['max_depth'],'reply_text'=>$args['reply_text']))) ?>
					</div>						
				</article>	
		<?php 		
	}
}

if( !function_exists( 'gazeta_comment_form_before_fields' ) ){
	function gazeta_comment_form_before_fields() {
		if( !get_current_user_id() ){
			print '<div class="col-md-6">';
		}
	}
	add_action( 'comment_form_before_fields' , 'gazeta_comment_form_before_fields');
}

if( !function_exists( 'gazeta_comment_form_after_fields' ) ){
	function gazeta_comment_form_after_fields() {
		if( !get_current_user_id() ){
			print '</div><div class="col-md-6">';
		}
	}
	add_action( 'comment_form_after_fields' , 'gazeta_comment_form_after_fields');
}

if( !function_exists( 'gazeta_comment_form' ) ){
	function gazeta_comment_form() {
		if( !get_current_user_id() ){
			print '</div>';
		}
	}
	add_action( 'comment_form' , 'gazeta_comment_form');
}

if( !function_exists( 'gazeta_the_breadcrumb' ) ){
	function gazeta_the_breadcrumb() {
		if ( function_exists('yoast_breadcrumb') ) {
			yoast_breadcrumb('<p class="bcrumbs" id="breadcrumbs">','</p>');
		}
	}
	add_action( 'gazeta_the_breadcrumb' , 'gazeta_the_breadcrumb', 10);
}
if( !function_exists( 'gazeta_wp_link_pages' ) ){
	function gazeta_wp_link_pages( $content ) {
		ob_start();
		$args = array(
			'before'      => '<div class="page-links">' . __( 'Pages:', 'gazeta' ),
			'after'       => '</div>',
			'link_before' => '<span>',
			'link_after'  => '</span>',
		);
		wp_link_pages( apply_filters( 'gazeta_wp_link_pages_args' , $args) );
		$the_link_pages = ob_get_clean();
		return $content . $the_link_pages;
	}
	add_action( 'the_content' , 'gazeta_wp_link_pages', 7, 1);
}

if( !function_exists( 'gazeta_post_tags_categories' ) ){
	/**
	 * Hooking into the_content action.
	 * Display the tag and category.
	 * @param unknown_type $content
	 * @return string
	 */
	function gazeta_post_tags_categories( $content ) {
		ob_start();
		global $post;
		if( is_single() ){
			if( has_category( '', $post ) || has_tag( '', $post ) ):
				?>
					<div class="bs-tags">
						<?php if( has_category( '', $post ) && apply_filters( 'gazeta_post_tags_categories/category_activate' , true) === true):?>
							<span><?php _e('Categories:','gazeta');?> <?php the_category(', ');?></span>
						<?php endif;?>
						<?php if( has_tag( '', $post ) ):?>
							<span><?php the_tags();?></span>
						<?php endif;?>
					</div>
				<?php 
			endif;
		}
		return $content . ob_get_clean();
	}
	add_filter( 'the_content' , 'gazeta_post_tags_categories', 5, 1);
}
if( !function_exists( 'gazeta_post_meta' ) ){
	function gazeta_post_meta( $content ) {
		global $post;
		if( !isset( $post->ID ) || get_post_type( $post ) != 'post' && !is_single() && is_main_query() )
			return $content;
		ob_start();
		?>
			<div class="ns-meta entry-meta">
				<div class="nsm-inner">
					<span><i class="fa fa-clock-o"></i> <a href="<?php print gazeta_get_post_archive_link( $post->ID );?>"><?php print get_the_date();?></a></span>
					<?php if( get_comments_number() ):?>
						<span><a href="<?php comments_link();?>"><i class="fa fa-comments"></i> <?php print get_comments_number();?></a></span>
					<?php if( gazeta_get_post_views( $post->ID ) > 0 ):?>
						<span><i class="fa fa-eye"></i> <?php print gazeta_get_post_views( $post->ID );?></span>
					<?php endif;?>
					<?php endif;?>
					<?php do_action( 'gazeta_post_meta/meta' );?>
				</div>
			</div>
		<?php 
		return $content . ob_get_clean();;
	}
	add_action( 'the_content' , 'gazeta_post_meta', 10);
}

if( !function_exists( 'gazeta_get_embed_code_post_format' ) ){
	/**
	 * Get the embed code of video, audio format.
	 * @param unknown_type $post_id
	 */
	function gazeta_get_embed_code_post_format( $post_id ) {
		$output = '';
		$post_format = get_post_format( $post_id );
		if( post_password_required( $post_id ) )
			return;
		if( !is_singular() && has_post_thumbnail( $post_id ) ){
			$output .= '
				<div id="bl-featured-'.$post_id.'" class="entry-featured-image">
					<div class="bl-featured-big">
						<a href="'.get_permalink( $post_id ).'">';
							$output .= get_the_post_thumbnail( get_the_ID() , apply_filters( 'gazeta_post_thumbnail_size' , 'large'), array( 'class'=> 'img-responsive' ));
							if( $post_format == 'video' ){
								$output .= '<i class="fa fa-play"></i>';
							}
							else{
								$output .= '<i class="fa fa-file-audio-o"></i>';
							}
							$output .= '
						</a>
					</div>
				</div>			
			';
			return $output;
		}
		$meta_field_name = ( $post_format == 'video' ) ? '_format_video_embed' : '_format_audio_embed';
		$meta_value = get_post_meta( $post_id, $meta_field_name, true );
		if( !$meta_value )
			return;
		$meta_media	= function_exists( 'wp_oembed_get' ) ? wp_oembed_get( $meta_value ) : '';
		if( !$meta_media ){
			return '<div class="media-frame">'. do_shortcode( $meta_value ) . '</div>';
		}
		//$output .= function_exists( 'jetpack_responsive_videos_embed_html' ) ? jetpack_responsive_videos_embed_html($meta_media) : $meta_media;
		return '<div class="media-frame">'. $meta_media . '</div>';
	}
}

if( !function_exists( 'gazeta_get_the_first_image_gallery' ) ){
	/**
	 * Use this image if no Thumbnail image is set.
	 * @param unknown_type $post_id
	 * @param unknown_type $size
	 */
	function gazeta_get_the_first_image_gallery( $post_id, $size = 'large' ) {
		if( !$post_id )
			return;
		$format_gallery_images = get_post_meta( $post_id, '_format_gallery_images',true );
		if( is_array( $format_gallery_images ) ){
			$image_url = wp_get_attachment_image_src( $format_gallery_images[0] , $size);
			if( isset( $image_url ) )
				return '<img class="img-responsive" src="'.$image_url[0].'" alt="'.esc_attr( get_the_title( $post_id ) ).'">';
		}
	}
}

if( !function_exists( 'gazeta_get_post_gallery_content' ) ){
	/**
	 * @param unknown_type $post_id
	 * @param unknown_type $size
	 */
	function gazeta_get_post_gallery_content( $post_id, $size = 'large' ) {
		if( !$post_id || post_password_required( $post_id ) )
			return;
		$output = '';
		$format_gallery_images = get_post_meta( $post_id, '_format_gallery_images',true );
		if( is_array( $format_gallery_images ) ){
			$is_rtl = ( is_rtl() ) ? 'yes' : 'no';
			$output .= '<div data-rtl="'.$is_rtl.'" id="bl-featured-'.get_the_ID().'" class="bl-featured-slider bl-featured">';
				for ($i = 0; $i < count( $format_gallery_images ); $i++) {
					$image_url = wp_get_attachment_image_src( $format_gallery_images[$i] , apply_filters( 'gazeta_get_post_gallery_content/image_size' , 'image-770-460'));
					$attachment_caption = get_post_field('post_excerpt', $format_gallery_images[$i]);
					$output .= '
						<div class="bl-featured-big">
							<a href="'.get_permalink().'">
								<img src="'.esc_url( $image_url[0] ).'" class="img-responsive" alt="'.esc_attr( $attachment_caption ).'"/>';
								if( !empty( $attachment_caption ) ){
									$output .= '
										<div class="bl-info">
											<h3>'.esc_attr( $attachment_caption ).'</h3>
										</div>									
									';
								}
								$output .= '
							</a>
						</div>						
					';
				}
			$output .= '</div>';
		}
		return $output;
	}
}

if( !function_exists( 'gazeta_get_the_weather' ) ){
	function gazeta_get_the_weather( $type = 'c' ) {
		$output = '';
		global $gazeta_global_data;
		if( !isset( $gazeta_global_data ) ){
			return;
		}
		$transName = 'WeatherData';
		//delete_transient( $transName );
		if( false === ($weather_data = get_transient( $transName ) ) && apply_filters( 'is_cache_active' , true) === true ){
			if( isset( $gazeta_global_data['header_weather'] ) && $gazeta_global_data['header_weather'] == 1 ){
				$apikey = isset( $gazeta_global_data['header_weather_apikey'] ) ? esc_attr( $gazeta_global_data['header_weather_apikey'] ) : '';
				$location = isset( $gazeta_global_data['header_weather_location'] ) ? esc_attr( $gazeta_global_data['header_weather_location'] ) : '';
				if( !$apikey ){
					return;
				}
				$weather_data = gazeta_get_the_weather_data( $apikey, $location );
			}
		}
		if( !empty( $weather_data ) ){
			if( $type == 'c' ){
				$output .= isset( $weather_data->current_observation->temp_c ) ? $weather_data->current_observation->temp_c . ' C' : '';
			}
			else{
				$output .= isset( $weather_data->current_observation->temp_f ) ? $weather_data->current_observation->temp_f . ' F' : '';
			}
			if( apply_filters( 'is_cache_active' , true) === true ){
				set_transient( $transName , $weather_data, apply_filters( 'weather_transient_expiration' , 300));
			}
		}
		return $output;
	}
}

if( !class_exists('BootStrap_Walker_Nav_Menu') ){
	class BootStrap_Walker_Nav_Menu extends Walker_Nav_Menu {
		function start_lvl(&$output, $depth = 0, $args = array()) {
			$output .= "\n<ul class=\"dropdown-menu dropdown\">\n";
		}
		function start_el(&$output, $item, $depth = 0, $args = array(), $id = 0) {
			$item_html = '';
			parent::start_el($item_html, $item, $depth, $args);

			if ( $item->is_dropdown ) {
				//if ( $item->is_dropdown ) {
				//$item_html = str_replace( '<a', '<a class="dropdown-toggle" data-toggle="dropdown"', $item_html );
				$item_html = str_replace( '<a', '<a class="dropdown-toggle" data-toggle="dropdown"', $item_html );
			}

			$output .= $item_html;
		}
		function display_element($element, &$children_elements, $max_depth, $depth = 0, $args, &$output) {
			if ( $element->current )
				$element->classes[] = 'active';

			$element->is_dropdown = !empty( $children_elements[$element->ID] );

			if ( $element->is_dropdown ) {
				if ( $depth === 0 ) {
					$element->classes[] = 'dropdown';
				} elseif ( $depth === 1 ) {
					// Extra level of dropdown menu,
					// as seen in http://twitter.github.com/bootstrap/components.html#dropdowns
					$element->classes[] = 'dropdown-submenu';
				}
			}
			parent::display_element($element, $children_elements, $max_depth, $depth, $args, $output);
		}
	}
}

if( !function_exists( 'gazeta_searchform' ) ){
	function gazeta_searchform() {
		print '<input type="hidden" name="post_type" value="post" />';
	}
	add_action( 'gazeta_searchform' , 'gazeta_searchform', 10);
}

if( !function_exists( 'gazeta_related_products_args' ) ){
	function gazeta_related_products_args( $args ) {
		global $woocommerce_loop;
		$args['posts_per_page'] = 4; // 4 related products
		return $args;
	}
	add_filter( 'woocommerce_output_related_products_args', 'gazeta_related_products_args' );
}

if( !function_exists( 'gazeta_shows_top_bar' ) ){
	function gazeta_shows_top_bar() {
		global $gazeta_global_data;
		if( isset( $gazeta_global_data['top_bar'] ) && $gazeta_global_data['top_bar'] == 1 )
			return true;
		return false;
	}
}

if( !function_exists( 'gazeta_topbar_datetime_format' ) ){
	function gazeta_topbar_datetime_format( $format ) {
		global $gazeta_global_data;
		if( isset( $gazeta_global_data['header_current_time_format'] ) && !empty( $gazeta_global_data['header_current_time_format'] ) )
			return esc_attr( $gazeta_global_data['header_current_time_format'] );
		return $format;
	}
	add_filter( 'gazeta_current_datetime/format' , 'gazeta_topbar_datetime_format', 10, 1);
}

if( !function_exists('gazeta_add_custom_css') ){
	function gazeta_add_custom_css() {
		global $gazeta_global_data;
		$css = NULL;
		if( isset( $gazeta_global_data['custom_css'] ) && wp_strip_all_tags( $gazeta_global_data['custom_css'] ) != '' ){
			$css = '<style>'.wp_strip_all_tags( $gazeta_global_data['custom_css'] ).'</style>';
		}
		if( wp_is_mobile() && isset( $gazeta_global_data['custom_css_mobile'] ) && wp_strip_all_tags( $gazeta_global_data['custom_css_mobile'] ) != '' ){
			$css = '<style>'.wp_strip_all_tags( $gazeta_global_data['custom_css_mobile'] ).'</style>';
		}
		print $css;
	}
	add_action('wp_head', 'gazeta_add_custom_css');
}
if( !function_exists('gazeta_add_custom_js') ){
	function gazeta_add_custom_js() {
		global $gazeta_global_data;
		$js = NULL;
		if( isset( $gazeta_global_data['custom_js'] ) && wp_strip_all_tags( $gazeta_global_data['custom_js'] ) != '' ){
			$js .= '<script>jQuery(document).ready(function(){'. $gazeta_global_data['custom_js'] .'});</script>';
		}
		if( wp_is_mobile() && isset( $gazeta_global_data['custom_js_mobile'] ) && wp_strip_all_tags( $gazeta_global_data['custom_js_mobile'] ) != '' ){
			$js .= '<script>jQuery(document).ready(function(){'. $gazeta_global_data['custom_js_mobile'] .'});</script>';
		}
		print $js;
	}
	add_action('wp_footer', 'gazeta_add_custom_js');
}

if( !function_exists('gazeta_add_favicon') ){
	function gazeta_add_favicon() {
		global $gazeta_global_data;
		if( isset( $gazeta_global_data['favicon']['url'] ) && esc_url( $gazeta_global_data['favicon']['url'] ) ){
			print '<link rel="shortcut icon" href="'.esc_url( $gazeta_global_data['favicon']['url'] ).'">';
		}
	}
	add_action('wp_head', 'gazeta_add_favicon');
}

if( !function_exists( 'gazeta_set_sidebar_layout' ) ){
	function gazeta_set_sidebar_layout( $classes ) {
		global $gazeta_global_data;
		if( isset( $gazeta_global_data['sidebar'] ) && $gazeta_global_data['sidebar'] != 'right-sidebar' ){
			$classes[] = 'left-sidebar';
		}
		return $classes;
	}
	add_filter( 'body_class' , 'gazeta_set_sidebar_layout', 100, 1);
}

if( !function_exists( 'gazeta_is_transient_activated' ) ){
	function gazeta_is_transient_activated() {
		global $gazeta_global_data;
		if( isset( $gazeta_global_data['transient'] ) && $gazeta_global_data['transient'] == 1 )
			return true;
		return false;
	}
	add_filter( 'is_cache_active' , 'gazeta_is_transient_activated', 10, 1);
}

if( !function_exists( 'gazeta_set_transient_expiration' ) ){
	function gazeta_set_transient_expiration( $expiration ) {
		global $gazeta_global_data;
		if( isset( $gazeta_global_data['transient_expiration'] ) && !empty( $gazeta_global_data['transient_expiration'] ) ){
			$transient_expiration = (int)$gazeta_global_data['transient_expiration'];
			$expiration = $transient_expiration;
		}
		return $expiration;
	}
	add_filter( 'transient_expiration' , 'gazeta_set_transient_expiration', 100, 1);
}

if( !function_exists( 'gazeta_custom_404_page' ) ){
	function gazeta_custom_404_page() {
		global $gazeta_global_data;
		if( isset( $gazeta_global_data['404-page'] ) && get_post_status( $gazeta_global_data['404-page'] ) == 'publish' && is_404() ){
			wp_redirect( get_permalink( $gazeta_global_data['404-page'] ) );
		}
	}
	add_action( 'wp' , 'gazeta_custom_404_page');
}
if( !function_exists( 'gazeta_credits' ) ){
	function gazeta_credits() {
		global $gazeta_global_data;
		if( !empty( $gazeta_global_data['credits'] ) ){
			print '<p>' . $gazeta_global_data['credits'] . '</p>';
		}
	}
	add_action( 'gazeta_credits' , 'gazeta_credits', 10);
}

if( !function_exists( 'gazeta_footer_widget_columns' ) ){
	function gazeta_footer_widget_columns( $columns ) {
		global $gazeta_global_data;
		if( isset( $gazeta_global_data['footer-columns'] ) && absint( $gazeta_global_data['footer-columns'] ) >  1 ){
			$columns = function_exists( 'gazeta_get_bt_grid_columns' ) ? gazeta_get_bt_grid_columns( $gazeta_global_data['footer-columns'] ) : 3;
		}
		return $columns;
	}
	add_filter( 'footer_widget_columns' , 'gazeta_footer_widget_columns', 10, 1);
}