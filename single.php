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

$context         = Timber::context();
$timber_post     = Timber::query_post();
global $post, $page, $pages, $multipage;
$context['post'] = $timber_post;
if ( $multipage ) {
    $context['post'] -> post_content = $pages[ $page - 1 ];
}

$author_info = get_post_meta( $timber_post->ID, '_meta_info', true) ? get_post_meta( $timber_post->ID, '_meta_info', true) : 1;

if ($author_info == 'show_author' || $author_info == 1) {
	$context['show_author'] = true;
} else {
	$context['show_author'] = false;
}


if ( post_password_required( $timber_post->ID ) ) {
	Timber::render( 'single-password.twig', $context );
} else {
	Timber::render( array( 'single-' . $timber_post->ID . '.twig', 'single-' . $timber_post->post_type . '.twig', 'single-' . $timber_post->slug . '.twig', 'single.twig' ), $context );
}
