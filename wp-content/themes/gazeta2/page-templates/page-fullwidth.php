<?php 
/**
 * Template Name: Page Fullwidth
 */
?>
<?php get_header();?>
	<div class="main-content container">
		<div class="col-md-12">
			<div class="page_header">
				<div class="col-md-12">
					<h1 class="entry-title"><?php the_title();?></h1>
					<?php 
					/**
					 * Hooked gazeta_the_breadcrumb, 10
					 */
					do_action( 'gazeta_the_breadcrumb' );
					?>					
				</div>
			</div>
		</div>	
		<div class="col-md-12">
			<?php 
				if( have_posts() ) : the_post();
					/*
					 * Include the post format-specific template for the content. If you want to
					* use this in a child theme, then include a file called called content-___.php
					* (where ___ is the post format) and that will be used instead.
					*/			
					get_template_part( 'content', 'page' );
					if( comments_open() ){
						comments_template();
					}
				endif;
			?>
		</div>
	</div>
<?php get_footer();?>