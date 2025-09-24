<?php

include_once( ABSPATH . 'wp-admin/includes/plugin.php' );

add_action( 'init', array( Iastate22_Blocks::class, 'init' ) );

/**
 * Register new custom block category, and add as first item in list.
 *
 * @param array $categories
 *
 * @return array
 * @see 'block_categories_all'
 */
function idf_plugin_block_categories( $categories ) {
	$custom_isu_category = array(
			'slug'  => 'isu-blocks',
			'title' => __( 'Iowa State Theme', 'isu-boilerplate' )
	);
	array_unshift( $categories, $custom_isu_category );

	return $categories;
}

add_action( 'block_categories_all', 'idf_plugin_block_categories', 10, 2 );

/**
 * Filters the content of a single block.
 *
 * @param string $block_content The block content.
 * @param array $block The full block, including name and attributes.
 *
 * @return string
 * @see 'render_block'
 */
function theme_block_wrappers( $block_content, $block ) {
	switch ( $block['blockName'] ) {
		case 'core/paragraph':
		case 'core/heading':
			return '<div class="paragraph-widget paragraph-widget--text-html"><div class="text-content">' . $block_content . '</div></div>';
		case 'core/table':
			return '<div class="paragraph-widget paragraph-widget--table"><div class="iastate22-table" role="region" aria-labelledby="caption_one" tabindex="0">' . $block_content . '</div></div>';
		default:
			return $block_content;
	}
}

add_filter( 'render_block', 'theme_block_wrappers', 10, 2 );
