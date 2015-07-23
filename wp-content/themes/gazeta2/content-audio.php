<div id="post-<?php the_ID()?>" <?php post_class();?>>
	<?php print function_exists( 'gazeta_get_embed_code_post_format' ) ? gazeta_get_embed_code_post_format( get_the_ID() ) : '';?>
	<?php if( !is_singular()):?>
		<div class="entry-title">
			<h3><a href="<?php the_permalink()?>"><?php the_title();?></a></h3>
		</div>
	<?php endif;?>
	<div class="entry-content">
		<?php the_content( __( 'Continue Reading <em>&#8594;</em>', 'gazeta' ) );?>
	</div>	
</div>