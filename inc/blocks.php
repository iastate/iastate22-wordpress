<?php

use Timber\Post;
use Timber\Timber;

include_once( ABSPATH . 'wp-admin/includes/plugin.php' );

// Register new custom block category, and add as first item in list.
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
 * Register blocks with ACF components. Registration order determines position in the editor.
 * @return void
 */
function idf_register_acf_blocks_natively() {

	// Accordion
	register_block_type( get_template_directory() . '/blocks/accordion' );

	// Announcement
	// Example: https://www.news.iastate.edu/news/stability-top-growing-opposition-bloomberg-steyer
	//register_block_type( get_template_directory() . '/blocks/announcement' );

	// Blockquote
	register_block_type( get_template_directory() . '/blocks/blockquote' );

	// Body Content
	register_block_type( get_template_directory() . '/blocks/text-content' );

	// Button Set
	register_block_type( get_template_directory() . '/blocks/button-set' );

	// Call to Action - Image
	register_block_type( get_template_directory() . '/blocks/cta-large' );

	// Call to Action - Text
	register_block_type( get_template_directory() . '/blocks/cta-small' );

	// Card Set
	register_block_type( get_template_directory() . '/blocks/cards' );

	// Carousel
	register_block_type( get_template_directory() . '/blocks/carousel' );

	// Events - Cards
	register_block_type( get_template_directory() . '/blocks/featured-events' );

	// Events - List
	register_block_type( get_template_directory() . '/blocks/upcoming-events' );

	// Events - List (with Feature)
	register_block_type( get_template_directory() . '/blocks/featured-event-with-calendar' );

	// Feature - External
	register_block_type( get_template_directory() . '/blocks/external-news-story' );

	// Feature - Large
	register_block_type( get_template_directory() . '/blocks/feature-with-large-image' );

	// Feature - Large with Intro
	register_block_type( get_template_directory() . '/blocks/callout-inset' );

	// Feature - Small
	register_block_type( get_template_directory() . '/blocks/callout-with-image' );

	// Feature Set
	register_block_type( get_template_directory() . '/blocks/callout-set' );

	// Hero - Directory. unused?
	#register_block_type( get_template_directory() . '/blocks/directory-hero' );

	// Homepage Hero
	register_block_type( get_template_directory() . '/blocks/landing-hero' );

	// Image - Full Width
	register_block_type( get_template_directory() . '/blocks/full-width-image' );

	// Image Grid with Text
	register_block_type( get_template_directory() . '/blocks/image-grid-with-text' );

	// Link Block
	register_block_type( get_template_directory() . '/blocks/link-block' );

	// Link Set
	register_block_type( get_template_directory() . '/blocks/link-set' );

	// Multi Column Content
	register_block_type( get_template_directory() . '/blocks/multi-column-content' );

	// News - Cards
	register_block_type( get_template_directory() . '/blocks/recent-articles' );

	// News - Featured
	register_block_type( get_template_directory() . '/blocks/featured-news' );

	// News - Items
	register_block_type( get_template_directory() . '/blocks/news-items' );

	// Page Hero
	register_block_type( get_template_directory() . '/blocks/interior-hero' );

	// Statistics
	register_block_type( get_template_directory() . '/blocks/statistic-with-text' );

	// Table
	register_block_type( get_template_directory() . '/blocks/tables' );

	// Video
	register_block_type( get_template_directory() . '/blocks/video' );
}

add_action( 'init', 'idf_register_acf_blocks_natively' );

/**
 * Render callback to prepare and display a registered block using Timber.
 *
 * @param array $attributes The block attributes.
 * @param string $content The block content.
 * @param bool $is_preview Whether the block is being rendered for editing preview.
 * @param int $post_id The current post being edited or viewed.
 * @param WP_Block $wp_block The block instance (since WP 5.5).
 *
 * @return   void
 */
function idf_acf_unified_block_render_callback( $attributes, $content = '', $is_preview = false, $post_id = 0, $wp_block = null ) {
	$context = apply_filters( 'idf_acf_block_render_context', array(), $attributes, $content, $is_preview, $post_id, $wp_block );

	// Create the slug of the block using the name property in the block.json.
	$slug = str_replace( 'acf/', '', $attributes['name'] );

	$context = apply_filters( sprintf( 'idf_acf_block_render_context_%s', $slug ), $context, $attributes, $content, $is_preview, $post_id, $wp_block );

	// Render the block.
	Timber::render(
			'templates/blocks/' . $slug . '.twig',
			$context
	);
}

