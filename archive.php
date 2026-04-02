<?php
/**
 *
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

$templates        = array( 'archive.twig', 'index.twig' );
$context          = Timber::context();
$context['posts'] = new PostQuery();

StarterSite::set_archive_title_context( $context, $templates );

Timber::render( $templates, $context );
