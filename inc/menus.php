<?php

function register_menus() {
    register_nav_menus(
        array(
            'main-menu' => __( 'Main Menu' ),
            'utility-menu' => __( 'Utility Menu' ),
            'social-menu' => __( 'Social Menu' ),
            'footer-primary-menu' => __( 'Footer Primary Menu' ),
            'footer-secondary-menu' => __( 'Footer Secondary Menu' ),
            'footer-utility-menu' => __( 'Footer Utility Menu' ),
            'ks-sidebar-menu' => __( 'KS Sidebar Menu' )
        )
    );
}
add_action( 'init', 'register_menus' );