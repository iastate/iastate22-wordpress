<?php

/**
 * Timber starter-theme
 * https://github.com/timber/starter-theme
 *
 * @package  WordPress
 * @subpackage  Timber
 * @since   Timber 0.1
 */
if ( ! function_exists( 'acf_register_block_type' ) ) {
	function acf_requirement_admin_notice__error() {
		$class   = 'notice notice-error';
		$message = __( 'Please activate the Advanced Custom Fields PRO plugin' );
		$link    = __( 'wordpress.theme.iastate.edu/faq/' );

		printf( '<div class="%1$s"><p>%2$s</p><p><a href="https://%3$s">%3$s</a></p></div>', esc_attr( $class ), esc_html( $message ), esc_html( $link ) );
	}

	add_action( 'admin_notices', 'acf_requirement_admin_notice__error' );

	return;
}
