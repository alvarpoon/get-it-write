<?php
if( !function_exists( 'gazeta_featured_posts_shortcode' ) ){
	/**
	 * Slider Posts shortcode.
	 * @param unknown_type $attr
	 * @param unknown_type $content
	 * @return mixed
	 */
	function gazeta_featured_posts_shortcode( $attr, $content = null ) {
		$output = '';
		$current_post = get_the_ID();
		wp_reset_postdata();
		extract(shortcode_atts(array(
			'heading'				=>	'',
			'id'					=>	'main-post-' . rand(1000, 9999),
			'author__in'			=>	'',
			'post_format'			=>	'',
			'post_tags'				=>	'',
			'categories'			=>	'',
			'ignore_sticky_posts'	=>	'yes',
			'orderby'				=>	'ID',
			'order'					=>	'DESC',
			'posts_per_page'				=>	get_option( 'posts_per_page' ),
			'thumbnail_size'		=>	'image-585-472',
			'hide_post_no_featured_image'	=>	'',
			'el_class'	=>	'',
			'expiration'	=>	'300',
			'is_grid'	=>	'',
			'columns'	=>	4
		), $attr));
		
		$columns = (int)$columns;
		$bt_column_class = (12%$columns == 0 && 12>=$columns) ? 12/$columns : 1;
		$bt_class = ( $is_grid == 'yes' && isset( $bt_column_class ) ) ? 'col-md-' . $bt_column_class : null;
		$slider_class = empty( $is_grid ) ? 'bl-featured-slider' : null;
		
		$post_data = array(
			'no_found_rows'		=>	true,
			'post_type'			=>		'post',
			'post_status'		=>		'publish',
			'posts_per_page'	=>		$posts_per_page,
			'orderby'			=>		( $orderby == 'view' ) ? 'meta_value_num' : $orderby,
			'order'				=>		$order,
			'post__not_in'		=>		isset( $current_post ) ?  array( $current_post ) : '', // Do not include the current post.
		);

		// order by views
		if( $orderby == 'view' ){
			$post_data['meta_key']	=	defined( 'GAZETA_POST_VIEWS_FIELD_NAME' ) ? GAZETA_POST_VIEWS_FIELD_NAME : 'post_views';
		}
		// Post formats
		if( !empty( $post_format ) ){
			$post_format	=	explode(",", $post_format);
			if( is_array( $post_format ) && !empty( $post_format ) ){
				$post_data['tax_query'][] = array(
					'taxonomy' => 'post_format',
					'field'    => 'slug',
					'terms'    => $post_format,
					'operator'	=>	'IN'
				);
			}
		}
		if( !empty( $author__in ) ){
			$author__in = explode(",", $author__in);
			if( is_array( $author__in ) && !empty( $author__in ) ){
				$post_data['author__in'] = $author__in;
			}
		}
		// Post Tags
		if( !empty( $post_tags ) ){
			$post_tags = explode(",", $post_tags);
			if( is_array( $post_tags ) && !empty( $post_tags ) ){
				$post_data['tag_slug__in']	=	$post_tags;
			}
		}
			
		// $sticky
		if( $ignore_sticky_posts == 'yes' || $ignore_sticky_posts === true ){
			$post_data['ignore_sticky_posts']	=	true;
		}

		if( $hide_post_no_featured_image == 'yes' ){
			$post_data['meta_query']	=	array(
				array(
					'key' => '_thumbnail_id',
					'compare' => '!=',
					'value'	=>	''
				)
			);
		}

		if( !empty( $categories ) ){
			$categories = explode(",", $categories);
			if( is_array( $categories ) && !empty( $categories ) ){
				$post_data['category__in']	=	$categories;
			}
		}

		if( false !== ( $output = get_transient( $id ) ) && apply_filters( 'is_cache_active' , true) === true ):
			// check if the cache is exists.
			return $output;
		else:
			$post_data = apply_filters( 'gazeta_slider_posts/args' , $post_data, $id);
				
			$post_query = new WP_Query( $post_data );
			$is_rtl = ( is_rtl() ) ? 'yes' : 'no';
			if( $post_query->have_posts() ):
				if( empty( $bt_class ) ){
					// slider
					$output .= '<div data-rtl="'.$is_rtl.'" id="'.esc_attr( $id ).'" class="bl-featured '.$slider_class.' '.esc_attr( $el_class ).'">';
				}	
				// start loop.
				while ( $post_query->have_posts() ): $post_query->the_post();
					if( !empty( $bt_class ) ){
						$output .= '<div id="'.esc_attr( $id ). get_the_ID() .'" class="bl-featured bl-featured-grid responsive-height '.esc_attr( $el_class ).' '.esc_attr( $bt_class ).'">';
					}			
					$output .= '
						<div class="bl-featured-big">';
							if( has_post_thumbnail( get_the_ID() ) ){
								$output .= get_the_post_thumbnail( get_the_ID(), apply_filters( 'gazeta_slider_posts/thumbnail_size' , $thumbnail_size, $id ), array( 'class'=>'img-responsive' ));
							}
							$output .= '
							<div class="bl-info">';
								if( has_category( '', get_the_ID() ) ){
									if( has_category( '', get_the_ID() ) ){
										$output .= '<span>'. get_the_category_list(', ') . '</span>';
									}
								}
								$output .= '<h3 class="post-title"><a href="'.get_permalink().'">'.get_the_title().'</a></h3>';							
								$output .= '
							</div>
						</div>				
					';
					if( !empty( $bt_class ) ){
						$output .= '</div>';
					}
				endwhile;
				// end loop.
				if( empty( $bt_class ) ){
					$output .= '</div>';
				}	
			// end query.
				if( apply_filters( 'is_cache_active' , true) === true ){
					set_transient( $id , $output, apply_filters( 'transient_expiration' , $expiration));
				}
				wp_reset_postdata();
				return $output;			
			endif;
		endif;
	}
	add_shortcode( 'gazeta_featured_posts' , 'gazeta_featured_posts_shortcode');
}

