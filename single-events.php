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

$timber_post     = Timber::query_post();
$context         = Timber::context();
$context['post'] = $timber_post;

if ( post_password_required( $timber_post->ID ) ) {
	Timber::render( 'single-password.twig', $context );
} else {
	if ( true !== StarterSite::is_post_type_events_active() ) {
		Timber::render( '404.twig', $context );
	} else {
		Timber::render( array( 'single-events.twig' ), $context );
	}
}
