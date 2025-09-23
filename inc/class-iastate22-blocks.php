<?php

use Timber\Post;
use Timber\PostQuery;
use Timber\Timber;

/**
 * Theme block filters
 *
 * Used to modify the Timber context for rendering theme blocks.
 *
 * @since 1.3.7 Moved stand-alone functions into class
 */
class Iastate22_Blocks {

	/**
	 * Register all resources for theme blocks
	 *
	 * @return void
	 */
	public static function init() {
		self::register_blocks();
		self::register_hooks();
	}

	/**
	 * Register blocks with ACF components. Registration order determines position in the editor.
	 *
	 * @return void
	 */
	public static function register_blocks() {
		$template_directory = get_template_directory();

		// Page Hero
		register_block_type( $template_directory . '/blocks/interior-hero' );
		// Homepage Hero
		register_block_type( $template_directory . '/blocks/landing-hero' );
		// Accordion
		register_block_type( $template_directory . '/blocks/accordion' );
		// Announcement
		// Example: https://www.news.iastate.edu/news/stability-top-growing-opposition-bloomberg-steyer
		//register_block_type( $template_directory . '/blocks/announcement' );
		// Blockquote
		register_block_type( $template_directory . '/blocks/blockquote' );
		// Body Content
		register_block_type( $template_directory . '/blocks/text-content' );
		// Button Set
		register_block_type( $template_directory . '/blocks/button-set' );
		// Call to Action - Image
		register_block_type( $template_directory . '/blocks/cta-large' );
		// Call to Action - Text
		register_block_type( $template_directory . '/blocks/cta-small' );
		// Card Set
		register_block_type( $template_directory . '/blocks/cards' );
		// Carousel
		register_block_type( $template_directory . '/blocks/carousel' );
		// Events - Cards
		register_block_type( $template_directory . '/blocks/featured-events' );
		// Events - List
		register_block_type( $template_directory . '/blocks/upcoming-events' );
		// Events - List (with Feature)
		register_block_type( $template_directory . '/blocks/featured-event-with-calendar' );
		// Feature - External
		register_block_type( $template_directory . '/blocks/external-news-story' );
		// Feature - Large
		register_block_type( $template_directory . '/blocks/feature-with-large-image' );
		// Feature - Large with Intro
		register_block_type( $template_directory . '/blocks/callout-inset' );
		// Feature - Small
		register_block_type( $template_directory . '/blocks/callout-with-image' );
		// Feature Set
		register_block_type( $template_directory . '/blocks/callout-set' );
		// Hero - Directory. unused?
		#register_block_type( $template_directory . '/blocks/directory-hero' );
		// Image - Full Width
		register_block_type( $template_directory . '/blocks/full-width-image' );
		// Image Grid with Text
		register_block_type( $template_directory . '/blocks/image-grid-with-text' );
		// Link Block
		register_block_type( $template_directory . '/blocks/link-block' );
		// Link Set
		register_block_type( $template_directory . '/blocks/link-set' );
		// Multi Column Content
		register_block_type( $template_directory . '/blocks/multi-column-content' );
		// News - Cards
		register_block_type( $template_directory . '/blocks/recent-articles' );
		// News - Featured
		register_block_type( $template_directory . '/blocks/featured-news' );
		// News - Items
		register_block_type( $template_directory . '/blocks/news-items' );
		// Statistics
		register_block_type( $template_directory . '/blocks/statistic-with-text' );
		// Table
		register_block_type( $template_directory . '/blocks/tables' );
		// Video
		register_block_type( $template_directory . '/blocks/video' );
	}

	/**
	 * registers class functions to block filters
	 *
	 * @return void
	 */
	public static function register_hooks() {
		add_filter( 'idf_acf_block_render_context',
				array( self::class, 'set_initial_context' ), 10, 6 );
		add_filter( 'idf_acf_block_render_context',
				array( self::class, 'legacy_anchor_support' ), 99, 1 );

		add_filter( 'idf_acf_block_render_context/video',
				array( self::class, 'render_video' ), 10, 6 );
		add_filter( 'idf_acf_block_render_context/upcoming-events',
				array( self::class, 'render_upcoming_events' ), 10, 6 );
		add_filter( 'idf_acf_block_render_context/featured-event-with-calendar',
				array( self::class, 'render_featured_event_with_calendar' ), 10, 6 );
		add_filter( 'idf_acf_block_render_context/interior-hero',
				array( self::class, 'render_interior_hero' ), 10, 6 );
		add_filter( 'idf_acf_block_render_context/recent-articles',
				array( self::class, 'render_recent_articles' ), 10, 6 );
	}

