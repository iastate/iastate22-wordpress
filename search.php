<?php
/**
 * Search results page
 *
 * Methods for TimberHelper can be found in the /lib sub-directory
 *
 * @package  WordPress
 * @subpackage  Timber
 * @since   Timber 0.1
 */

$templates = array( 'search.twig' );
$context          = Timber::context();
$context['title'] = 'Search results for: ' . get_search_query();
if(get_query_var("post_type") == "profiles") {
    $search_letter = get_query_var("starts_with");
    $taxA = get_query_var("taxonomy_a");
    $taxB = get_query_var("taxonomy_b");
    $arr = array(
        'post_type' => 'profiles',
        'posts_per_page' => -1,
        'order' => 'DESC',
        'orderby' => 'menu_order',
        'meta_query' => array(
            array(
                'key' => 'first_name',
                'value' => $search_letter,
                'compare' => 'LIKE'
            )
        )
    );
    $context['posts'] = new Timber\PostQuery();
    // This works for the search filter, but not the search query or Taxonomies. 
} else {
    $context['posts'] = new Timber\PostQuery();
}

Timber::render( $templates, $context );