function idf_acf_block_render_landing_hero( $attributes, $content = '', $is_preview = false, $post_id = 0, $wp_block = null ) {
	$context = apply_filters( 'idf_acf_block_render_context', array(), $attributes, $content, $is_preview, $post_id, $wp_block );

	if ( $is_preview && ! empty( $attributes['data'] ) ) {
		echo '<img src="' . $attributes['data']['preview_image_help'] . '" style="width:100%; height:auto;">';

		return;
	}

	if ( $is_preview ) {
		echo 'Other condition';

		return;
	}

	Timber::render( 'templates/blocks/landing-hero.twig', $context );
}

/**
 * @param array $attributes The block attributes.
 * @param string $content The block content.
 * @param bool $is_preview Whether the block is being rendered for editing preview.
 * @param int $post_id The current post being edited or viewed.
 * @param WP_Block $wp_block The block instance (since WP 5.5).
 *
 * @return void
 */
function idf_acf_block_render_interior_hero( $attributes, $content = '', $is_preview = false, $post_id = 0, $wp_block = null ) {
	$context = apply_filters( 'idf_acf_block_render_context', array(), $attributes, $content, $is_preview, $post_id, $wp_block );

	$timber_post  = new Post();
	$rent         = $timber_post->post_parent;
	$parent_title = get_the_title( $rent );
	$arg          = ( array(
		'parent'      => $rent,
		'sort_order'  => 'ASC',
		'sort_column' => 'menu_order'
	) );
	$children    = array();
	foreach ( get_pages( $arg ) as $d ) {
		$arr         = ( array(
			'parent'      => $d->ID,
			'sort_order'  => 'ASC',
			'sort_column' => 'menu_order'
		) );
		$children[] = get_pages( $arr );
	}

	$context['siblings']     = get_pages( $arg );
	$context['current']      = $timber_post->ID;
	$context['childrens']    = $children;
	$context['rent']         = $rent;
	$context['parent_title'] = $parent_title;

	Timber::render( 'templates/blocks/interior-hero.twig', $context );
}

function idf_acf_block_render_recent_articles( $attributes, $content = '', $is_preview = false, $post_id = 0, $wp_block = null ) {
	$context = apply_filters( 'idf_acf_block_render_context', array(), $attributes, $content, $is_preview, $post_id, $wp_block );

	$postCount             = $context['fields']['news_stories'];
	$context['post_count'] = (int) $postCount;

	if ( $context['fields']['feed_style']['value'] == "syndicated" ) {
		$order                      = $context['fields']['feed_order']['value'];
		$cat                        = $context['fields']['feed_category'];
		$tag                        = $context['fields']['feed_tags'];
		$argh                       = 'post_type=post&numberposts=' . $postCount . '&category=' . $cat . '&tag_id=' . $tag . '&orderby=date&order=' . $order . '';
		$context['recent_articles'] = Timber::get_posts( $argh ); // uses wp_query format.
	}

	Timber::render( 'templates/blocks/recent-articles.twig', $context );
}

/**
 * @param array $context
 * @param $attributes
 * @param $content
 * @param $is_preview
 *
 * @return array
 */
function idf_acf_block_initial_context( $context, $attributes, $content, $is_preview, $post_id, $wp_block ) {
	$context               = array_merge( Timber::context(), $context );
	$context['block']      = $attributes;
	$context['fields']     = get_fields();
	$context['is_preview'] = $is_preview;
	$context['postId']     = $post_id;

	return $context;
}

add_filter( 'idf_acf_block_render_context', 'idf_acf_block_initial_context', 10, 6 );

/**
 * support the old anchor. delete after some time.
 *
 * @param array $context
 *
 * @deprecated setting 'deprecated' to remember to remove sometime in the future.
 */
function idf_acf_block_support_new_anchor( $context ) {
	if ( ! isset( $context['fields'] ) || ! isset( $context['block'] ) ) {
		return $context;
	}
	if ( ! empty( $context['block']['anchor'] ) ) {
		return $context;
	}
	if ( ! empty( $context['fields']['anchor_target'] ) ) {
		_deprecated_argument( 'idf_acf_block_support_new_anchor', '1.0.87',
			sprintf( 'Old anchor is being used [%s]. Use "HTML Anchor" in sidebar instead.', esc_html( $context['fields']['anchor_target'] ) ) );
		$context['block']['anchor'] = $context['fields']['anchor_target'];
	}

	return $context;
}

add_filter( 'idf_acf_block_render_context', 'idf_acf_block_support_new_anchor', 99, 1 );

/**
 * Filters the content of a single block.
 *
 * @param string $block_content The block content.
 * @param array $block The full block, including name and attributes.
 *
 * @return string
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
