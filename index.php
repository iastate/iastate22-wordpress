<?php
/**
 * The main template file
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists
 *
 * Methods for TimberHelper can be found in the /lib subdirectory
 *
 * @package  WordPress
 * @subpackage  Timber
 * @since   Timber 0.1
 */

use Timber\Post;
use Timber\PostQuery;
use Timber\Timber;

$templates        = array( 'index.twig' );
$timber_post      = new Post();
$context          = Timber::context();
$context['post']  = $timber_post;
$context['title'] = wp_title( '', false );
$context['posts'] = new PostQuery();

if ( is_home() ) {
	array_unshift( $templates, 'front-page.twig', 'home.twig' );
}
Timber::render( $templates, $context );
