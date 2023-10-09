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


 /**
 * Initialize Timber.
 */
$composer_autoload = __DIR__ . '/vendor/autoload.php';
if ( file_exists( $composer_autoload ) ) {
	require_once $composer_autoload;
	$timber = new Timber\Timber();
}

include('inc/timber.php');
include('inc/blocks.php');
include('inc/editor.php');
include('inc/menus.php');
include('inc/overrides.php');
include('inc/validation.php');
include('inc/breadcrumbs.php');
include('inc/search.php');
include('inc/post-types/events.php');
include('inc/post-types/profiles.php');


// Won't work as an include
require 'plugin-update-checker/plugin-update-checker.php';
$myUpdateChecker = Puc_v4_Factory::buildUpdateChecker(
	'https://github.com/idfive/Iowa_test',
	__FILE__,
	'iowa-state'
);

//Set the branch that contains the stable release.
$myUpdateChecker->setBranch('');

//Optional: If you're using a private repository, specify the access token like this:
$myUpdateChecker->setAuthentication('your-token-here');
