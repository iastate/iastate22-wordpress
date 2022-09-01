<?php
function remove_unfiltered_html_cap() {
    $wp_roles = wp_roles();
    $wp_roles->remove_cap( 'editor', 'unfiltered_html' );
}

// This function actually only needs to run once, so you can comment this out
// after loading the site once.
add_action( 'init', 'remove_unfiltered_html_cap', 5 );