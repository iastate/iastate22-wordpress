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

global $post, $page, $pages, $multipage;

$timber_post     = Timber::query_post();
$context         = Timber::context();
$context['post'] = $timber_post;

if ( $multipage ) {
	$context['post']->post_content = $pages[ $page - 1 ];
}
if ( post_password_required( $timber_post->ID ) ) {
	Timber::render( 'single-password.twig', $context );
} else {
	Timber::render( array(
		'single-' . $timber_post->ID . '.twig',
		'single-' . $timber_post->post_type . '.twig',
		'single-' . $timber_post->slug . '.twig',
		'single.twig'
	), $context );
}
