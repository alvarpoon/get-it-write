<?php get_header();?>
	<div class="main-content container">
		<div class="col-md-12">
			<div class="page_header">
				<div class="col-md-12">
					<h1 class="no-uppercase entry-title"><?php the_archive_title();?></h1>
					<span class="archive_description"><?php the_archive_description();?></span>
					<?php 
					/**
					 * Hooked gazeta_the_breadcrumb, 10
					 */
					do_action( 'gazeta_the_breadcrumb' );
					?>
				</div>
			</div>
		</div>	
		<div class="main-content-column col-md-<?php print is_active_sidebar( apply_filters( 'gazeta_custom_sidebar' ,  'primary-sidebar' ) ) ? 8 : 12;?>">
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
