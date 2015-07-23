<?php
if( !function_exists( 'gazeta_init_widgets' ) ){
	function gazeta_init_widgets() {	
		// Advanced Sidebar Posts
		if( class_exists( 'Gazeta_Aside_Posts' ) ){
			register_widget('Gazeta_Aside_Posts');
		}
		if( class_exists( 'Gazeta_Twitters' ) ){
			register_widget('Gazeta_Twitters');
		}
		if( class_exists( 'Gazeta_MegaMenu_Posts' ) ){
			register_widget('Gazeta_MegaMenu_Posts');
		}		
	}
	add_action( 'widgets_init' , 'gazeta_init_widgets');
}

if( !class_exists( 'Gazeta_Aside_Posts' ) ){
	/**
	 * Related Posts Widget.
	 * @author ADMIN
	 *
	 */
	class Gazeta_Aside_Posts extends WP_Widget{
		function Gazeta_Aside_Posts() {
			parent::__construct(
				'gazeta-aside-posts', // Base ID
				__('Gazeta Aside Posts Widget', 'gazeta'), // Name
				array( 'classname'=>'p-news' ,'description' => __('Display the Aside Posts Widget', 'gazeta')) // Args
			);
		}
		function widget($args, $instance){
			$output = '';
			wp_reset_postdata();
			$current_post_tags = $current_post_categories = null;
			$current_post = get_the_ID();
			$title 				= !empty( $instance['title'] ) ? apply_filters('widget_title', esc_attr( $instance['title'] ) ) : null;
			$id 				= isset( $instance['id'] ) ? esc_attr( $instance['id'] ) : '';
			$expiration 		= isset( $instance['expiration'] ) ? esc_attr( $instance['expiration'] ) : '';
			$layout 			= isset( $instance['layout'] ) ? esc_attr( $instance['layout'] ) : 'classic';
			$shows_thumbnail_image = isset( $instance['shows_thumbnail_image'] ) ? esc_attr( $instance['shows_thumbnail_image'] ) : '';
			$thumbnail_size		= isset( $instance['thumbnail_size'] ) ? esc_attr( $instance['thumbnail_size'] ) : '';
			$post_format 		= isset( $instance['post_format'] ) ? esc_attr( $instance['post_format'] ) : '';
			$author__in 		= isset( $instance['author__in'] ) ? esc_attr( $instance['author__in'] ) : '';
			$post_tags 			= isset( $instance['post_tags'] ) ? esc_attr( $instance['post_tags'] ) : '';
			$post_categories 	= isset( $instance['post_categories'] ) ? esc_attr( $instance['post_categories'] ) : '';
			$sticky 			= isset( $instance['ignore_sticky_posts'] ) ? esc_attr( $instance['ignore_sticky_posts'] ) : 'on';
			$orderby 			= isset( $instance['orderby'] ) ? esc_attr( $instance['orderby'] ) : 'ID';
			$order 				= isset( $instance['order'] ) ? esc_attr( $instance['order'] ) : 'DESC';
			$posts_per_page 			= isset( $instance['posts_per_page'] ) ? absint( $instance['posts_per_page'] ) : 5;
			
			$widget_id = isset( $this->id ) ? $this->id : $id;
			
			if( !isset( $expiration ) ){
				$expiration = apply_filters( 'transient_expiration' , 300);
			}
				
			$post_data = array(
				'no_found_rows'	=>		true,
				'post_type'		=>		'post',
				'post_status'	=>		'publish',
				'posts_per_page'=>		$posts_per_page,
				'orderby'		=>		( $orderby == 'view' ) ? 'meta_value_num' : $orderby,
				'order'			=>		$order,
				'post__not_in'	=>		array( $current_post ) // Do not include the current post.
			);
				
			// order by views
			if( $orderby == 'view' ){
				$post_data['meta_key']	=	GAZETA_POST_VIEWS_FIELD_NAME;
			}
			if( !empty( $author__in ) ){
				$author__in = explode(",", $author__in);
				if( is_array( $author__in ) && !empty( $author__in ) ){
					$post_data['author__in'] = $author__in;
				}
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
			// Post Categories
			if( !empty( $post_categories ) ){
				$post_categories = explode(",", $post_categories);
				if( is_array( $post_categories ) && !empty( $post_categories ) ){
					$post_data['category__in']	=	$post_categories;
				}
			}
			// $sticky
			if( $sticky == 'on' ){
				$post_data['ignore_sticky_posts']	=	true;
			}
				
			$post_data = apply_filters( 'gazeta_posts_widget/args' , $post_data, $this->id);
				
			$post_query = new WP_Query( $post_data );
			if( $layout == 'modern' ){
				$before_widget = str_ireplace("p-news", "m-comment", $args['before_widget']);
				echo $before_widget;
			}
			else{
				echo $args['before_widget'];
			}
			
			if ( $title ) {
				echo $args['before_title'] . $title . $args['after_title'];
			}

			$hide_thumbnail_image = empty( $shows_thumbnail_image ) ? 'no-post-thumbnail' : null;
			
			if( false !== ( $output = get_transient( $widget_id ) ) && apply_filters( 'is_cache_active' , true) === true ){
				// check if the cache is exists.
				echo $output;
			}
			else{
				if( $post_query->have_posts() ):
					// classic layout
					if( $layout == 'classic' ):
				
					$output .= '<div class="sw-inner"><ul>';
						while ( $post_query->have_posts() ): $post_query->the_post();
							$output .= '
								<li class="'.join( ' ', get_post_class( 'post-item', '' ) ).'">';
									if( has_post_thumbnail( get_the_ID() ) && !empty( $shows_thumbnail_image ) ){
										$output .= '<div class="post-thumnail-image">';
											$output .= '<a href="'.get_permalink( get_the_ID() ).'">';
												$output .= get_the_post_thumbnail( get_the_ID(), apply_filters( 'gazeta_aside_posts_widget/thumbnail_size' , $thumbnail_size ) , array( 'class'=>'img-responsive' ) );
											$output .= '</a>';									
										$output .= '</div>';
									}
									$output .= '
									<div class="pn-info '.$hide_thumbnail_image.'">';
									if( has_category( '', get_the_ID() ) ){
										$output .= '<span class="cat-default">';
											$output .= get_the_category_list(', ');
										$output .= '</span>';
									}
									$output .= '
										<em>
											<a href="'.gazeta_get_post_archive_link( get_the_ID() ).'"><i class="fa fa-clock-o"></i> '.apply_filters( 'gazeta_aside_posts_widget/time_format' , get_the_date()).'</a>'; 
											if( get_comments_number( get_the_ID() ) ){
												$output .= '<a href="'.get_comments_link( get_the_ID() ).'"><i class="fa fa-comments"></i> '.get_comments_number( get_the_ID() ).'</a>';	
											}
											$output .= '
										</em>
										<h4 class="post-title"><a href="'.get_permalink( get_the_ID() ).'">'.get_the_title().'</a></h4>
									</div>
								</li>							
							';
						endwhile;
					$output .= '</ul></div>'; // end inner.
					else:
					// modern layout
						$output .= '<ul>';
						while ( $post_query->have_posts() ): $post_query->the_post();
							$output .= '
								<li>';
								if( has_post_thumbnail( get_the_ID() ) ){
									$output .= get_the_post_thumbnail( get_the_ID(), apply_filters( 'gazeta_aside_posts_widget/thumbnail_size' , $thumbnail_size ) , array( 'class'=>'img-responsive' ) );
								}
								if( get_post_format( get_the_ID() ) == 'video' ){
									$output .= '<i class="fa fa-play"></i>';
								}
								if( has_category( '', get_the_ID() ) ){
									$output .= '<span>';
										$output .= get_the_category_list(', ');
									$output .= '</span>';
								}								
								$output .= '
									<h4><a href="'.get_permalink( get_the_ID() ).'">'.get_the_title().'</a></h4>
								</li>							
							';
						endwhile;
						$output .= '</ul>';
					endif;
				else:
					$output .= '<div class="alert alert-warning" role="alert">'.__('Nothing found!','gazeta').'</div>';
				endif;
				if( apply_filters( 'is_cache_active' , true) === true ){
					set_transient( $widget_id , $output, $expiration);
				}
				echo $output;				
			}

			echo $args['after_widget'];
			wp_reset_postdata();
		}
		function update( $new_instance, $old_instance ) {
			delete_transient( $this->id );
			$instance 						= $old_instance;
			$instance['title'] 				= esc_attr( $new_instance['title'] );
			$instance['layout'] 			= esc_attr( $new_instance['layout'] );
			$instance['expiration']			= esc_attr( $new_instance['expiration'] );
			$instance['layout'] 			= esc_attr( $new_instance['layout'] );
			$instance['shows_thumbnail_image'] 			= esc_attr( $new_instance['shows_thumbnail_image'] );
			$instance['thumbnail_size']		= esc_attr( $new_instance['thumbnail_size'] );
			$instance['post_format'] 		= esc_attr( $new_instance['post_format'] );
			$instance['author__in']		=	esc_attr( $new_instance['author__in'] );
			$instance['post_tags'] 			= esc_attr( $new_instance['post_tags'] );
			$instance['post_categories'] 	= esc_attr( $new_instance['post_categories'] );
			$instance['ignore_sticky_posts']=	esc_attr( $new_instance['ignore_sticky_posts'] );
			$instance['orderby']			=	esc_attr( $new_instance['orderby'] );
			$instance['order']				=	esc_attr( $new_instance['order'] );
			$instance['posts_per_page']			=	absint( $new_instance['posts_per_page'] );
			return $instance;
		}
		function form( $instance ){
			$defaults = array(
				'title' 			=> __('Recent Posts', 'gazeta'),
				'layout'			=>	'classic',
				'expiration'		=>	300,
				'shows_thumbnail_image'	=>	'on',
				'thumbnail_size'	=>	'image-110-81',
				'post_format'		=>	'',
				'author__in'		=>	'',
				'post_tags'			=>	'',
				'post_categories'	=>	'',
				'ignore_sticky_posts'		=>	'on',
				'category'			=>	'',
				'orderby'			=>	'ID',
				'order'				=>	'DESC',
				'posts_per_page'			=>	5,
			);
			$instance = wp_parse_args( (array) $instance, $defaults );			
			?>
				<p>
					<label for="<?php echo esc_attr( $this->get_field_id( 'title' ));?>"><?php _e('Title:', 'gazeta');?></label>
					<input id="<?php echo esc_attr( $this->get_field_id( 'title' ));?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) );?>" value="<?php echo esc_attr( $instance['title'] );?>" style="width:100%;" />
				</p>
				<p>  
				    <label for="<?php echo esc_attr( $this->get_field_id( 'layout' ) ); ?>"><?php _e('Layout:', 'gazeta'); ?></label>
				    <select style="width:100%;" id="<?php echo esc_attr( $this->get_field_id( 'layout' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'layout' ) ); ?>">
				    	<?php 
				    		foreach ( $this->layout() as $key=>$value ){
				    			?>
				    				<option <?php selected( esc_attr( $instance['layout'] ) , esc_attr( $key ), true);?> value="<?php print esc_attr( $key );?>"><?php print esc_attr( $value );?></option>
				    			<?php 
				    		}
				    	?>
				    </select>
				</p>				
				<p>
					<label for="<?php echo esc_attr( $this->get_field_id( 'shows_thumbnail_image' ));?>"><?php _e('Shows Thumbnail Image:', 'gazeta');?></label>
					<input type="checkbox" <?php checked( 'on' , esc_attr( $instance['shows_thumbnail_image'] ));?> id="<?php echo esc_attr( $this->get_field_id( 'shows_thumbnail_image' ));?>" name="<?php echo esc_attr( $this->get_field_name( 'shows_thumbnail_image' ) );?>"/>
				</p>				
				<p>
					<label for="<?php echo esc_attr( $this->get_field_id( 'thumbnail_size' ));?>"><?php _e('Thumbnail Size:', 'gazeta');?></label>
					<input id="<?php echo esc_attr( $this->get_field_id( 'thumbnail_size' ));?>" name="<?php echo esc_attr( $this->get_field_name( 'thumbnail_size' ) );?>" value="<?php echo esc_attr( $instance['thumbnail_size'] );?>" style="width:100%;" />
					<small><?php printf(__( 'Enter image size. Example: "thumbnail", "medium", "large", "full" or other sizes defined by current theme size: <strong>%s</strong>".', 'gazeta' ), implode(", ", gazeta_get_thumbnail_image_sizes()) );?></small>
				</p>
				<p>
					<label for="<?php echo $this->get_field_id( "expiration" ); ?>"><?php _e( 'Caching Expiration','gazeta' ); ?></label>
					<input id="<?php echo $this->get_field_id( "expiration" ); ?>" name="<?php echo $this->get_field_name( "expiration" ); ?>" type="text" value="<?php echo esc_attr( $instance["expiration"] ); ?>" style="width:100%;"/>
					<small><?php _e('Time until expiration in seconds from now, or 0 for never expires. Ex: For one day, the expiration value would be: (60 * 60 * 24).','gazeta')?></small>
				</p>				
				<p>
					<label for="<?php echo $this->get_field_id( "author__in" ); ?>"><?php _e( 'Author','gazeta' ); ?></label>
					<input id="<?php echo $this->get_field_id( "author__in" ); ?>" name="<?php echo $this->get_field_name( "author__in" ); ?>" type="text" value="<?php echo esc_attr( $instance["author__in"] ); ?>" style="width:100%;"/>
					<small><?php _e('Specify Author to retrieve, use author id, separated by comma(,)','gazeta')?></small>
				</p>				
				<p>
					<label for="<?php echo $this->get_field_id( "post_format" ); ?>"><?php _e( 'Post Format','gazeta' ); ?></label>
					<input id="<?php echo $this->get_field_id( "post_format" ); ?>" name="<?php echo $this->get_field_name( "post_format" ); ?>" type="text" value="<?php echo esc_attr( $instance["post_format"] ); ?>" style="width:100%;"/>
					<small><?php _e('Specify Post Format to retrieve (post-format-standard, post-format-audio, post-format-gallery,post-format-image,post-format-video), leave blank for all.','gazeta')?></small>
				</p>
				<p>
					<label for="<?php echo $this->get_field_id( "post_tags" ); ?>"><?php _e( 'Post Tags','gazeta' ); ?></label>
					<input id="<?php echo $this->get_field_id( "post_tags" ); ?>" name="<?php echo $this->get_field_name( "post_tags" ); ?>" type="text" value="<?php echo esc_attr( $instance["post_tags"] ); ?>" style="width:100%;"/>
					<small><?php printf( __('Specify Post Tags to retrieve, use %s, separated by comma(,).','gazeta'), '<a href="'.admin_url('edit-tags.php?taxonomy=post_tag').'">'.__('Tag Slugs','gazeta').'</a>' );?></small>
				</p>
				<p>
					<label for="<?php echo $this->get_field_id( "post_categories" ); ?>"><?php _e( 'Post Categories','gazeta' ); ?></label>
					<input id="<?php echo $this->get_field_id( "post_categories" ); ?>" name="<?php echo $this->get_field_name( "post_categories" ); ?>" type="text" value="<?php echo esc_attr( $instance["post_categories"] ); ?>" style="width:100%;"/>
					<small><?php printf( __('Specify Post Categories to retrieve, use %s, separated by comma(,).','gazeta'), '<a href="'.admin_url('edit-tags.php?taxonomy=category').'">'.__('Category IDs','gazeta').'</a>' );?></small>
				</p>				
				<p>
					<label for="<?php echo esc_attr( $this->get_field_id( 'ignore_sticky_posts' ));?>"><?php _e('Ignore Sticky Posts:', 'gazeta');?></label>
					<input <?php checked( 'on' , esc_attr( $instance['ignore_sticky_posts'] ), true);?> type="checkbox" id="<?php echo esc_attr( $this->get_field_id( 'ignore_sticky_posts' ));?>" name="<?php echo esc_attr( $this->get_field_name( 'ignore_sticky_posts' ) );?>"/>
				</p>
				<?php if( function_exists( 'gazeta_post_orderby' ) ):?>
					<p>  
					    <label for="<?php echo esc_attr( $this->get_field_id( 'orderby' ) ); ?>"><?php _e('Order by:', 'gazeta'); ?></label>
					    <select style="width:100%;" id="<?php echo esc_attr( $this->get_field_id( 'orderby' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'orderby' ) ); ?>">
					    	<?php 
					    		foreach ( gazeta_post_orderby() as $key=>$value ){
					    			?>
					    				<option <?php selected( esc_attr( $instance['orderby'] ) , esc_attr( $key ), true);?> value="<?php print esc_attr( $key );?>"><?php print esc_attr( $value );?></option>
					    			<?php 
					    		}
					    	?>
					    </select>  
					</p>
				<?php endif;?>
				<?php if( function_exists( 'gazeta_post_order' ) ):?>
					<p>  
					    <label for="<?php echo esc_attr( $this->get_field_id( 'order' ) ); ?>"><?php _e('Order:', 'gazeta'); ?></label>
					    <select style="width:100%;" id="<?php echo esc_attr( $this->get_field_id( 'order' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'order' ) ); ?>">
					    	<?php 
					    		foreach ( gazeta_post_order() as $key=>$value ){
					    			?>
					    				<option <?php selected( esc_attr( $instance['order'] ) , esc_attr( $key ), true);?> value="<?php print esc_attr( $key );?>"><?php print esc_attr( $value );?></option>
					    			<?php 
					    		}
					    	?>
					    </select>  
					</p>
				<?php endif;?>				
				<p>  
				    <label for="<?php echo esc_attr( $this->get_field_id( 'posts_per_page' ) ); ?>"><?php _e('Show Posts:', 'gazeta'); ?></label>
				    <input id="<?php echo esc_attr( $this->get_field_id( 'posts_per_page' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'posts_per_page' ) ); ?>" value="<?php echo esc_attr( $instance['posts_per_page'] ); ?>" style="width:100%;" />
				</p>
				<p>
					<?php printf( __('Need more filters? check out %s filter hook.','gazeta'), '<code>gazeta_aside_posts_widget/args</code>' );?>
				</p>
			<?php 
		}
		function layout(){
			return array(
				'classic'	=>	__('Classic','gazeta'),
				'modern'	=>	__('Modern','gazeta')
			);
		}		
	}
}

if( !class_exists( 'Gazeta_Twitters' ) ){
	/**
	 * @author ADMIN
	 *
	 */
	class Gazeta_Twitters extends WP_Widget{
		function Gazeta_Twitters() {
			parent::__construct(
				'gazeta-twitters', // Base ID
				__('Gazeta Twitters', 'gazeta'), // Name
				array( 'classname'=>'twitter-feeds' ,'description' => __('Display the Twitter Feeds', 'gazeta')) // Args
			);
		}
		function widget($args, $instance){
			$title 				= !empty( $instance['title'] ) ? apply_filters('widget_title', esc_attr( $instance['title'] ) ) : null;
			$expiration 		= !empty( $instance['expiration'] ) ? esc_attr( $instance['expiration'] ) : 300;
			$layout 			= !empty( $instance['layout'] ) ? esc_attr( $instance['layout'] ) : 'list';
			$thumbnail 			= !empty( $instance['thumbnail'] ) ? esc_attr( $instance['thumbnail'] ) : null;
			$username 			= !empty( $instance['username'] ) ? esc_attr( $instance['username'] ) : null;
			$consumerkey 		= !empty( $instance['consumerkey'] ) ? esc_attr( $instance['consumerkey'] ) : null;
			$consumersecret 	= !empty( $instance['consumersecret'] ) ? esc_attr( $instance['consumersecret'] ) : null;
			$accesstoken 		= !empty( $instance['accesstoken'] ) ? esc_attr( $instance['accesstoken'] ) : null;
			$accesstokensecret 	= !empty( $instance['accesstokensecret'] ) ? esc_attr( $instance['accesstokensecret'] ) : null;
			$shows 				= !empty( $instance['shows'] ) ? absint( $instance['shows'] ) : 5;
			echo $args['before_widget'];
			if ( $title ) {
				echo $args['before_title'] . $title . $args['after_title'];
			}
			if( !class_exists('TwitterOAuth') )
				return;
			if( !function_exists( 'getConnectionWithAccessToken' ) )
				return;
			if(false === ( $twitterData = get_transient( $this->id ) ) ){
				$connection = getConnectionWithAccessToken($consumerkey, $consumersecret, $accesstoken, $accesstokensecret);
				$tweets = $connection->get("https://api.twitter.com/1.1/statuses/user_timeline.json?screen_name=".$username."&count=".$shows);
				$twitterData = $tweets;
				set_transient( $this->id , $twitterData, apply_filters( 'transient_expiration' , $expiration));
			}

			if( empty( $twitterData->errors[0]->message ) ):
				if( $layout == 'list' ):
					?>
						<div class="twitter-list">
							<ul>
								<?php foreach ( $twitterData as $tweet ):?>
									<li>
										<?php if( $thumbnail == 'on' ):?>
											<a class="twitter-profile-link" href="<?php print gazeta_get_twitter_profile_link( $username );?>"><img src="<?php print apply_filters( 'gazeta_twitters/profile_image_url' , gazeta_get_twitter_bigger_avatar($tweet->user->profile_image_url), $tweet);?>" alt="<?php print apply_filters( 'gazeta_twitters/name' , esc_attr( $tweet->user->name ), $tweet);?>"/></a>
										<?php endif;?>
										<div class="twitter-inner <?php if(!$thumbnail):?>no-thumbnail-image<?php endif;?>">
											<span class="twitter-username"><?php print apply_filters( 'gazeta_twitters/name' , esc_attr( $tweet->user->name ));?></span>
											<p><?php print gazeta_find_twitter_user( gazeta_convert_string_to_link($tweet->text) );?></p>
											<p><?php print( apply_filters( 'gazeta_twitters/time' , sprintf(__('%s ago','gazeta'), human_time_diff( gazeta_conver_twitter_time_to_timeago($tweet->created_at), current_time('timestamp') )), $tweet));?></p>
										</div>
									</li>
								<?php endforeach;?>
							</ul>
						</div>
					<?php 
				else:
					$is_rtl = ( is_rtl() ) ? 'yes' : 'no';
					?>
					<div data-rtl="<?php print $is_rtl;?>" id="<?php print $this->id?>" class="tweet-feed">
						<?php foreach ( $twitterData as $tweet ):?>
							<div class="tf-info">
								<?php if($thumbnail):?>
									<a class="twitter-profile-link" href="<?php print gazeta_get_twitter_profile_link( $username );?>"><img src="<?php print apply_filters( 'gazeta_twitters/profile_image_url' , gazeta_get_twitter_bigger_avatar($tweet->user->profile_image_url), $tweet);?>" alt="<?php print apply_filters( 'gazeta_twitters/name' , esc_attr( $tweet->user->name ), $tweet);?>"/></a>
								<?php endif;?>
								<p><?php print gazeta_find_twitter_user( gazeta_convert_string_to_link($tweet->text) );?></p>
							</div>						
						<?php endforeach;?>
					</div>
					<?php 
				endif;
			else:
				print isset( $tweets->errors[0]->message ) ? $tweets->errors[0]->message . '!' : __('Getting feeds error.','gazeta');
			endif;
			echo $args['after_widget'];
		}
		function update( $new_instance, $old_instance ) {
			delete_transient( $this->id );
			$instance 						= $old_instance;
			$instance['title'] 				= esc_attr( $new_instance['title'] );
			$instance['expiration'] 		= esc_attr( $new_instance['expiration'] );
			$instance['layout'] 			= esc_attr( $new_instance['layout'] );
			$instance['thumbnail'] 			= esc_attr( $new_instance['thumbnail'] );
			$instance['username']			= esc_attr( $new_instance['username'] );
			$instance['consumerkey'] 		= esc_attr( $new_instance['consumerkey'] );
			$instance['consumersecret'] 	= esc_attr( $new_instance['consumersecret'] );
			$instance['accesstoken'] 		= esc_attr( $new_instance['accesstoken'] );
			$instance['accesstokensecret'] 	= esc_attr( $new_instance['accesstokensecret'] );
			$instance['shows']				= esc_attr( $new_instance['shows'] );			
			return $instance;
		}
		function form( $instance ){
			$defaults = array(
				'title' 			=> __('Twitter Feeds', 'gazeta'),
				'expiration'		=>	300,
				'layout'			=>	'list',
				'thumbnail'			=>	'on',
				'username'			=>	'',
				'consumerkey'		=>	'',
				'consumersecret'	=>	'',
				'accesstoken'		=>	'',
				'accesstokensecret'	=>	'',
				'shows'				=>	'5'	
			);
			$instance = wp_parse_args( (array) $instance, $defaults );			
			?>
				<p>
					<label for="<?php echo esc_attr( $this->get_field_id( 'title' ));?>"><?php _e('Title:', 'gazeta');?></label>
					<input id="<?php echo esc_attr( $this->get_field_id( 'title' ));?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) );?>" value="<?php echo esc_attr( $instance['title'] );?>" style="width:100%;" />
				</p>
				<p>
					<label for="<?php echo $this->get_field_id( "expiration" ); ?>"><?php _e( 'Caching Expiration','gazeta' ); ?></label>
					<input id="<?php echo $this->get_field_id( "expiration" ); ?>" name="<?php echo $this->get_field_name( "expiration" ); ?>" type="text" value="<?php echo esc_attr( $instance["expiration"] ); ?>" style="width:100%;"/>
					<small><?php _e('Time until expiration in seconds from now, or 0 for never expires. Ex: For one day, the expiration value would be: (60 * 60 * 24).','gazeta')?></small>
				</p>				
				<p>  
				    <label for="<?php echo esc_attr( $this->get_field_id( 'layout' ) ); ?>"><?php _e('Layout:', 'gazeta'); ?></label>
				    <select style="width:100%;" id="<?php echo esc_attr( $this->get_field_id( 'layout' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'layout' ) ); ?>">
				    	<option><?php _e('Choose one ...','gazeta');?></option>
				    	<?php 
				    		foreach ( $this->layout() as $key=>$value ){
				    			?>
				    				<option <?php selected( esc_attr( $instance['layout'] ) , esc_attr( $key ), true);?> value="<?php print esc_attr( $key );?>"><?php print esc_attr( $value );?></option>
				    			<?php 
				    		}
				    	?>
				    </select> 
				    <small><?php _e('Specify Layout to display.','gazeta');?></small> 
				</p>			
				<p>
					<label for="<?php echo esc_attr( $this->get_field_id( 'thumbnail' ));?>"><?php _e('Show Thumbnail:', 'gazeta');?></label>
					<input type="checkbox" <?php checked( 'on' , esc_attr( $instance['thumbnail'] ), true);?> id="<?php echo esc_attr( $this->get_field_id( 'thumbnail' ));?>" name="<?php echo esc_attr( $this->get_field_name( 'thumbnail' ) );?>"/>
				</p>
				<p>
					<label for="<?php echo $this->get_field_id( 'username' ); ?>"><?php _e('Twitter Username:', 'gazeta'); ?></label>
					<input id="<?php echo $this->get_field_id( 'username' ); ?>" name="<?php echo $this->get_field_name( 'username' ); ?>" value="<?php echo $instance['username'];?>" style="width:100%;" />
				</p>
				<p>
					<label for="<?php echo $this->get_field_id( 'consumerkey' ); ?>"><?php _e('Consumer Key:', 'gazeta'); ?></label>
					<input id="<?php echo $this->get_field_id( 'consumerkey' ); ?>" name="<?php echo $this->get_field_name( 'consumerkey' ); ?>" value="<?php echo $instance['consumerkey'];?>" style="width:100%;" />
				</p>
				<p>
					<label for="<?php echo $this->get_field_id( 'consumersecret' ); ?>"><?php _e('Consumer Secret:', 'gazeta'); ?></label>
					<input id="<?php echo $this->get_field_id( 'consumersecret' ); ?>" name="<?php echo $this->get_field_name( 'consumersecret' ); ?>" value="<?php echo $instance['consumersecret'];?>" style="width:100%;" />
				</p>
				<p>
					<label for="<?php echo $this->get_field_id( 'accesstoken' ); ?>"><?php _e('Access Token:', 'gazeta'); ?></label>
					<input id="<?php echo $this->get_field_id( 'accesstoken' ); ?>" name="<?php echo $this->get_field_name( 'accesstoken' ); ?>" value="<?php echo $instance['accesstoken'];?>" style="width:100%;" />
				</p>
				<p>
					<label for="<?php echo $this->get_field_id( 'accesstokensecret' ); ?>"><?php _e('Access Token Secret:', 'gazeta'); ?></label>
					<input id="<?php echo $this->get_field_id( 'accesstokensecret' ); ?>" name="<?php echo $this->get_field_name( 'accesstokensecret' ); ?>" value="<?php echo $instance['accesstokensecret'];?>" style="width:100%;" />
				</p>
				<p>
					<label for="<?php echo $this->get_field_id( 'shows' ); ?>"><?php _e('How many tweets will be shown?:', 'gazeta'); ?></label>
					<input id="<?php echo $this->get_field_id( 'shows' ); ?>" name="<?php echo $this->get_field_name( 'shows' ); ?>" value="<?php echo $instance['shows'];?>" style="width:100%;" />
				</p>
			<?php 			
		}
		function layout(){
			return array(
				'list'	=>	__('List','gazeta'),
				'block'	=>	__('Block','gazeta')	
			);
		}
	}
}
if( !class_exists( 'Gazeta_MegaMenu_Posts' ) ){
	/**
	 * Related Posts Widget.
	 * @author ADMIN
	 *
	 */
	class Gazeta_MegaMenu_Posts extends WP_Widget{
		function Gazeta_MegaMenu_Posts() {
			parent::__construct(
				'gazeta-advanced-megamenu-posts', // Base ID
				__('MegaMenu Posts', 'gazeta'), // Name
				array( 'classname'=>'megamenu-posts' ,'description' => __('Display the Posts on Mega Menu', 'gazeta')) // Args
			);
		}
		function widget($args, $instance){
			wp_reset_postdata();
			$current_post_tags = $current_post_categories = null;
			$current_post = get_the_ID();
			$title 				= !empty( $instance['title'] ) ? apply_filters('widget_title', esc_attr( $instance['title'] ) ) : null;
			$expiration 		= isset( $instance['expiration'] ) ? esc_attr( $instance['expiration'] ) : '300';
			$thumbnail_size 	= isset( $instance['thumbnail_size'] ) ? esc_attr( $instance['thumbnail_size'] ) : 'medium';
			$layout 			= isset( $instance['layout'] ) ? esc_attr( $instance['layout'] ) : '';
			$post_format 		= isset( $instance['post_format'] ) ? esc_attr( $instance['post_format'] ) : '';
			$post_tags 			= isset( $instance['post_tags'] ) ? esc_attr( $instance['post_tags'] ) : '';
			$post_categories 	= isset( $instance['post_categories'] ) ? esc_attr( $instance['post_categories'] ) : '';
			$sticky 			= isset( $instance['ignore_sticky_posts'] ) ? esc_attr( $instance['ignore_sticky_posts'] ) : 'on';
			$orderby 			= isset( $instance['orderby'] ) ? esc_attr( $instance['orderby'] ) : 'ID';
			$order 				= isset( $instance['order'] ) ? esc_attr( $instance['order'] ) : 'DESC';
			$posts_per_page 			= isset( $instance['posts_per_page'] ) ? absint( $instance['posts_per_page'] ) : 5;

			$post_data = array(
				'no_found_rows'	=>		true,
				'post_type'		=>		'post',
				'post_status'	=>		'publish',
				'posts_per_page'		=>		$posts_per_page,
				'orderby'		=>		( $orderby == 'view' ) ? 'meta_value_num' : $orderby,
				'order'			=>		$order,
				'post__not_in'	=>		array( $current_post ) // Do not include the current post.
			);
			// order by views
			if( $orderby == 'view' ){
				$post_data['meta_key']	=	GAZETA_POST_VIEWS_FIELD_NAME;
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
			// Post Categories
			if( !empty( $post_categories ) ){
				$post_categories = explode(",", $post_categories);
				if( is_array( $post_categories ) && !empty( $post_categories ) ){
					$post_data['category__in']	=	$post_categories;
				}
			}
			// $sticky
			if( $sticky == 'on' ){
				$post_data['ignore_sticky_posts']	=	true;
			}

			$post_data = apply_filters( 'gazeta_mega_posts/args' , $post_data, $this->id);
				
			$post_query = wp_cache_get( $this->id );
			if( $post_query === false ){
				$post_query = new WP_Query( $post_data );
				wp_cache_set( $this->id , $post_query, '', 300);
			}
				
			if( $post_query->have_posts() ):
			echo $args['before_widget'];
			if ( $title ) {
				echo $args['before_title'] . $title . $args['after_title'];
			}

			?>
				<ul class="mega-menu-posts mega-menu-posts-<?php print $post_query->post_count?>-columns <?php print $this->id?>" id="<?php print $this->id?>">
					<?php 
						while ( $post_query->have_posts() ):
							$post_query->the_post();
							?>
								<li class="col-xs-6">
									<a href="<?php the_permalink();?>">
										<div class="news-feed">
											<?php 
												if( has_post_thumbnail( get_the_ID() ) ){
													print get_the_post_thumbnail( get_the_ID(), apply_filters( 'gazeta_mega_posts/thumbnail_size' , $thumbnail_size) , array( 'class'=>'img-responsive' ) );
												}
												else{
													the_excerpt();
												}
											?>
											<h4 class="post-title"><?php the_title();?></h4>
											<p class="date-time"><?php printf( __('Posted on: %s','gazeta'), apply_filters( 'gazeta_mega_posts/time_format' , get_the_date()) );?></p>
										</div>
									</a>
								</li>
							<?php 
						endwhile;
					?>
				</ul>
			<?php 
			
			echo $args['after_widget'];
			endif;
			wp_reset_postdata();
		}
		function update( $new_instance, $old_instance ) {
			$instance 						= $old_instance;
			$instance['title'] 				= esc_attr( $new_instance['title'] );
			$instance['expiration'] 		= esc_attr( $new_instance['expiration'] );
			$instance['thumbnail_size'] 	= esc_attr( $new_instance['thumbnail_size'] );
			$instance['layout'] 			= esc_attr( $new_instance['layout'] );
			$instance['post_format'] 		= esc_attr( $new_instance['post_format'] );
			$instance['post_tags'] 			= esc_attr( $new_instance['post_tags'] );
			$instance['post_categories'] 	= esc_attr( $new_instance['post_categories'] );
			$instance['ignore_sticky_posts']=	esc_attr( $new_instance['ignore_sticky_posts'] );
			$instance['orderby']			=	esc_attr( $new_instance['orderby'] );
			$instance['order']				=	esc_attr( $new_instance['order'] );
			$instance['posts_per_page']			=	absint( $new_instance['posts_per_page'] );
			return $instance;
		}
		function form( $instance ){
			$defaults = array(
				'title' 			=> __('Recent Posts', 'gazeta'),
				'expiration'		=>	300,
				'thumbnail_size'	=>	'image-370-243',
				'layout'			=>	'classic',
				'post_format'		=>	'',
				'post_tags'			=>	'',
				'post_categories'	=>	'',
				'ignore_sticky_posts'		=>	'on',
				'category'			=>	'',
				'orderby'			=>	'ID',
				'order'				=>	'DESC',
				'posts_per_page'			=>	5,
			);
			$instance = wp_parse_args( (array) $instance, $defaults );			
			?>
				<p>
					<label for="<?php echo esc_attr( $this->get_field_id( 'title' ));?>"><?php _e('Title:', 'gazeta');?></label>
					<input id="<?php echo esc_attr( $this->get_field_id( 'title' ));?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) );?>" value="<?php echo esc_attr( $instance['title'] );?>" style="width:100%;" />
				</p>
				<p>
					<label for="<?php echo $this->get_field_id( "expiration" ); ?>"><?php _e( 'Caching Expiration','gazeta' ); ?></label>
					<input id="<?php echo $this->get_field_id( "expiration" ); ?>" name="<?php echo $this->get_field_name( "expiration" ); ?>" type="text" value="<?php echo esc_attr( $instance["expiration"] ); ?>" style="width:100%;"/>
					<small><?php _e('Time until expiration in seconds from now, or 0 for never expires. Ex: For one day, the expiration value would be: (60 * 60 * 24).','gazeta')?></small>
				</p>				
				<p>
					<label for="<?php echo esc_attr( $this->get_field_id( 'thumbnail_size' ));?>"><?php _e('Thumbnail Size:', 'gazeta');?></label>
					<input id="<?php echo esc_attr( $this->get_field_id( 'thumbnail_size' ));?>" name="<?php echo esc_attr( $this->get_field_name( 'thumbnail_size' ) );?>" value="<?php echo esc_attr( $instance['thumbnail_size'] );?>" style="width:100%;" />
					<small><?php printf(__( 'Enter image size. Example: "thumbnail", "medium", "large", "full" or other sizes defined by current theme size: <strong>%s</strong>".', 'gazeta' ), implode(", ", gazeta_get_thumbnail_image_sizes()) );?></small>
				</p>
				<p>
					<label for="<?php echo $this->get_field_id( "post_format" ); ?>"><?php _e( 'Post Format','gazeta' ); ?></label>
					<input id="<?php echo $this->get_field_id( "post_format" ); ?>" name="<?php echo $this->get_field_name( "post_format" ); ?>" type="text" value="<?php echo esc_attr( $instance["post_format"] ); ?>" style="width:100%;"/>
					<small><?php _e('Specify Post Format to retrieve (post-format-standard, post-format-audio, post-format-gallery,post-format-image,post-format-video), leave blank for all.','gazeta')?></small>
				</p>
				<p>
					<label for="<?php echo $this->get_field_id( "post_tags" ); ?>"><?php _e( 'Post Tags','gazeta' ); ?></label>
					<input id="<?php echo $this->get_field_id( "post_tags" ); ?>" name="<?php echo $this->get_field_name( "post_tags" ); ?>" type="text" value="<?php echo esc_attr( $instance["post_tags"] ); ?>" style="width:100%;"/>
					<small><?php printf( __('Specify Post Tags to retrieve, use %s, separated by comma(,).','gazeta'), '<a href="'.admin_url('edit-tags.php?taxonomy=post_tag').'">'.__('Tag Slugs','gazeta').'</a>' );?></small>
				</p>
				<p>
					<label for="<?php echo $this->get_field_id( "post_categories" ); ?>"><?php _e( 'Post Categories','gazeta' ); ?></label>
					<input id="<?php echo $this->get_field_id( "post_categories" ); ?>" name="<?php echo $this->get_field_name( "post_categories" ); ?>" type="text" value="<?php echo esc_attr( $instance["post_categories"] ); ?>" style="width:100%;"/>
					<small><?php printf( __('Specify Post Categories to retrieve, use %s, separated by comma(,).','gazeta'), '<a href="'.admin_url('edit-tags.php?taxonomy=category').'">'.__('Category IDs','gazeta').'</a>' );?></small>
				</p>				
				<p>
					<label for="<?php echo esc_attr( $this->get_field_id( 'ignore_sticky_posts' ));?>"><?php _e('Ignore Sticky Posts:', 'gazeta');?></label>
					<input <?php checked( 'on' , esc_attr( $instance['ignore_sticky_posts'] ), true);?> type="checkbox" id="<?php echo esc_attr( $this->get_field_id( 'ignore_sticky_posts' ));?>" name="<?php echo esc_attr( $this->get_field_name( 'ignore_sticky_posts' ) );?>"/>
				</p>
				<?php if( function_exists( 'gazeta_post_orderby' ) ):?>
					<p>  
					    <label for="<?php echo esc_attr( $this->get_field_id( 'orderby' ) ); ?>"><?php _e('Order by:', 'gazeta'); ?></label>
					    <select style="width:100%;" id="<?php echo esc_attr( $this->get_field_id( 'orderby' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'orderby' ) ); ?>">
					    	<?php 
					    		foreach ( gazeta_post_orderby() as $key=>$value ){
					    			?>
					    				<option <?php selected( esc_attr( $instance['orderby'] ) , esc_attr( $key ), true);?> value="<?php print esc_attr( $key );?>"><?php print esc_attr( $value );?></option>
					    			<?php 
					    		}
					    	?>
					    </select>  
					</p>
				<?php endif;?>
				<?php if( function_exists( 'gazeta_post_order' ) ):?>
					<p>  
					    <label for="<?php echo esc_attr( $this->get_field_id( 'order' ) ); ?>"><?php _e('Order:', 'gazeta'); ?></label>
					    <select style="width:100%;" id="<?php echo esc_attr( $this->get_field_id( 'order' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'order' ) ); ?>">
					    	<?php 
					    		foreach ( gazeta_post_order() as $key=>$value ){
					    			?>
					    				<option <?php selected( esc_attr( $instance['order'] ) , esc_attr( $key ), true);?> value="<?php print esc_attr( $key );?>"><?php print esc_attr( $value );?></option>
					    			<?php 
					    		}
					    	?>
					    </select>  
					</p>
				<?php endif;?>				
				<p>  
				    <label for="<?php echo esc_attr( $this->get_field_id( 'posts_per_page' ) ); ?>"><?php _e('Show Posts:', 'gazeta'); ?></label>
				    <input id="<?php echo esc_attr( $this->get_field_id( 'posts_per_page' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'posts_per_page' ) ); ?>" value="<?php echo esc_attr( $instance['posts_per_page'] ); ?>" style="width:100%;" />
				</p>
				<p>
					<?php printf( __('Need more filters? check out %s filter hook.','gazeta'), '<code>gazeta_mega_posts/args</code>' );?>
				</p>
			<?php 
		}
	}
}