<?php
/**
 * Search results page
 *
 * Methods for TimberHelper can be found in the /lib subdirectory
 *
 * @package  WordPress
 * @subpackage  Timber
 * @since   Timber 0.1
 */

use Timber\PostQuery;
use Timber\Timber;

$templates             = array( 'search.twig' );
$context               = Timber::context();
$context['title']      = 'Search results for: ' . get_search_query();
$context['categories'] = Timber::get_terms( 'categories' );
$context['tags']       = Timber::get_terms( 'tags' );

if ( false === StarterSite::maybe_load_profile_search_context($context) ) {
	$context['posts'] = new PostQuery();
}
Timber::render( $templates, $context );
