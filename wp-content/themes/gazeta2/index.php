<?php get_header();?>
	<div class="main-content container">
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
					get_template_part( 'content', 'none' );
				
				endif;
			?>
		</div>
		<?php get_sidebar();?>
	</div>
<?php get_footer();?>