if( !function_exists( 'gazeta_main_posts_shortcode' ) ){
	/**
	 * Main Posts Shortcode
	 * @param unknown_type $attr
	 * @param unknown_type $content
	 * @return mixed|string|unknown|Ambigous <string, mixed>
	 */
	function gazeta_main_posts_shortcode( $attr, $content = null ) {
		$output = '';
		$current_post = get_the_ID();
		wp_reset_postdata();
		extract(shortcode_atts(array(
			'heading'				=>	'',
			'id'					=>	'main-post-' . rand(1000, 9999),
			'columns'				=>	1,
			'author__in'			=>	'',
			'post_format'			=>	'',
			'post_tags'				=>	'',
			'categories'			=>	'',
			'ignore_sticky_posts'	=>	'yes',
			'orderby'				=>	'ID',
			'order'					=>	'DESC',
			'posts_per_page'				=>	get_option( 'posts_per_page' ),
			'thumbnail_size'		=>	'image-278-186',
			'hide_post_no_featured_image'	=>	'',
			'el_class'	=>	'',
			'show_excerpt'	=>	'',
			'excerpt_length'	=>	30,
			'expiration'	=>	'300',
			'main_query'	=>	'' // the page navigation will work if this value set to yes.
		), $attr));

		$columns = (int)$columns;
		$bt_column_class = (12%$columns == 0 && 12>=$columns) ? 12/$columns : 1;
			
		$post_data = array(
			'no_found_rows'		=>	true,
			'post_type'			=>		'post',
			'post_status'		=>		'publish',
			'posts_per_page'	=>		$posts_per_page,
			'orderby'			=>		( $orderby == 'view' ) ? 'meta_value_num' : $orderby,
			'order'				=>		$order,
			'post__not_in'		=>		isset( $current_post ) ?  array( $current_post ) : '', // Do not include the current post.
		);

		// order by views
		if( $orderby == 'view' ){
			$post_data['meta_key']	=	defined( 'GAZETA_POST_VIEWS_FIELD_NAME' ) ? GAZETA_POST_VIEWS_FIELD_NAME : 'post_views';
		}
		// Post formats
		if( !empty( $post_format ) ){
			$post_format	=	explode(",", $post_format);
			if( is_array( $post_format ) && !empty( $post_format ) ){
				$post_data['tax_query'][] = array(
					'taxonomy' => 'post_format',
					'field'    => 'slug',
					'terms'    => $post_format,
					'operator'	=>	'IN'
				);
			}
		}
		if( !empty( $author__in ) ){
			$author__in = explode(",", $author__in);
			if( is_array( $author__in ) && !empty( $author__in ) ){
				$post_data['author__in'] = $author__in;
			}
		}
		// Post Tags
		if( !empty( $post_tags ) ){
			$post_tags = explode(",", $post_tags);
			if( is_array( $post_tags ) && !empty( $post_tags ) ){
				$post_data['tag_slug__in']	=	$post_tags;
			}
		}
			
		// $sticky
		if( $ignore_sticky_posts == 'yes' || $ignore_sticky_posts === true ){
			$post_data['ignore_sticky_posts']	=	true;
		}

		if( $hide_post_no_featured_image == 'yes' ){
			$post_data['meta_query']	=	array(
				array(
					'key' => '_thumbnail_id',
					'compare' => '!=',
					'value'	=>	''
				)
			);
		}

		if( !empty( $categories ) ){
			$categories = explode(",", $categories);
			if( is_array( $categories ) && !empty( $categories ) ){
				$post_data['category__in']	=	$categories;
			}
		}
		if( $main_query == 'yes' ){
			$post_data['paged']	=	get_query_var( 'paged' ) ? intval( get_query_var( 'paged' ) ) : 1;
			$post_data['no_found_rows']	=	false;
		}
		if( false !== ( $output = get_transient( $id ) ) && apply_filters( 'is_cache_active' , true) === true ):
			// check if the cache is exists.
			return $output;
		else:
		$post_data = apply_filters( 'gazeta_main_posts/args' , $post_data, $id);
			
		$post_query = new WP_Query( $post_data );
			
		if( $post_query->have_posts() ):
			add_filter( 'excerpt_more' , function(){
				return '...';
			});
			// start the query.
			$output .= '<div id="'.esc_attr( $id ).'" class="featured-news '.esc_attr( $el_class ).'">';
				if( !empty( $heading ) ){
					$output .= '<h5><span>'.esc_attr( $heading ).'</span></h5>';
				}
				$output .= '<div class="row">';
					// Loop content.	
					while ( $post_query->have_posts() ): $post_query->the_post();
					
						$output .= '
							<div class="col-md-'.$bt_column_class.' responsive-height">
								<div class="fn-inner">';
									if( has_post_thumbnail( get_the_ID() ) ):
										$output .= '
										<div class="fn-thumb">';
											$output .= get_the_post_thumbnail( get_the_ID(), apply_filters( 'gazeta_main_posts/thumbnail_size' , $thumbnail_size, $id ), array( 'class'=>'img-responsive' ));
											// category
											if( has_category( '', get_the_ID() ) ){
												$output .= '<div class="fn-meta">';
													$output .= get_the_category_list(', ');
												$output .= '</div>';
											}
											$output .= '
										</div>
									';
									endif;
									$output .= '<h4 class="post-title"><a href="'.get_permalink().'">'.get_the_title().'</a></h4>';
									if( has_category( '', get_the_ID() ) && !has_post_thumbnail( get_the_ID() ) ){
										$output .= get_the_category_list(', ');
									}									
									$output .= '<em>
										<a href="'.gazeta_get_post_archive_link( get_the_ID() ).'"><i class="fa fa-clock-o"></i> '.get_the_date( apply_filters( 'gazeta_main_posts/time_format', '' ) ) . '</a>';
										if( get_comments_number( get_the_ID() ) ){
											$output .= ' <a href="'.get_comments_link().'"><i class="fa fa-comments"></i> '.get_comments_number( get_the_ID() ).'</a>';
										}
									$output .= '</em>';
									if( $show_excerpt == 'yes' ){
										add_filter( 'excerpt_length' , function() use( $excerpt_length ){
											return $excerpt_length;
										});
										$output .= '<p class="entry-content">'.get_the_excerpt().'</p>';
									}
							$output .= '
								</div>
							</div>						
						';
					
					endwhile;
					// end loop.
				$output .= '</div><!--end row-->';
			$output .= '</div><!--end block-->';
			if( $main_query == 'yes' ):
				$output .=  gazeta_the_posts_pagination( $post_query, false );
			endif;
		// end query.
		endif;
			if( apply_filters( 'is_cache_active' , true) === true ){
				set_transient( $id , $output, apply_filters( 'transient_expiration' , $expiration));
			}
			wp_reset_postdata();
			return $output;
		endif;
	}
	add_shortcode( 'gazeta_main_posts' , 'gazeta_main_posts_shortcode');
}

