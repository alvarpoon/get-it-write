<div id="post-<?php the_ID()?>" <?php post_class();?>>
	<?php 
		if( gazeta_get_post_gallery_content( get_the_ID() ) ){
			print gazeta_get_post_gallery_content( get_the_ID(), 'large' );
		}
	?>
	<?php if( !is_singular()):?>
		<div class="entry-title">
			<h3><a href="<?php the_permalink()?>"><?php the_title();?></a></h3>
		</div>
	<?php endif;?>
	<div class="entry-content">
		<?php the_content( __( 'Continue Reading <em>&#8594;</em>', 'gazeta' ) );?>
	</div>
</div>