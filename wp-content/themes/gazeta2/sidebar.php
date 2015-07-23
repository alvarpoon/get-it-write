<?php 
global $post;
if( is_single() && get_post_type() == 'product' || (function_exists( 'is_shop' ) && is_shop()) || is_tax( 'product_cat' ) || is_tax( 'product_tag' ) ){
	$default_sidebar = 'woocommerce-sidebar';
}
else{
	$default_sidebar = 'primary-sidebar';
}
?>
<?php if( is_active_sidebar( apply_filters( 'gazeta_custom_sidebar' ,  $default_sidebar ) ) ):?>
	<!-- Sidebar -->
	<aside class="col-md-4 sidebar">
		<?php dynamic_sidebar( apply_filters( 'gazeta_custom_sidebar' ,  $default_sidebar ) );?>
	</aside>
<?php endif;?>