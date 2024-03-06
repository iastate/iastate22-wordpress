<?php

// Register Custom Profiles
function custom_profiles() {
	if ( true !== get_field( 'profiles_enabled', 'options' ) ) {
		return;
	}

	$labels = array(
		'name'                  => _x( 'Profiles', 'Personal Profiles General Name' ),
		'singular_name'         => _x( 'Profile', 'Profile Singular Name' ),
		'menu_name'             => __( 'Personal Profile Pages' ),
		'name_admin_bar'        => __( 'Personal Profile Page' ),
		'archives'              => __( 'Personal Profile Page Archives' ),
		'attributes'            => __( 'Personal Profile Page Attributes' ),
		'parent_item_colon'     => __( 'Parent Item:' ),
		'all_items'             => __( 'All Personal Profile Pages' ),
		'add_new_item'          => __( 'Add New Personal Profile Page' ),
		'add_new'               => __( 'Add New Personal Profile' ),
		'new_item'              => __( 'New Personal Profile Page' ),
		'edit_item'             => __( 'Edit Personal Profile Page' ),
		'update_item'           => __( 'Update Personal Profile Page' ),
		'view_item'             => __( 'View Personal Profile Page' ),
		'view_items'            => __( 'View Items' ),
		'search_items'          => __( 'Search Personal Profile Pages' ),
		'not_found'             => __( 'Not found' ),
		'not_found_in_trash'    => __( 'Not found in Trash' ),
		'featured_image'        => __( 'Featured Image' ),
		'set_featured_image'    => __( 'Set featured image' ),
		'remove_featured_image' => __( 'Remove featured image' ),
		'use_featured_image'    => __( 'Use as featured image' ),
		'insert_into_item'      => __( /** @lang text */ 'Insert into personal profile page' ),
		'uploaded_to_this_item' => __( 'Uploaded to this personal profile page' ),
		'items_list'            => __( 'Personal Profile Pages list' ),
		'items_list_navigation' => __( 'Personal Profile Pages list navigation' ),
		'filter_items_list'     => __( 'Filter personal profile pages list' ),
	);
	$args   = array(
		'label'               => __( 'Profiles' ),
		'description'         => __( 'Personal Profile Pages' ),
		'labels'              => $labels,
		'supports'            => array( 'title', 'page-attributes' ),
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
		'capability_type'     => 'page',
	);
	register_post_type( 'profiles', $args );
	register_taxonomy( 'department', 'profiles', array(
		// Hierarchical taxonomy (like categories)
		'hierarchical' => true,
		'show_in_rest' => true,
		// This array of options controls the labels displayed in the WordPress Admin UI
		'labels'       => array(
			'name'              => _x( 'Department or Unit', 'taxonomy general name' ),
			'singular_name'     => _x( 'Department or Unit', 'taxonomy singular name' ),
			'search_items'      => __( 'Search Departments' ),
			'all_items'         => __( 'All Departments' ),
			'parent_item'       => __( 'Parent Department or Unit' ),
			'parent_item_colon' => __( 'Parent Department or Unit:' ),
			'edit_item'         => __( 'Edit Department or Unit' ),
			'update_item'       => __( 'Update Department or Unit' ),
			'add_new_item'      => __( 'Add New Department or Unit' ),
			'new_item_name'     => __( 'New Department or Unit' ),
			'menu_name'         => __( 'Department or Unit' ),
		),
		// Control the slugs used for this taxonomy
		'rewrite'      => array(
			'slug'         => 'locations', // This controls the base slug that will display before each term
			'with_front'   => false, // Don't display the category base before "/locations/"
			'hierarchical' => true // This will allow URL's like "/locations/boston/cambridge/"
		),
	) );

	register_taxonomy( 'affiliation', 'profiles', array(
		// Hierarchical taxonomy (like categories)
		'hierarchical' => true,
		'show_in_rest' => true,
		// This array of options controls the labels displayed in the WordPress Admin UI
		'labels'       => array(
			'name'              => _x( 'Affiliation', 'taxonomy general name' ),
			'singular_name'     => _x( 'Affiliation', 'taxonomy singular name' ),
			'search_items'      => __( 'Search Affiliations' ),
			'all_items'         => __( 'All Affiliations' ),
			'parent_item'       => __( 'Parent Affiliation' ),
			'parent_item_colon' => __( 'Parent Affiliation:' ),
			'edit_item'         => __( 'Edit Affiliation' ),
			'update_item'       => __( 'Update Affiliation' ),
			'add_new_item'      => __( 'Add New Affiliation' ),
			'new_item_name'     => __( 'New Affiliation Name' ),
			'menu_name'         => __( 'Affiliation' ),
		),
		// Control the slugs used for this taxonomy
		'rewrite'      => array(
			'slug'         => 'division', // This controls the base slug that will display before each term
			'with_front'   => false, // Don't display the category base before "/locations/"
			'hierarchical' => true // This will allow URL's like "/locations/boston/cambridge/"
		),
	) );

}

add_action( 'init', 'custom_profiles', 0 );

function acf_custom_post_type_changed_check( $value, $post_id, $field, $original ) {
	if ( 'options' !== $post_id ) {
		return $value;
	}
	$current_value    = intval( get_field( 'profiles_enabled', 'options' ) );
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

add_action( 'acf/update_value/key=field_64c2aa1e5879d', 'acf_custom_post_type_changed_check', 10, 4 );
