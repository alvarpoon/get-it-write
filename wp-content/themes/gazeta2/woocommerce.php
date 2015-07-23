<?php get_header();?>
	<div class="main-content container">
		<div class="main-content-column col-md-<?php print is_active_sidebar( apply_filters( 'gazeta_custom_sidebar' ,  'woocommerce-sidebar' ) ) ? 8 : 12;?>">
			<?php 
				if( have_posts() ) :
					
					woocommerce_content();
					
				else:
				// If no content, include the "No posts found" template.
					get_template_part( 'content', 'none' );
				
				endif;
			?>
		</div>
		<?php get_sidebar();?>
	</div>
<?php get_footer();?>