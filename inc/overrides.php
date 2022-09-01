<?php
function remove_body_classes( $wp_classes ) {
    $blacklist = array( 'home' );
    $wp_classes = array_diff( $wp_classes, $blacklist );
    return $wp_classes;
}
add_filter( 'body_class', 'remove_body_classes', 10, 2 );