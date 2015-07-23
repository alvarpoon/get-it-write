	<?php if( is_active_sidebar( 'bigbanner-sidebar' )  ):?>
		<?php dynamic_sidebar( 'bigbanner-sidebar' )?>
	<?php endif;?>
	<!-- Footer -->
	<?php if( is_active_sidebar( 'footer-sidebar' ) ):?>
		<footer class="container">
			<?php dynamic_sidebar( 'footer-sidebar' );?>
		</footer>
	<?php endif;?>
	<div class="footer-bottom">
		<div class="container">
			<?php do_action( 'gazeta_credits' );?>
			<?php if( has_nav_menu('footer_navigation') ):?>
		    	<?php 
		    		wp_nav_menu( apply_filters( 'gazeta_nav_footer_menu_args' , array(
		    			'menu_class'		=>	'footer-links',
		    			'theme_location'	=>	'footer_navigation',
		    		)) );
		    	?>				
			<?php endif;?>
		</div>
	</div>
</div>
<div class="clearfix space30"></div>
	<?php wp_footer();?>
</body>
</html>