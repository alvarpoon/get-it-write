<div id="post-<?php the_ID()?>" <?php post_class();?>>
	<?php if( has_post_thumbnail() ):?>
		<div id="bl-featured-<?php the_ID();?>" class="entry-featured-image">
			<div class="bl-featured-big">
				<a href="<?php the_permalink();?>">
					<?php 
						print get_the_post_thumbnail( get_the_ID() , apply_filters( 'gazeta_post_thumbnail_size' , 'large'), array( 'class'=> 'img-responsive' ));
					?>
				</a>
			</div>
		</div>
	<?php endif;?>
	<?php if( !is_singular()):?>
		<div class="entry-title">
			<h3><a href="<?php the_permalink()?>"><?php the_title();?></a></h3>
		</div>
	<?php endif;?>
	<div class="entry-content">
		<?php the_content( __( 'Continue Reading <em>&#8594;</em>', 'gazeta' ) );?>
	</div>
</div>