	/**
	 * Render callback to prepare and display a registered block using Timber.
	 *
	 * @param array $attributes The block attributes.
	 * @param string $content The block content.
	 * @param bool $is_preview Whether the block is being rendered for editing preview.
	 * @param int $post_id The current post being edited or viewed.
	 * @param WP_Block $wp_block The block instance (since WP 5.5).
	 *
	 * @return void
	 */
	public static function render_block( $attributes, $content = '', $is_preview = false, $post_id = 0, $wp_block = null ) {
		/**
		 * Block Timber context filter
		 *
		 * Called before sending `$context` to twig template.
		 *
		 * @param array $context Empty array to initialize Timber context
		 * @param array $attributes The block attributes.
		 * @param string $content The block content.
		 * @param bool $is_preview Whether the block is being rendered for editing preview.
		 * @param int $post_id The current post being edited or viewed.
		 * @param WP_Block $wp_block The block instance (since WP 5.5).
		 *
		 * @since 1.3.0
		 */
		$context = apply_filters( 'idf_acf_block_render_context', array(), $attributes, $content, $is_preview, $post_id, $wp_block );

		// Create the slug of the block using the name property in the block.json.
		$slug = str_replace( 'acf/', '', $attributes['name'] );

		/**
		 * Block specific Timber context filter
		 *
		 * Filter for specific blocks. `$slug` should match the block's registered name without the "acf/" prefix.
		 *
		 * @param array $context Empty array for Timber context
		 * @param array $attributes The block attributes.
		 * @param string $content The block content.
		 * @param bool $is_preview Whether the block is being rendered for editing preview.
		 * @param int $post_id The current post being edited or viewed.
		 * @param WP_Block $wp_block The block instance (since WP 5.5).
		 *
		 * @since 1.3.7
		 */
		$context = apply_filters( "idf_acf_block_render_context/{$slug}", $context, $attributes, $content, $is_preview, $post_id, $wp_block );

		// Render the block.
		Timber::render( 'templates/blocks/' . $slug . '.twig', $context );
	}

	/**
	 *
	 * @param array $context Timber context
	 * @param array $attributes The block attributes.
	 * @param string $content The block content.
	 * @param bool $is_preview Whether the block is being rendered for editing preview.
	 * @param int $post_id The current post being edited or viewed.
	 * @param WP_Block $wp_block The block instance (since WP 5.5).
	 *
	 * @return array
	 * @see 'idf_acf_block_render_context'
	 */
	public static function set_initial_context( $context, $attributes, $content, $is_preview, $post_id, $wp_block ) {
		$context               = array_merge( Timber::context(), $context );
		$context['block']      = $attributes;
		$context['fields']     = get_fields();
		$context['is_preview'] = $is_preview;
		$context['postId']     = $post_id;

		return $context;
	}