if( !function_exists( 'gazeta_category_block_shortcode' ) ){
	/**
	 *
	 * @param unknown_type $attr
	 * @param unknown_type $content
	 */
	function gazeta_category_block_shortcode( $attr, $content = null ) {
		$output = $category_link = $sc_output = '';
		$post_array = array();
		$current_post = get_the_ID();
		wp_reset_postdata();
		extract(shortcode_atts(array(
			'heading'				=>	'',
			'id'					=>	'category_block-' . rand(1000, 9999),
			'post_format'			=>	'',
			'author__in'			=>	'',
			'post_tags'				=>	'',
			'post_category'			=>	'',
			'ignore_sticky_posts'	=>	'yes',
			'orderby'				=>	'ID',
			'order'					=>	'DESC',
			'posts_per_page'		=>	get_option( 'posts_per_page' ),
			'thumbnail_size'		=>	'image-280-435',
			'hide_post_no_featured_image'	=>	'yes',
			'el_class'	=>	'category_block-' . rand(1000, 9999),
			'show_excerpt'	=>	'yes',
			'excerpt_length'	=>	30,
			'expiration'	=>	300
		), $attr));

		$post_data = array(
			'post_type'		=>		'post',
			'post_status'	=>		'publish',
			'posts_per_page'=>	$posts_per_page,
			'orderby'		=>		( $orderby == 'view' ) ? 'meta_value_num' : $orderby,
			'order'			=>		$order,
			'post__not_in'	=>		isset( $current_post ) ?  array( $current_post ) : '', // Do not include the current post.
			'cat'			=>		isset( $post_category ) ? (int)$post_category : '',
			'no_found_rows'	=>	true
		);
		if( !empty( $author__in ) ){
			$author__in = explode(",", $author__in);
			if( is_array( $author__in ) && !empty( $author__in ) ){
				$post_data['author__in'] = $author__in;
			}
		}
		if( isset( $post_category ) ){
			$category_link = get_category_link( $post_category );
		}

		// order by views
		if( $orderby == 'view' ){
			$post_data['meta_key']	=	defined( 'GAZETA_POST_VIEWS_FIELD_NAME' ) ? GAZETA_POST_VIEWS_FIELD_NAME : 'post_views';
		}

		// Post formats
		if( !empty( $post_format ) ){
			$post_format	=	explode(",", $post_format);
			if( is_array( $post_format ) && !empty( $post_format ) ){
				$post_data['tax_query'][] = array(
					'taxonomy' => 'post_format',
					'field'    => 'slug',
					'terms'    => $post_format,
					'operator'	=>	'IN'
				);
			}
		}
		// Post Tags
		if( !empty( $post_tags ) ){
			$post_tags = explode(",", $post_tags);
			if( is_array( $post_tags ) && !empty( $post_tags ) ){
				$post_data['tag_slug__in']	=	$post_tags;
			}
		}
			
		// $sticky
		if( $ignore_sticky_posts == 'yes' || $ignore_sticky_posts === true ){
			$post_data['ignore_sticky_posts']	=	true;
		}

		if( $hide_post_no_featured_image == 'yes' ){
			$post_data['meta_query']	=	array(
				array(
					'key' => '_thumbnail_id',
					'compare' => '!=',
					'value'	=>	''
				)
			);
		}

		if( false !== ( $output = get_transient( $id ) ) && apply_filters( 'is_cache_active' , true) === true ):
			return $output;
		else:
			$post_data = apply_filters( 'gazeta_category_block/args' , $post_data, $id);
				
			$post_query = new WP_Query( $post_data );
			$first_post_id = 0;
			if( $post_query->have_posts() ):
				add_filter( 'excerpt_more' , function(){
					return '...';
				});			
				$output .= '<div class="cat-blocks '.$el_class.'" id="'.$id.'">';
				if( !empty( $heading ) ){
					if( !empty( $category_link ) ){
						$output .= '<h4 class="section-heading"><span><a href="'.esc_url( $category_link ).'">'.esc_attr( $heading ).'</a></span></h4>';
					}
					else{
						$output .= '<h4 class="section-heading"><span>'.esc_attr( $heading ).'</span></h4>';
					}
				}
				while ( $post_query->have_posts() ):
					$post_query->the_post();
					$post_array[]	=	get_the_ID();
				endwhile;
				$output .= '<div class="row">';				
				if( isset( $post_array[0] ) && has_post_thumbnail( $post_array[0] ) ){
					$first_post_id = $post_array[0];
					$output .= '
						<div class="col-md-6">
							<div class="cb-big">';
								$output .= get_the_post_thumbnail( $post_array[0], apply_filters( 'gazeta_category_block/thumbnail_size' , $thumbnail_size, $id ), array( 'class'=>'img-responsive' ));
								$output .= '
							</div>
						</div>
					';
					unset( $post_array[0] );
					$post_array = array_unique( $post_array );
				}
				if( is_array( $post_array ) && !empty( $post_array ) ){
					$output .= '
						<div class="col-md-6 cb-info">
							<h5 class="post-title"><a href="'.get_permalink( $first_post_id ).'">'.get_the_title( $first_post_id ).'</a></h5>
							<span class="date">
								<a href="'.gazeta_get_post_archive_link( $first_post_id ).'"><i class="fa fa-clock-o"></i> '.get_the_date( apply_filters( 'gazeta_category_block/time_format', '' ), $first_post_id ).'</a>';
								if( get_comments_number( $first_post_id ) ){
									$output .= '<a href="'.get_comments_link( $first_post_id ).'"><i class="fa fa-comments"></i> '.get_comments_number( $first_post_id ).'</a>';
								} 
								$output .= '
							</span>';
							if( $show_excerpt == 'yes' ){
								add_filter( 'excerpt_length' , function() use( $excerpt_length ){
									return $excerpt_length;
								});
								$output .= '<p class="entry-content">'.get_the_excerpt().'</p>';
							}
							$output .= '
							<ul>';
								for ($i = 1; $i <= count( $post_array ); $i++) {
									$output .= '<li class="post-title"><a href="'.get_permalink( $post_array[$i] ).'">'.get_the_title( $post_array[$i] ).'</a></li>';
								}
							$output .= '
							</ul>
						</div>					
					';
				}
				$output .= '</div><!-- end row -->';
				$output .= '<div class="space40"></div>
						</div>';
			endif;
			if( apply_filters( 'is_cache_active' , true) === true ){
				set_transient( $id , $output, apply_filters( 'transient_expiration' , $expiration));
			}
		endif;
		wp_reset_postdata();
		return $output;
	}
	add_shortcode( 'gazeta_category_block' , 'gazeta_category_block_shortcode');
}

