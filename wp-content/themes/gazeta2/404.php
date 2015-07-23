<?php get_header();?>
	<div class="main-content container">
		<div class="col-md-<?php print is_active_sidebar( 'primary-sidebar' ) ? 8 : 12;?>">
			<h3 class="post-title"><?php _e('Oops! Page Not Found.','gazeta');?></h3>
			<p><?php _e('It looks like nothing was found at this location.','gazeta');?></p>
		</div>
		<?php get_sidebar();?>
	</div>
<?php get_footer();?>
