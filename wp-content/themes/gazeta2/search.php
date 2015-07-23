<?php get_header();?>
	<div class="main-content container">
		<div class="col-md-12">
			<div class="page_header">
				<div class="col-md-12">
					<h1 class="entry-title"><?php printf( __('Search result for : <span>%s</span>','gazeta') , esc_attr( get_query_var( 's' ) ) );?></h1>
					<?php 
					/**
					 * Hooked gazeta_the_breadcrumb, 10
					 */
					do_action( 'gazeta_the_breadcrumb' );
					?>
				</div>
			</div>
		</div>		
		<div class="main-content-column col-md-<?php print is_active_sidebar( 'primary-sidebar' ) ? 8 : 12;?>">
			<?php 
				if( have_posts() ) :
					// Start the Loop.
					while ( have_posts() ) : the_post();
						/*
						 * Include the post format-specific template for the content. If you want to
						* use this in a child theme, then include a file called called content-___.php
						* (where ___ is the post format) and that will be used instead.
						*/			
						get_template_part( 'content', get_post_format() );
						
					endwhile;
					
					gazeta_the_posts_pagination(null, true);
					
				else:
					// If no content, include the "No posts found" template.
					?><p><?php _e('Sorry, but nothing matched your search terms. Please try again with some different keywords.','gazeta');?></p><?php 
				
				endif;
			?>
		</div>
		<?php get_sidebar();?>
	</div>
<?php get_footer();?>