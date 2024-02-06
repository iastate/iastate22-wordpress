<?php

/**
 * import theme files.
 * using get_template_directory() to make sure parent theme path is the same.
 */
include( get_template_directory() . '/inc/acf-pro.php' );
include( get_template_directory() . '/inc/timber.php' );
include( get_template_directory() . '/inc/blocks.php' );
include( get_template_directory() . '/inc/editor.php' );
include( get_template_directory() . '/inc/menus.php' );
include( get_template_directory() . '/inc/overrides.php' );
include( get_template_directory() . '/inc/validation.php' );
include( get_template_directory() . '/inc/breadcrumbs.php' );
include( get_template_directory() . '/inc/search.php' );
include( get_template_directory() . '/inc/post-types/events.php' );
include( get_template_directory() . '/inc/post-types/profiles.php' );
