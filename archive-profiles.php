<?php
/**
 * The template for displaying Archive pages.
 *
 * Used to display archive-type pages if nothing more specific matches a query.
 * For example, puts together date-based pages if no date.php file exists.
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * Methods for TimberHelper can be found in the /lib subdirectory
 *
 * @package  WordPress
 * @subpackage  Timber
 * @since   Timber 0.2
 */

use Timber\PostQuery;
use Timber\Timber;

$templates = array( 'archive-profiles.twig', 'index.twig' );

$context     = Timber::context();
$param_array = array();
$tq          = array();
$search_letter = "";
$tax_query     = array();
$meta_query    = array();
$profile_toggle = $context["options"]["profiles_enabled"];

foreach ( $param_array as $tax_param ) {
	$tq[] = array(
			'taxonomy' => $tax_param[0],
			'field'    => 'slug',
			'terms'    => $tax_param[1],
	);
}

if ( count( $param_array ) > 0 ) {
	$tax_query = array(
		'relation' => 'AND',
		$tq
	);
}

if ( strlen( $search_letter ) > 0 ) {
	$meta_query = array(
		array(
			'key'     => 'last_name',
			'value'   => "^[" . $search_letter . "]",
			'compare' => 'REGEXP'
		)
	);
}

$arr              = array(
	'post_type'  => 'profiles',
	'order'      => 'ASC',
	'orderby'    => array(
		'last_name_clause'  => 'ASC',
		'first_name_clause' => 'ASC',
	),
	'meta_key'   => 'last_name',
	'paged'      => $paged,
	's'          => $s,
	'tax_query'  => $tax_query,
	'meta_query' => array(
		'relation'          => 'AND',
		'last_name_clause'  => array(
			'key'     => 'last_name',
			'compare' => 'EXISTS'
		),
		'first_name_clause' => array(
			'key'     => 'first_name',
			'compare' => 'EXISTS'
		),
		$meta_query
	)
);
$argh             = array(
	'post_type'      => 'profiles',
	'posts_per_page' => - 1,
	'order'          => 'DESC',
	'orderby'        => 'meta_value',
	'paged'          => $paged,
	's'              => $s,
	'tax_query'      => $tax_query,
	'meta_query'     => $meta_query
);
$context['title'] = 'Archive';
if ( is_day() ) {
	$context['title'] = 'Archive: ' . get_the_date( 'D M Y' );
} elseif ( is_month() ) {
	$context['title'] = 'Archive: ' . get_the_date( 'M Y' );
} elseif ( is_year() ) {
	$context['title'] = 'Archive: ' . get_the_date( 'Y' );
} elseif ( is_tag() ) {
	$context['title'] = single_tag_title( '', false );
} elseif ( is_category() ) {
	$context['title'] = single_cat_title( '', false );
	array_unshift( $templates, 'archive-' . get_query_var( 'cat' ) . '.twig' );
} elseif ( is_post_type_archive() ) {
	$context['title'] = post_type_archive_title( '', false );
	array_unshift( $templates, 'archive-' . get_post_type() . '.twig' );
}

$context['posts']    = new PostQuery( $arr );
$context['allposts'] = new PostQuery( $argh );
if ( $profile_toggle === false ) {
	Timber::render( '404.twig', $context );
} else {
	Timber::render( $templates, $context );
}
