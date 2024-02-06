<?php
function remove_body_classes( $wp_classes ) {
	$blacklist = array( 'home' );

	return array_diff( $wp_classes, $blacklist );
}

add_filter( 'body_class', 'remove_body_classes', 10, 2 );


/**
 * Look for $_GET variable set if a post type was toggled.
 * If criteria are met then flush rewrite rules.
 *
 * @wp-hook load-toplevel_page_acf-options should only load in the ACF theme option menu.
 *
 * @return void
 */
function acf_maybe_refresh_rewrite_rules_for_post_type_options() {
	if ( true === filter_input( INPUT_GET, 'acf_post_type_refresh', FILTER_VALIDATE_BOOLEAN ) ) {
		if ( false !== wp_verify_nonce( filter_input( INPUT_GET, 'refresh_nonce', FILTER_SANITIZE_STRING ), 'refresh_rewrite_after_post_type_change' ) ) {
			flush_rewrite_rules( false );
			if ( wp_safe_redirect( remove_query_arg( array(
				'acf_post_type_refresh',
				'refresh_nonce'
			) ) ) ) {
				exit();
			}
		}
	}
}

add_action( 'load-toplevel_page_acf-options', 'acf_maybe_refresh_rewrite_rules_for_post_type_options' );
