<?php
//   Custom Event

function custom_event() {

  if (iastate22_wordpress_is_environment_compatible() !== true) {
    return;
  }

	if ( true !== get_field( 'events_options_enabled', 'options' ) ) {
		return;
	}

	$events_labels = array(
		'name'                  => _x( 'Events', 'Events General Name' ),
		'singular_name'         => _x( 'Event', 'Event Singular Name' ),
		'menu_name'             => __( 'Events' ),
		'name_admin_bar'        => __( 'Event' ),
		'archives'              => __( 'Event Archives' ),
		'attributes'            => __( 'Event Attributes' ),
		'parent_item_colon'     => __( 'Parent Item:' ),
		'all_items'             => __( 'All Event' ),
		'add_new_item'          => __( 'Add New Event' ),
		'add_new'               => __( 'Add New Event' ),
		'new_item'              => __( 'New Event' ),
		'edit_item'             => __( 'Edit Event' ),
		'update_item'           => __( 'Update Event' ),
		'view_item'             => __( 'View Event' ),
		'view_items'            => __( 'View Items' ),
		'search_items'          => __( 'Search Event' ),
		'not_found'             => __( 'Not found' ),
		'not_found_in_trash'    => __( 'Not found in Trash' ),
		'featured_image'        => __( 'Featured Image' ),
		'set_featured_image'    => __( 'Set featured image' ),
		'remove_featured_image' => __( 'Remove featured image' ),
		'use_featured_image'    => __( 'Use as featured image' ),
		'insert_into_item'      => __( /** @lang text */ 'Insert into personal event' ),
		'uploaded_to_this_item' => __( 'Uploaded to this personal event' ),
		'items_list'            => __( 'Events list' ),
		'items_list_navigation' => __( 'Events list navigation' ),
		'filter_items_list'     => __( 'Filter personal events list' ),
	);
	register_post_type( 'events', array(
		'label'               => __( 'Events' ),
		'description'         => __( 'Events Calendar' ),
		'labels'              => $events_labels,
		'supports'            => array( 'title', 'editor', 'excerpt', 'thumbnail' ),
		'show_in_rest'        => true,
		'hierarchical'        => true,
		'public'              => true,
		'show_ui'             => true,
		'show_in_menu'        => true,
		'menu_position'       => 5,
		'show_in_admin_bar'   => true,
		'show_in_nav_menus'   => true,
		'can_export'          => true,
		'has_archive'         => true,
		'exclude_from_search' => false,
		'publicly_queryable'  => true,
		'capability_type'     => 'post',
	) );

	$event_tag_labels = array(
		'name'              => _x( 'Tags', 'taxonomy general name' ),
		'singular_name'     => _x( 'Tag', 'taxonomy singular name' ),
		'search_items'      => __( 'Search Tags' ),
		'all_items'         => __( 'All Tags' ),
		'parent_item'       => __( 'Parent Tag' ),
		'parent_item_colon' => __( 'Parent Tag:' ),
		'edit_item'         => __( 'Edit Tag' ),
		'update_item'       => __( 'Update Tag' ),
		'add_new_item'      => __( 'Add New Tag' ),
		'new_item_name'     => __( 'New Tag Name' ),
		'menu_name'         => __( 'Tags' ),
	);

	register_taxonomy( 'event_tags', 'events', array(
			// Hierarchical taxonomy (like categories)
			'hierarchical' => true,
			'show_in_rest' => true,
			// This array of options controls the labels displayed in the WordPress Admin UI
			'labels'       => $event_tag_labels,
			// Control the slugs used for this taxonomy
			'rewrite'      => array(
				'slug'         => 'event_tags', // This controls the base slug that will display before each term
				'with_front'   => false, // Don't display the category base before "/locations/"
				'hierarchical' => true // This will allow URL's like "/locations/boston/cambridge/"
			),
		)
	);

	add_action( 'wp_enqueue_scripts', 'acf_custom_events_api_path_override', 11 );
}

add_action( 'init', 'custom_event', 0 );

function acf_custom_events_api_path_override() {
	$inline_script = 'const MYSCRIPT = ';
	$inline_script .= wp_json_encode(
		array(
			'eventsURL' => rest_get_route_for_post_type_items( 'events' ),
			'rootURL'   => get_rest_url( null, '' ),
		) );
	wp_add_inline_script( 'main', $inline_script, 'before' );
}

function acf_custom_event_changed_check( $value, $post_id, $field, $original ) {
	if ( 'options' !== $post_id ) {
		return $value;
	}
	$current_value    = intval( get_field( 'events_options_enabled', 'options' ) );
	$normalized_value = intval( $value );

	if ( $current_value !== $normalized_value ) {
		// option changed, flush needed.
		add_action( 'acf/options_page/save', function () {
			$_nonce = wp_create_nonce( 'refresh_rewrite_after_post_type_change' );
			wp_redirect( add_query_arg( array(
				'message'               => '1',
				'acf_post_type_refresh' => 'yes',
				'refresh_nonce'         => $_nonce
			) ) );
			exit;
		}, 0 );
	}

	return $value;
}

add_action( 'acf/update_value/key=field_64c2a145cab1f', 'acf_custom_event_changed_check', 10, 4 );

function admin_event_table_listings() {
	add_filter( 'manage_events_posts_columns', 'add_event_table_columns' );
	add_action( 'manage_pages_custom_column', 'add_event_table_column_data', 10, 2 );
}

add_action( 'admin_init', 'admin_event_table_listings' );

function add_event_table_columns( $columns ) {
	return array_merge(
		array_slice( $columns, 0, 2 ),
		array(
			'event_start_date' => esc_html__( 'Event Start' ),
			'event_end_date'   => esc_html__( 'Event End' ),
		),
		array_slice( $columns, 2 )
	);
}

function add_event_table_column_data( $column_name, $post_id ) {
	if ( in_array( $column_name, array( 'event_start_date', 'event_end_date' ), true ) ) {
		$full_day = 1;
		$date     = null;
		$time     = null;
		if ( 'event_start_date' === $column_name ) {
			$full_day = get_post_meta( $post_id, 'event_start_date_full_day', true );
			$date     = get_post_meta( $post_id, 'event_start_date_start_date', true );
			$time     = get_post_meta( $post_id, 'event_start_date_start_time', true );
		}
		if ( 'event_end_date' === $column_name ) {
			$full_day = get_post_meta( $post_id, 'event_end_date_full_day', true );
			$date     = get_post_meta( $post_id, 'event_end_date_end_date', true );
			$time     = get_post_meta( $post_id, 'event_end_date_end_time', true );
		}
		if ( empty( $date ) ) {
			return;
		}

		$format    = $full_day ? get_option( 'date_format' ) : get_option( 'date_format' ) . ' ' . get_option( 'time_format' );
		$timestamp = $full_day ? $date : $date . ' ' . $time;

		echo esc_html( gmdate( $format, strtotime( $timestamp ) ) );
	}

}

/**
 * REST API Query parameters.
 *
 * @param array $args
 * @param WP_REST_Request $request
 *
 * @return array
 */
function event_rest_query( array $args, WP_REST_Request $request ): array {
	return array_merge( $args, array(
		'meta_key'  => 'event_start_date_start_date',
		'meta_type' => 'DATE',
		'orderby'   => 'meta_value',
		'order'     => 'ASC',
	) );
}

add_filter( 'rest_events_query', 'event_rest_query', 10, 2 );
