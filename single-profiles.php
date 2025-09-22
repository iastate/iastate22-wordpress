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

$timber_post     = Timber::query_post();
$context         = Timber::context();
$context['post'] = $timber_post;
$profileToggle   = $context["options"]["profiles_enabled"];

$profileTax         = get_object_taxonomies( array( 'post_type' => 'profiles' ), 'objects' );
$profileTerms       = array();
foreach ( $profileTax as $item ) {
	$slug  = $item->name;
	$terms = get_terms( array(
			'taxonomy'   => $item->name,
			'hide_empty' => $slug,
	) );
	$profileTerms[] = $terms;
}
$context['profile_tax']   = $profileTax;
$context['profile_terms'] = $profileTerms;

if ( post_password_required( $timber_post->ID ) ) {
	Timber::render( 'single-password.twig', $context );
} else {
	if ( $profileToggle === false ) {
		Timber::render( '404.twig', $context );
	} else {
		Timber::render( array( 'single-profile.twig' ), $context );
	}
}
