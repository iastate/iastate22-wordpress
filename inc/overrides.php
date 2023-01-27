<?php
function remove_body_classes( $wp_classes ) {
    $blacklist = array( 'home' );
    $wp_classes = array_diff( $wp_classes, $blacklist );
    return $wp_classes;
}
add_filter( 'body_class', 'remove_body_classes', 10, 2 );

/**
  * This protects the free version of ACF from white-screening the site.
 */
if (!function_exists('acf_register_block_type')){
	function acf_register_block_type($block){
		return false;
	}
}