if( !function_exists( 'gazeta_gallery_posts_shortcode' ) ){
	/**
	 * Gallery Posts Shortcode
	 * @param unknown_type $attr
	 * @param unknown_type $content
	 * @return mixed|string|unknown|Ambigous <string, mixed>
	 */
	function gazeta_gallery_posts_shortcode( $attr, $content = null ) {
		$output = '';
		$current_post = get_the_ID();
		wp_reset_postdata();
		extract(shortcode_atts(array(
			'heading'				=>	'',
			'id'					=>	'gallery-post-' . rand(1000, 9999),
			'columns'				=>	1,
			'author__in'			=>	'',
			'post_tags'				=>	'',
			'categories'			=>	'',
			'ignore_sticky_posts'	=>	'yes',
			'orderby'				=>	'ID',
			'order'					=>	'DESC',
			'posts_per_page'		=>	get_option( 'posts_per_page' ),
			'thumbnail_size'		=>	'image-370-243',
			'hide_post_no_featured_image'	=>	'',
			'el_class'	=>	'',
			'expiration'	=>	'300',
			'main_query'	=>	'' // the page navigation will work if this value set to yes.
		), $attr));

		$columns = (int)$columns;
		$bt_column_class = (12%$columns == 0 && 12>=$columns) ? 12/$columns : 1;
			
		$post_data = array(
			'no_found_rows'		=>		true,
			'post_type'			=>		'post',
			'post_status'		=>		'publish',
			'posts_per_page'	=>		$posts_per_page,
			'orderby'			=>		( $orderby == 'view' ) ? 'meta_value_num' : $orderby,
			'order'				=>		$order,
			'post__not_in'		=>		isset( $current_post ) ?  array( $current_post ) : '', // Do not include the current post.
			'tax_query'		=>		array(
				array(
					'taxonomy' => 'post_format',
					'field'    => 'slug',
					'terms'    => array( 'post-format-gallery' ),
					'operator'	=>	'IN'
				)
			)
		);

		// order by views
		if( $orderby == 'view' ){
			$post_data['meta_key']	=	defined( 'GAZETA_POST_VIEWS_FIELD_NAME' ) ? GAZETA_POST_VIEWS_FIELD_NAME : 'post_views';
		}
		// Post formats
		if( !empty( $post_format ) ){
			$post_format	=	explode(",", $post_format);
			if( is_array( $post_format ) && !empty( $post_format ) ){
				$post_data['tax_query'][] = array(
					'taxonomy' => 'post_format',
					'field'    => 'slug',
					'terms'    => $post_format,
					'operator'	=>	'IN'
				);
			}
		}
		if( !empty( $author__in ) ){
			$author__in = explode(",", $author__in);
			if( is_array( $author__in ) && !empty( $author__in ) ){
				$post_data['author__in'] = $author__in;
			}
		}
		// Post Tags
		if( !empty( $post_tags ) ){
			$post_tags = explode(",", $post_tags);
			if( is_array( $post_tags ) && !empty( $post_tags ) ){
				$post_data['tag_slug__in']	=	$post_tags;
			}
		}
			
		// $sticky
		if( $ignore_sticky_posts == 'yes' || $ignore_sticky_posts === true ){
			$post_data['ignore_sticky_posts']	=	true;
		}

		if( $hide_post_no_featured_image == 'yes' ){
			$post_data['meta_query']	=	array(
				array(
					'key' => '_thumbnail_id',
					'compare' => '!=',
					'value'	=>	''
				)
			);
		}

		if( !empty( $categories ) ){
			$categories = explode(",", $categories);
			if( is_array( $categories ) && !empty( $categories ) ){
				$post_data['category__in']	=	$categories;
			}
		}
		if( $main_query == 'yes' ){
			if( gazeta_get_paged() ){
				// delete the cache.
				delete_transient( $id );
			}
			$post_data['paged']	=	gazeta_get_paged();
			$post_data['no_found_rows']	=	false;
		}	
		if( false !== ( $output = get_transient( $id ) ) && apply_filters( 'is_cache_active' , true) === true ):
			// check if the cache is exists.
			return $output;
		else:
		$post_data = apply_filters( 'gazeta_gallery_posts/args' , $post_data, $id);
			
		$post_query = new WP_Query( $post_data );
			
		if( $post_query->have_posts() ):
			// start the query.
			// Loop content.
			$output .= '<ul id="'.esc_attr( $id ).'" class="project grid-gallery accordion-p">';
				while ( $post_query->have_posts() ): $post_query->the_post();
					$output .= '
						<li class="responsive-height gallery-'.get_the_ID().'">
							<div class="pt-inner">
								<a class="accordion-section-title" href="#accordion-'.get_the_ID().'">
									<div class="hw-info">';
										if( has_post_thumbnail( get_the_ID() ) ){
											$output .= get_the_post_thumbnail( get_the_ID(), apply_filters( 'gazeta_gallery_posts/thumbnail_size' , $thumbnail_size, $id ), array( 'class'=>'img-responsive' ));
										}
										elseif( gazeta_get_the_first_image_gallery( get_the_ID(),  apply_filters( 'gazeta_gallery_posts/thumbnail_size' , $thumbnail_size, $id ) ) ){
											$output .= gazeta_get_the_first_image_gallery( get_the_ID(),  apply_filters( 'gazeta_gallery_posts/thumbnail_size' , $thumbnail_size, $id ) );
										}
										$output .= '
										<h4 class="post-title">'.get_the_title().'</h4>
										<span class="hw-trigger"></span>
									</div>
								</a>
								<div id="accordion-'.get_the_ID().'" class="accordion-section-content hw-expand col-md-12">
									<div class="col-md-12">
										<h3 class="post-title"><a href="'.get_permalink( get_the_ID() ).'">'.get_the_title().'</a></h3>
										<p class="entry-content">'.get_the_excerpt().'</p>
									</div>
									<div class="shots-wrap">
										<h5>'.__('Gallery Screenshots','gazeta').'</h5>
										<ul>';
											$format_gallery_images = get_post_meta( get_the_ID(), '_format_gallery_images',true );
											if( is_array( $format_gallery_images ) ){
												for ($i = 0; $i < count( $format_gallery_images ); $i++) {
													$small_image = wp_get_attachment_image_src( $format_gallery_images[$i] , apply_filters( 'accordion_gallery/thumbnail_size' , 'thumbnail'));
													$large_image = wp_get_attachment_image_src( $format_gallery_images[$i] , apply_filters( 'accordion_gallery/large_size' , 'large'));
													$output .= '<li><span><a href="'.$large_image[0].'" class="prettyPhoto"><img src="'.$small_image[0].'" class="img-responsive" alt=""/></a></span></li>';
												}
											}
										$output .= '
										</ul>
									</div>
								</div>
							</div>
						</li>					
					';
				endwhile;
				// end query.
			$output .= '</ul>';
			if( $main_query == 'yes' ):
				$output .=  gazeta_the_posts_pagination( $post_query, false );
			endif;
		endif;
			if( apply_filters( 'is_cache_active' , true) === true ){
				set_transient( $id , $output, apply_filters( 'transient_expiration' , $expiration));
			}
			wp_reset_postdata();
			return $output;
		endif;
	}
	add_shortcode( 'gazeta_gallery_posts' , 'gazeta_gallery_posts_shortcode');
}

