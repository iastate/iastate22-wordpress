<?php
/**
 * The Template for displaying all single posts
 *
 * Methods for TimberHelper can be found in the /lib subdirectory
 *
 * @package  WordPress
 * @subpackage  Timber
 * @since    Timber 0.1
 */

use Timber\Timber;

$context         = Timber::context();
$timber_post     = Timber::query_post();
$context['post'] = $timber_post;
$eventsToggle    = $context["options"]["events_options"]["enabled"];

if ( post_password_required( $timber_post->ID ) ) {
	Timber::render( 'single-password.twig', $context );
} else {
	if ( $eventsToggle === false ) {
		Timber::render( '404.twig', $context );
	} else {
		Timber::render( array( 'single-events.twig' ), $context );
	}
}
