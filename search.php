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

$templates = array( 'search.twig' );
$context          = Timber::context();
$context['title'] = 'Search results for: ' . get_search_query();
$context['searchtitle'] = $context['title']; // make sure this is only set if going through search.php by using new context var
$paramArray = array();
$tq = array();
foreach($_GET as $key => $value){
    if($key !== "post_type" && $key !== "search_letter" && $key !== "s" && strlen($value)) {
        array_push($paramArray, [$key, $value]);
    }
}
if(get_query_var("post_type") == "profiles") {
    $search_letter = $_GET["search_letter"];
    $tax_query = array();
    $meta_query = array();

    foreach($paramArray as $tax) {
        array_push($tq, array(
            'taxonomy' => $tax[0],
            'field' => 'slug',
            'terms' => $tax[1],
        ));
    }

    if(count($paramArray) > 0) {
        $tax_query = array(
            'relation' => 'AND',
            $tq
        );
    }

    if(strlen($search_letter) > 0) {
        $meta_query = array(
            array(
                'key' => 'last_name',
                'value' => "^[".$search_letter."]",
                'compare' => 'REGEXP'
            )
        );
    }

  $arr = array(
    'post_type' => 'profiles',
    'order' => 'ASC',
    'orderby' => array(
        'last_name_clause' => 'ASC',
        'first_name_clause' => 'ASC',
    ),
    'meta_key' => 'last_name',
    'paged' => $paged,
    's' => $s,
    'tax_query' => $tax_query,
    'meta_query' => array (
        'relation' => 'AND',
        'last_name_clause' => array(
            'key' => 'last_name',
            'compare' => 'EXISTS'
        ),
         'first_name_clause' => array(
            'key' => 'first_name',
            'compare' => 'EXISTS'
        ),$meta_query
       )
);
    $argh = array(
        'post_type' => 'profiles',
        'posts_per_page' => -1,
        'order' => 'DESC',
        'orderby' => 'meta_value',
        'paged' => $paged,
        's' => $s,
        'tax_query' => $tax_query,
        'meta_query' => $meta_query
    );
    $context['posts'] = new Timber\PostQuery($arr);
    $context['allposts'] = new Timber\PostQuery($argh);
    // This works for the search filter, but not the search query or Taxonomies.
} else {
    $context['posts'] = new Timber\PostQuery();
}
Timber::render( $templates, $context );
