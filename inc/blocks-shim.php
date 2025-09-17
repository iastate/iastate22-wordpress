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

add_filter('idf_acf_block_render_context_video', 'idf_acf_block_shim_video', 10, 6);