if( !function_exists( 'gazeta_grid_posts_shortcode' ) ){
	/**
	 * Main Posts Shortcode
	 * @param unknown_type $attr
	 * @param unknown_type $content
	 * @return mixed|string|unknown|Ambigous <string, mixed>
	 */
	function gazeta_grid_posts_shortcode( $attr, $content = null ) {
		$output = '';
		$current_post = get_the_ID();
		wp_reset_postdata();
		extract(shortcode_atts(array(
			'heading'				=>	'',
			'id'					=>	'grid-post-' . rand(1000, 9999),
			'columns'				=>	3,
			'author__in'			=>	'',
			'post_format'			=>	'',
			'post_tags'				=>	'',
			'categories'			=>	'',
			'ignore_sticky_posts'	=>	'yes',
			'orderby'				=>	'ID',
			'order'					=>	'DESC',
			'posts_per_page'				=>	get_option( 'posts_per_page' ),
			'thumbnail_size'		=>	'image-370-243',
			'hide_post_no_featured_image'	=>	'',
			'el_class'	=>	'',
			'show_excerpt'	=>	'',
			'excerpt_length'	=>	30,
			'expiration'	=>	'300',
			'main_query'	=>	'' // the page navigation will work if this value set to yes.
		), $attr));

		$columns = (int)$columns;
		$bt_column_class = (12%$columns == 0 && 12>=$columns) ? 12/$columns : 1;
			
		$post_data = array(
			'no_found_rows'		=>	true,
			'post_type'			=>		'post',
			'post_status'		=>		'publish',
			'posts_per_page'	=>		$posts_per_page,
			'orderby'			=>		( $orderby == 'view' ) ? 'meta_value_num' : $orderby,
			'order'				=>		$order,
			'post__not_in'		=>		isset( $current_post ) ?  array( $current_post ) : '', // Do not include the current post.
		);

		// order by views
		if( $orderby == 'view' ){
			$post_data['meta_key']	=	defined( 'GAZETA_POST_VIEWS_FIELD_NAME' ) ? GAZETA_POST_VIEWS_FIELD_NAME : 'post_views';
		}
		// Post formats
		if( !empty( $post_format ) ){
			$post_format	=	explode(",", $post_format);
			if( is_array( $post_format ) && !empty( $post_format ) ){
				$post_data['tax_query'][] = array(
					'taxonomy' => 'post_format',
					'field'    => 'slug',
					'terms'    => $post_format,
					'operator'	=>	'IN'
				);
			}
		}
		if( !empty( $author__in ) ){
			$author__in = explode(",", $author__in);
			if( is_array( $author__in ) && !empty( $author__in ) ){
				$post_data['author__in'] = $author__in;
			}
		}
		// Post Tags
		if( !empty( $post_tags ) ){
			$post_tags = explode(",", $post_tags);
			if( is_array( $post_tags ) && !empty( $post_tags ) ){
				$post_data['tag_slug__in']	=	$post_tags;
			}
		}
			
		// $sticky
		if( $ignore_sticky_posts == 'yes' || $ignore_sticky_posts === true ){
			$post_data['ignore_sticky_posts']	=	true;
		}

		if( $hide_post_no_featured_image == 'yes' ){
			$post_data['meta_query']	=	array(
				array(
					'key' => '_thumbnail_id',
					'compare' => '!=',
					'value'	=>	''
				)
			);
		}

		if( !empty( $categories ) ){
			$categories = explode(",", $categories);
			if( is_array( $categories ) && !empty( $categories ) ){
				$post_data['category__in']	=	$categories;
			}
		}
		if( $main_query == 'yes' ){
			if( gazeta_get_paged() ){
				// delete the cache.
				delete_transient( $id );
			}
			$post_data['paged']	=	gazeta_get_paged();
			$post_data['no_found_rows']	=	false;
		}
		if( false !== ( $output = get_transient( $id ) ) && apply_filters( 'is_cache_active' , true) === true ):
			// check if the cache is exists.
			return $output;
		else:
		$post_data = apply_filters( 'gazeta_grid_posts/args' , $post_data, $id);
			
		$post_query = new WP_Query( $post_data );
			
		if( $post_query->have_posts() ):
			// start the query.
			$output .= '<div id="'.esc_attr( $id ).'" class="col-md-12 grid-posts '.esc_attr( $el_class ).'">';
				if( !empty( $heading ) ){
					$output .= '<h5><span>'.esc_attr( $heading ).'</span></h5>';
				}
				$output .= '<div class="row">';
					// Loop content.
					while ( $post_query->have_posts() ): $post_query->the_post();
						
						$output .= '
							<div class="responsive-height col-md-'.esc_attr( $bt_column_class ).'">
								<a href="'.get_permalink( get_the_ID() ).'">
									<div class="news-thumb">';
										if( has_post_thumbnail( get_the_ID() ) ){
											$output .= get_the_post_thumbnail( get_the_ID(), apply_filters( 'gazeta_grid_posts/thumbnail_size' , $thumbnail_size, $id ), array( 'class'=>'img-responsive' ));
										}
										$output .= '<h4 class="post-title">'.get_the_title( get_the_ID() ).'</h4>';
										if( get_post_format( get_the_ID() ) == 'video' ){
											$output .= '<i class="fa fa-play"></i>';
										}
										$output .= '
									</div>
								</a>
							</div>					
						';
					
					endwhile;			
				$output .= '</div>';// end row.
			$output .= '</div>'; // end block
			// end query.
			if( $main_query == 'yes' ):
				$output .=  gazeta_the_posts_pagination( $post_query, false );
			endif;			
		endif;
			if( apply_filters( 'is_cache_active' , true) === true ){
				set_transient( $id , $output, apply_filters( 'transient_expiration' , $expiration));
			}
			wp_reset_postdata();
			return $output;
		endif;
	}
	add_shortcode( 'gazeta_grid_posts' , 'gazeta_grid_posts_shortcode');
}

