<?php
/**
 * Timber starter-theme
 * https://github.com/timber/starter-theme
 *
 * @package  WordPress
 * @subpackage  Timber
 * @since   Timber 0.1
 */

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