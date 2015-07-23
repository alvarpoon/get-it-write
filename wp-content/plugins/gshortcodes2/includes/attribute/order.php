<?php
if( !function_exists( 'gazeta_vc_order_attr' ) && function_exists( 'add_shortcode_param' ) ){
	/**
	 * @param unknown_type $settings
	 * @param unknown_type $value
	 */	
	function gazeta_vc_order_attr( $settings, $value ) {
		$html = null;
		$order_array = function_exists( 'gazeta_post_order' ) ? gazeta_post_order() : '';
		$dependency = function_exists( 'vc_generate_dependencies_attributes' ) ? vc_generate_dependencies_attributes($settings) : '';
		$html .= '<div class="order_attr">';
			$html .= '<select name="'.$settings['param_name'].'" id="'.$settings['param_name'].'" class="wpb_vc_param_value wpb-textinput '.$settings['param_name'].' '.$settings['type'].'_field">';
				foreach ( $order_array  as $k=>$v) {
					$html .= '<option '.selected( $value, $k, false ).' value="'.$k.'">'.$v.'</option>';
				}
			$html .= '</select>';
		$html .= '</div>';
		return $html;
	}	
	add_shortcode_param( 'order' , 'gazeta_vc_order_attr');
}