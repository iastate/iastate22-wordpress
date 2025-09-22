<?php
/**
 * Home template
 */

use Timber\Post;
use Timber\PostQuery;
use Timber\Timber;

$context          = Timber::context();
$context['title'] = wp_title( '', false );
$templates        = array( 'front-page.twig', 'home.twig', 'index.twig' );

if ( 'page' === get_option( 'show_on_front' ) ) {
	$context['post'] = new Post( get_option( 'page_for_posts' ) );
}

$context['posts'] = new PostQuery( array(
		'post_type'     => 'post',
		's'             => get_query_var( 's' ),
		'tag'           => get_query_var( 'tag' ),
		'category_name' => get_query_var( 'category_name' ),
		'paged'         => get_query_var( 'paged', 0 ),
) );

Timber::render( $templates, $context );
