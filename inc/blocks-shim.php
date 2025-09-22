<?php

/**
 *  Attempt to patch legacy data for the video block
 *
 * @param $context
 * @param $attributes
 * @param $content
 * @param $is_preview
 * @param $post_id
 * @param $wp_block
 *
 * @return array
 * @since 1.3.7
 */
function idf_acf_block_shim_video( $context, $attributes, $content, $is_preview, $post_id, $wp_block ) {

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

add_filter( 'idf_acf_block_render_context/video', 'idf_acf_block_shim_video', 10, 6 );

/**
 *  Set events for block `upcoming-events`
 *
 * @param $context
 * @param $attributes
 * @param $content
 * @param $is_preview
 * @param $post_id
 * @param $wp_block
 *
 * @return array
 * @since 1.3.8
 */
function idf_acf_block_shim_upcoming_events( $context, $attributes, $content, $is_preview, $post_id, $wp_block ) {

	if ( isset( $context['next_four'] ) ) {
		return $context;
	}

	$context['next_four'] = new \Timber\PostQuery( array(
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

add_filter( 'idf_acf_block_render_context/upcoming-events', 'idf_acf_block_shim_upcoming_events', 10, 6 );

/**
 *  Set events for block `featured-event-with-calendar`
 *
 * @param $context
 * @param $attributes
 * @param $content
 * @param $is_preview
 * @param $post_id
 * @param $wp_block
 *
 * @return array
 * @since 1.3.8
 */
function idf_acf_block_shim_featured_event_with_calendar( $context, $attributes, $content, $is_preview, $post_id, $wp_block ) {

	if ( isset( $context['upcoming_events'] ) ) {
		return $context;
	}

	$context['upcoming_events'] = new \Timber\PostQuery( array(
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

add_filter( 'idf_acf_block_render_context/featured-event-with-calendar', 'idf_acf_block_shim_featured_event_with_calendar', 10, 6 );


/**
 *  Set events for block `interior-hero`
 *
 * @param $context
 * @param $attributes
 * @param $content
 * @param $is_preview
 * @param $post_id
 * @param $wp_block
 *
 * @return array
 * @since 1.3.8
 */
function idf_acf_block_shim_interior_hero( $context, $attributes, $content, $is_preview, $post_id, $wp_block ) {

	if ( isset( $context['childrens'] ) ) {
		return $context;
	}

	$timber_post  = new \Timber\Post();
	$parent       = $timber_post->post_parent;
	$parent_title = get_the_title( $parent );
	$children     = array();
	$arg          = array(
			'parent'      => $parent,
			'sort_order'  => 'ASC',
			'sort_column' => 'menu_order'
	);

	foreach ( get_pages( $arg ) as $d ) {
		$arr        = ( array(
				'parent'      => $d->ID,
				'sort_order'  => 'ASC',
				'sort_column' => 'menu_order'
		) );
		$children[] = get_pages( $arr );
	}

	$context['siblings']     = get_pages( $arg );
	$context['current']      = $timber_post->ID;
	$context['childrens']    = $children;
	$context['rent']         = $parent;
	$context['parent_title'] = $parent_title;

	return $context;
}

add_filter( 'idf_acf_block_render_context/interior-hero', 'idf_acf_block_shim_interior_hero', 10, 6 );


/**
 *  Set events for block `recent-articles`
 *
 * @param $context
 * @param $attributes
 * @param $content
 * @param $is_preview
 * @param $post_id
 * @param $wp_block
 *
 * @return array
 * @since 1.3.8
 */
function idf_acf_block_shim_recent_articles( $context, $attributes, $content, $is_preview, $post_id, $wp_block ) {

	$postCount             = $context['fields']['news_stories'];
	$context['post_count'] = (int) $postCount;

	if ( $context['fields']['feed_style']['value'] == "syndicated" ) {
		$order                      = $context['fields']['feed_order']['value'];
		$cat                        = $context['fields']['feed_category'];
		$tag                        = $context['fields']['feed_tags'];
		$argh                       = 'post_type=post&numberposts=' . $postCount . '&category=' . $cat . '&tag_id=' . $tag . '&orderby=date&order=' . $order . '';
		$context['recent_articles'] = \Timber\Timber::get_posts( $argh ); // uses wp_query format.
	}

	return $context;
}

add_filter( 'idf_acf_block_render_context/recent-articles', 'idf_acf_block_shim_recent_articles', 10, 6 );


/**
 *  Set events for block `recent-articles`
 *
 * @param $context
 * @param $attributes
 * @param $content
 * @param $is_preview
 * @param $post_id
 * @param $wp_block
 *
 * @return array
 * @since 1.3.8
 */
function idf_acf_block_shim_news_filter( $context, $attributes, $content, $is_preview, $post_id, $wp_block ) {


	return $context;
}

add_filter( 'idf_acf_block_render_context/recent-articles', 'idf_acf_block_shim_recent_articles', 10, 6 );
