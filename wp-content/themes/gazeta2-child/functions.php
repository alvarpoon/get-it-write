<?php
if( !defined( 'ABSPATH' ) )
	exit;
// write your function here.
add_filter( 'widget_tag_cloud_args' , function( $args ){
	$args['largest'] = 15;
	$args['number'] = 10;
	return $args;
});