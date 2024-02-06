<?php
/**
 * The template for displaying Author Archive pages
 *
 * Methods for TimberHelper can be found in the /lib subdirectory
 *
 * @package  WordPress
 * @subpackage  Timber
 * @since    Timber 0.1
 */

global $wp_query;


use Timber\PostQuery;
use Timber\Timber;

$context          = Timber::context();
$context['posts'] = new PostQuery();
if ( isset( $wp_query->query_vars['author'] ) ) {
	$author            = new \Timber\User( $wp_query->query_vars['author'] );
	$context['author'] = $author;
	$context['title']  = 'Author Archives: ' . $author->name();
}
Timber::render( array( 'author.twig', 'archive.twig' ), $context );
