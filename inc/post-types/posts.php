<?php

// Register Custom Post Type
function custom_post_type() {

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
		'insert_into_item'      => __( 'Insert into personal profile page' ),
		'uploaded_to_this_item' => __( 'Uploaded to this personal profile page' ),
		'items_list'            => __( 'Personal Profile Pages list' ),
		'items_list_navigation' => __( 'Personal Profile Pages list navigation' ),
		'filter_items_list'     => __( 'Filter personal profile pages list' ),
	);
	$args = array(
		'label'                 => __( 'Profiles' ),
		'description'           => __( 'Personal Profile Pages' ),
		'labels'                => $labels,
		'supports'      	  => array( 'title', 'page-attributes' ),
		'hierarchical'          => true,
		'public'                => true,
		'show_ui'               => true,
		'show_in_menu'          => true,
		'menu_position'         => 5,
		'show_in_admin_bar'     => true,
		'show_in_nav_menus'     => true,
		'can_export'            => true,
		'has_archive'           => true,
		'exclude_from_search'   => false,
		'publicly_queryable'    => true,
		'capability_type'       => 'page',
	);
	register_post_type( 'profiles', $args );

}
add_action( 'init', 'custom_post_type', 0 );