	/**
	 * support the old anchor. delete after some time.
	 *
	 * @param array $context Timber context
	 *
	 * @deprecated setting 'deprecated' to remember to remove sometime in the future.
	 * @see 'idf_acf_block_render_context'
	 */
	public static function legacy_anchor_support( $context ) {
		if ( ! isset( $context['fields'], $context['block'] ) ) {
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

	/**
	 * Attempt to patch legacy data for the video block
	 *
	 * @param array $context Timber context
	 * @param array $attributes The block attributes.
	 * @param string $content The block content.
	 * @param bool $is_preview Whether the block is being rendered for editing preview.
	 * @param int $post_id The current post being edited or viewed.
	 * @param WP_Block $wp_block The block instance (since WP 5.5).
	 *
	 * @return array
	 * @see 'idf_acf_block_render_context/{$slug}'
	 *
	 * @since 1.3.7
	 */
	public static function render_video( $context, $attributes, $content, $is_preview, $post_id, $wp_block ) {
		if ( ! isset( $context['fields'], $attributes['data'] )
		     || isset( $context['fields']['video_group'] ) ) {
			return $context;
		}

		$data = $attributes['data'];

		if ( empty( $data ) ) {
			return $context;
		}

		$shim = array(
				'title'            => $data['video_title'] ?? null,
				'source'           => $data['video_source'] ?? null,
				'youtube_video_id' => $data['video_youtube_video_id'] ?? null,
				'vimeo_video_id'   => $data['video_vimeo_video_id'] ?? null,
				'video_poster'     => $data['video_video_poster'] ?? null,
				'poster_image'     => $data['video_poster_image'] ?? null,
		);

		if ( ! empty( $shim['poster_image'] ) && (int) $shim['poster_image'] > 0 ) {
			$shim['poster_image'] = get_field( 'video_poster_image', $post_id );
		}

		$context['fields']['shim'] = $shim;

		return $context;
	}

	/**
	 * Set events for block `upcoming-events`
	 *
	 * @param array $context Timber context
	 * @param array $attributes The block attributes.
	 * @param string $content The block content.
	 * @param bool $is_preview Whether the block is being rendered for editing preview.
	 * @param int $post_id The current post being edited or viewed.
	 * @param WP_Block $wp_block The block instance (since WP 5.5).
	 *
	 * @return array
	 * @see 'idf_acf_block_render_context/{$slug}'
	 * @since 1.3.7
	 */
	public static function render_upcoming_events( $context, $attributes, $content, $is_preview, $post_id, $wp_block ) {
		if ( isset( $context['next_four'] ) ) {
			return $context;
		}

		$context['next_four'] = new PostQuery( array(
				'post_type'      => 'events',
				'posts_per_page' => - 1,
				'orderby'        => 'meta_value',
				'order'          => 'ASC',
				'meta_key'       => 'event_start_date_start_date',
				'meta_query'     => array(
						array(
								'key'     => 'event_start_date_start_date',
								'compare' => '>=',
								'value'   => date( "Ymd" ),
								'type'    => 'DATE',
						),
				)
		) );

		return $context;
	}

	/**
	 * Set events for block `featured-event-with-calendar`
	 *
	 * @param array $context Timber context
	 * @param array $attributes The block attributes.
	 * @param string $content The block content.
	 * @param bool $is_preview Whether the block is being rendered for editing preview.
	 * @param int $post_id The current post being edited or viewed.
	 * @param WP_Block $wp_block The block instance (since WP 5.5).
	 *
	 * @return array
	 * @see 'idf_acf_block_render_context/{$slug}'
	 * @since 1.3.7
	 */
	public static function render_featured_event_with_calendar( $context, $attributes, $content, $is_preview, $post_id, $wp_block ) {
		if ( isset( $context['upcoming_events'] ) ) {
			return $context;
		}

		$context['upcoming_events'] = new PostQuery( array(
				'post_type'      => 'events',
				'posts_per_page' => - 1,
				'orderby'        => 'meta_value',
				'order'          => 'ASC',
				'meta_key'       => 'event_start_date_start_date',
				'meta_query'     => array(
						array(
								'key'     => 'event_start_date_start_date',
								'compare' => '>=',
								'value'   => date( "Ymd" ),
								'type'    => 'DATE',
						),
				)
		) );

		return $context;
	}

	/**
	 * Set events for block `interior-hero`
	 *
	 * @param array $context Timber context
	 * @param array $attributes The block attributes.
	 * @param string $content The block content.
	 * @param bool $is_preview Whether the block is being rendered for editing preview.
	 * @param int $post_id The current post being edited or viewed.
	 * @param WP_Block $wp_block The block instance (since WP 5.5).
	 *
	 * @return array
	 * @see 'idf_acf_block_render_context/{$slug}'
	 * @since 1.3.7
	 */
	public static function render_interior_hero( $context, $attributes, $content, $is_preview, $post_id, $wp_block ) {
		if ( isset( $context['childrens'] ) ) {
			return $context;
		}

		$timber_post  = new Post();
		$parent       = $timber_post->post_parent;
		$parent_title = get_the_title( $parent );
		$children     = array();
		$query        = array(
				'parent'      => $parent,
				'sort_order'  => 'ASC',
				'sort_column' => 'menu_order'
		);

		foreach ( get_pages( $query ) as $page_parent ) {
			$sub_pages_query = ( array(
					'parent'      => $page_parent->ID,
					'sort_order'  => 'ASC',
					'sort_column' => 'menu_order'
			) );

			$children[] = get_pages( $sub_pages_query );
		}

		$context['siblings']     = get_pages( $query );
		$context['current']      = $timber_post->ID;
		$context['childrens']    = $children;
		$context['rent']         = $parent;
		$context['parent_title'] = $parent_title;

		return $context;
	}

	/**
	 * Set events for block `recent-articles`
	 *
	 * @param array $context Timber context
	 * @param array $attributes The block attributes.
	 * @param string $content The block content.
	 * @param bool $is_preview Whether the block is being rendered for editing preview.
	 * @param int $post_id The current post being edited or viewed.
	 * @param WP_Block $wp_block The block instance (since WP 5.5).
	 *
	 * @return array
	 * @see 'idf_acf_block_render_context/{$slug}'
	 * @since 1.3.7
	 */
	public static function render_recent_articles( $context, $attributes, $content, $is_preview, $post_id, $wp_block ) {
		$context['post_count'] = (int) $context['fields']['news_stories'];

		if ( "syndicated" === $context['fields']['feed_style']['value'] ) {
			$context['recent_articles'] = Timber::get_posts( array(
					'post_type'   => 'post',
					'orderby'     => 'date',
					'numberposts' => $context['post_count'],
					'category'    => $context['fields']['feed_category'],
					'tag_id'      => $context['fields']['feed_tags'],
					'order'       => $context['fields']['feed_order']['value'],
			) );
		}

		return $context;
	}

}