if( !function_exists( 'gazeta_gmap_shortcode' ) ){
	function gazeta_gmap_shortcode( $attr, $content = null ) {
		$output = '';
		extract(shortcode_atts(array(
			'heading'	=>	'',
			'email'	=>	'',
			'phone'	=>	'',
			'address'	=>	'',
			'latitude'	=>	'',
			'longitude'	=>	'',
			'zoom'	=>	13,
			'marker'	=>	'',
			'color'	=>	'ffff00',
			'saturation'	=>	20,
			'el_class'		=>	'gmap-' . rand(1000, 9999),
			'contactform_id'	=>	''
		), $attr));
		$src = isset( $marker ) ? wp_get_attachment_image_src( $marker, 'full' ) : null;
		
		if( !empty( $latitude ) && !empty( $longitude ) ){
			$output .= '<div class="map"><div class="gmap">';
				$output .= '<div data-color="'.$color.'" data-saturation="'.$saturation.'" data-latitude="'.$latitude.'" data-longitude="'.$longitude.'" data-zoom="'.$zoom.'" data-marker="'.$src[0].'" class="'.$el_class.'"  id="map"></div>';
			$output .= '</div></div>';	
			$output .= '<div class="clearfix"></div>';		
		}
		
		$output .= '
			<div class="c-info">
				<div class="row">';
					if( !empty( $heading ) || !empty( $content ) ){
						$output .= '<div class="col-md-6">';
							$output .= '<h4>'.esc_attr( $heading ).'</h4>';
							$output .= $content;
						$output .= '</div>';
					}
					if( !empty( $email ) || !empty( $phone ) || !empty( $address ) ):
						$output .= '
							<div class="col-md-6">
								<div class="cf-info">
									<ul>';
										if( !empty( $email ) ){
											$output .= '<li><span><i class="fa fa-envelope"></i></span> '.esc_attr( $email ).'</li>';
										}
										if( !empty( $phone ) ){
											$output .= '<li><span><i class="fa fa-phone"></i></span> '.esc_attr( $phone ).'</li>';
										}
										if( !empty( $address ) ){
											$output .= '<li><span><i class="fa fa-home"></i></span> '.esc_attr( $address ).'</li>';
										}
									$output .= '
									</ul>
								</div>
							</div>
						';
					endif;
					$output .= '
				</div>
			</div>	
		';
		if( !empty( $contactform_id ) ){
			$output .= '<div class="contact-form">';
				$output .= '<h5>'.__('Send Us a message','gazeta').'</h5>';
				$output .= do_shortcode( '[contact-form-7 id="'.$contactform_id.'"]' );
			$output .= '</div>';
		}
		return $output;
	}
	add_shortcode( 'gazeta_gmap' , 'gazeta_gmap_shortcode');
}