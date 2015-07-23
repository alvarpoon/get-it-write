<?php 
if ( post_password_required() ) {
	return;
}
?>
<?php if ( have_comments()):?>
<div class="news-comment">
	<h5 class="comments_number"><?php comments_number( '', __('1 comment','gazeta') , __('% comments','gazeta') ); ?></h5>
	<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : ?>
		<nav id="comment-nav-above" class="navigation comment-navigation" role="navigation">
			<h1 class="screen-reader-text"><?php _e( 'Comment navigation', 'gazeta' ); ?></h1>
			<div class="nav-previous"><?php previous_comments_link( __( '&larr; Older Comments', 'gazeta' ) ); ?></div>
			<div class="nav-next"><?php next_comments_link( __( 'Newer Comments &rarr;', 'gazeta' ) ); ?></div>
		</nav><!-- #comment-nav-above -->
	<?php endif; // Check for comment navigation. ?>		
	<ul class="comment-list">
		<?php
			$comments_args = array(
				'style'      => 'ul',
				'short_ping' => true,
				'avatar_size'=> 80,
				'reply_text'	=>	__('Reply','gazeta'),
				'callback'		=>	function_exists( 'gazeta_comments_template' ) ? 'gazeta_comments_template' : null
			);
			wp_list_comments( apply_filters( 'gazeta_list_comments_args' , $comments_args) );
		?>
	</ul>
	<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : ?>
		<nav id="comment-nav-above" class="navigation comment-navigation" role="navigation">
			<h1 class="screen-reader-text"><?php _e( 'Comment navigation', 'gazeta' ); ?></h1>
			<div class="nav-previous"><?php previous_comments_link( __( '&larr; Older Comments', 'gazeta' ) ); ?></div>
			<div class="nav-next"><?php next_comments_link( __( 'Newer Comments &rarr;', 'gazeta' ) ); ?></div>
		</nav><!-- #comment-nav-above -->
	<?php endif; // Check for comment navigation. ?>	
</div>
<?php endif;?>
<div class="n-commentform">
	<?php 
	$required_text = '';
	$req = get_option( 'require_name_email' );
	$aria_req = ( $req ? " aria-required='true'" : '' );
	$args = array(
		'id_form'           => 'comments',
		'id_submit'         => 'submit',
		'title_reply'       => '',
		'title_reply_to'    => __( 'Write the message to %s','gazeta' ),
		'cancel_reply_link' => __( 'Cancel Reply', 'gazeta' ),
		'label_submit'      => __( 'Post Comment','gazeta' ),
	
		'comment_field' =>  '<div class="ncf-textarea"><textarea id="comment" name="comment" aria-required="true" placeholder="'.__('Message','gazeta').'"></textarea></div>',
			
		'must_log_in' => '<p class="must-log-in">' .
		sprintf(
				__( 'You must be <a href="%s">logged in</a> to post a comment.', 'gazeta'  ),
				wp_login_url( apply_filters( 'the_permalink', get_permalink() ) )
		) . '</p>',
	
		'logged_in_as' => '<p class="logged-in-as">' .
		sprintf(
				__( 'Logged in as <a href="%1$s">%2$s</a>. <a href="%3$s" title="Log out of this account">Log out?</a>', 'gazeta'  ),
				admin_url( 'profile.php' ),
				$user_identity,
				wp_logout_url( apply_filters( 'the_permalink', get_permalink( ) ) )
		) . '</p>',
	
		'comment_notes_before' => '<p class="comment-notes">' .
		__( 'Your email address will not be published. Required fields are marked <span>*</span>', 'gazeta'  ) . ( $req ? $required_text : '' ) .
		'</p>',
		'comment_notes_after'	=>	'',
		'fields' => apply_filters( 'comment_form_default_fields', array(
				'author' => '<div class="ncf-ico"><input id="author" name="author" type="text" placeholder="'.__('Name','gazeta').'" value="' . esc_attr( $commenter['comment_author'] ) .'"><span><i class="fa fa-user"></i></span></div>',
				'email' => '<div class="ncf-ico"><input id="email" name="email" type="text" placeholder="'.__('Email','gazeta').'" value="'.esc_attr(  $commenter['comment_author_email'] ).'"><span><i class="fa fa-envelope-o"></i></span></div>',
				'url' => '<div class="ncf-ico"><input id="url" name="url" type="text" value="' . esc_attr( $commenter['comment_author_url'] ) .'"><span><i class="fa fa-link"></i></span></div>'
			)
		)
	);
	comment_form( $args );
	printf( '<p class="form-allowed-tags">' .
		sprintf(
				__( 'You may use these <abbr title="HyperText Markup Language">HTML</abbr> tags and attributes: %s', 'gazeta'  ),
				' <code>' . allowed_tags() . '</code>'
		) . '</p>' );
	?>
</div>