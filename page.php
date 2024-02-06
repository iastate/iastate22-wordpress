<?php
/**
 * The template for displaying all pages.
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site will use a
 * different template.
 *
 * To generate specific templates for your pages you can use:
 * /mytheme/templates/page-mypage.twig
 * (which will still route through this PHP file)
 * OR
 * /mytheme/page-mypage.php
 * (in which case you'll want to duplicate this file and save to the above path)
 *
 * Methods for TimberHelper can be found in the /lib subdirectory
 *
 * @package  WordPress
 * @subpackage  Timber
 * @since    Timber 0.1
 */

use Timber\Post;
use Timber\Timber;

$context = Timber::context();
global $post, $page, $pages, $multipage;
setup_postdata( $post );

$timber_post = new Post();

$context['post'] = $timber_post;
if ( $multipage ) {
	$context['post']->post_content = $pages[ $page - 1 ];
}

if ( post_password_required( $timber_post->ID ) ) {
	Timber::render( 'single-password.twig', $context );
} else if ( $timber_post->post_type === "profiles" ) {
	Timber::render( array( 'single-profile.twig' ), $context );
} else {
	Timber::render( array( 'page-' . $timber_post->post_name . '.twig', 'page.twig' ), $context );
}
