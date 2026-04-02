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

use Timber\Timber;

$templates = array( 'archive-profiles.twig', 'index.twig' );
$context   = Timber::context();

StarterSite::set_archive_title_context( $context, $templates );
StarterSite::maybe_load_profile_search_context( $context );

if ( true !== StarterSite::is_post_type_profile_active() ) {
	Timber::render( '404.twig', $context );
} else {
	Timber::render( $templates, $context );
}
