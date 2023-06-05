<?php
/**
 * The main template file
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists
 *
 * Methods for TimberHelper can be found in the /lib sub-directory
 *
 * @package  WordPress
 * @subpackage  Timber
 * @since   Timber 0.1
 */

$context          = Timber::context();
$timber_post     = new Timber\Post();
$context['post'] = $timber_post;
$context['title'] = wp_title('', false);
$s = get_query_var('s');
$cat = get_query_var('category_name');
$tag = get_query_var('tag');

$rarr = array(
	'post_type' => 'post',
	'order' => 'DESC',
	'category_name' => $cat,
	'posts_per_page' => 5,
	'tag' => $tag,
	's' => $s,
	'paged' => $paged
);
$context['posts'] = new Timber\PostQuery();
$templates        = array( 'index.twig' );
if ( is_home() ) {
	array_unshift( $templates, 'front-page.twig', 'home.twig' );
}
Timber::render( $templates, $context );