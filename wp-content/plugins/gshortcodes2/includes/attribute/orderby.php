<?php
if( !function_exists( 'gazeta_vc_orderby_attr' ) && function_exists( 'add_shortcode_param' ) ){
	/**
	 * Adding Orderby Attibute
	 * @param unknown_type $settings
	 * @param unknown_type $value
	 * @return string
	 */
	function gazeta_vc_orderby_attr( $settings, $value ) {
		$html = null;
		$orderby_array = function_exists( 'gazeta_post_orderby' ) ? gazeta_post_orderby() : '';
		$dependency = function_exists( 'vc_generate_dependencies_attributes' ) ? vc_generate_dependencies_attributes($settings) : '';
		$html .= '<div class="orderby_attr">';
			$html .= '<select name="'.$settings['param_name'].'" id="'.$settings['param_name'].'" class="wpb_vc_param_value wpb-textinput '.$settings['param_name'].' '.$settings['type'].'_field">';
				foreach ( $orderby_array  as $k=>$v) {
					$html .= '<option '.selected( $value, $k, false ).' value="'.$k.'">'.$v.'</option>';
				}
			$html .= '</select>';
		$html .= '</div>';
		return $html;
	}	
	add_shortcode_param( 'orderby' , 'gazeta_vc_orderby_attr');
}