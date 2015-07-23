<?php
if( !defined('ABSPATH') ) exit; // Don't access me directly.
if( !function_exists( 'gazeta_metaboxes_custom_page_sidebar' ) ){
	/**
	 * Add the page custom sidebar metabox.
	 */
	function gazeta_metaboxes_custom_page_sidebar() {
		$screen = apply_filters( 'gazeta_metaboxes_custom_page_sidebar' , array( 'page', 'post', 'product' ));
		foreach ( $screen as $screen ) {
			add_meta_box( 'page-custom-sidebar' , __('Custom Sidebar','gazeta'), 'gazeta_metaboxes_custom_page_sidebar_callback', $screen, 'side', 'high' );
		}
	}
	add_action( 'add_meta_boxes', 'gazeta_metaboxes_custom_page_sidebar' );
}
if( !function_exists( 'gazeta_metaboxes_custom_page_sidebar_callback' ) ){
	/**
	 * Page custom sidebar callback
	 * @param object $post
	 */
	function gazeta_metaboxes_custom_page_sidebar_callback(  $post  ) {
		$output = '';
		wp_nonce_field( 'gazeta_metaboxes_custom_page_sidebar', 'gazeta_metaboxes_custom_page_sidebar_nonce' );
		global $wp_registered_sidebars;
		$value_sidebar = get_post_meta( $post->ID, 'custom_sidebar', true );
		if( is_array( $wp_registered_sidebars ) ){
			$output .= '<p><strong>'.__('Choose a sidebar','gazeta').'</strong></p>';
			$output .= '<select name="custom_sidebar" id="custom_sidebar" class="custom_sidebar" style="width:100%">';
				$output .= '<option value="">'.__('Default Sidebar','gazeta').'</option>';
				foreach ($wp_registered_sidebars as $sidebar=>$value) {
					$output .= '<option '.selected( $value_sidebar , $sidebar, false).' value="'.$sidebar.'">'. $value['name'] .'</option>';
				}
			$output .= '</select>';
		}
		print $output;
	}
}
if( !function_exists( 'gazeta_metaboxes_custom_page_sidebar_save' ) ){
	/**
	 * Save the custom sidebar
	 * @param int $post_id
	 */
	function gazeta_metaboxes_custom_page_sidebar_save( $post_id ) {
		// Check if our nonce is set.
		if ( ! isset( $_POST['gazeta_metaboxes_custom_page_sidebar_nonce'] ) ) {
			return;
		}
		// Verify that the nonce is valid.
		if ( ! wp_verify_nonce( $_POST['gazeta_metaboxes_custom_page_sidebar_nonce'], 'gazeta_metaboxes_custom_page_sidebar' ) ) {
			return;
		}
		// Check the user's permissions.
		if ( isset( $_POST['post_type'] ) && 'page' == $_POST['post_type'] ) {
			if ( ! current_user_can( 'edit_page', $post_id ) ) {
				return;
			}
		
		} 
		else{
			if ( ! current_user_can( 'edit_post', $post_id ) ) {
				return;
			}
		}
		/* OK, it's safe for us to save the data now. */
		// Make sure that it is set.
		if ( ! isset( $_POST['custom_sidebar'] ) ) {
			return;
		}
		// Sanitize user input.
		$my_data = sanitize_text_field( $_POST['custom_sidebar'] );
		
		// Update the meta field in the database.
		update_post_meta( $post_id, 'custom_sidebar', $my_data );		
	}
	add_action( 'save_post', 'gazeta_metaboxes_custom_page_sidebar_save' );
}