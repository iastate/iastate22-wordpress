<?php


/**
 * @return true|WP_Error
 */
function iastate22_wordpress_is_environment_compatible() {
  if ( ! function_exists( 'get_field' ) ) {
    return new WP_Error( __( 'ACF Pro Required' ), __( 'Please activate the Advanced Custom Fields PRO plugin' ) );
  }
  if ( ! class_exists( 'Timber' ) ) {
    return new WP_Error( __( 'Timber Library Required' ), __( 'Timber not activated' ) );
  }

  return true;
}

/**
 * Timber starter-theme
 * https://github.com/timber/starter-theme
 *
 * @package  WordPress
 * @subpackage  Timber
 * @since   Timber 0.1
 */
if ( iastate22_wordpress_is_environment_compatible() !== true ) {
  function acf_requirement_admin_notice__error() {
    $error = iastate22_wordpress_is_environment_compatible();
    if ( $error === true ) {
      return;
    }

    printf( '<div class="notice notice-error"><p><strong>%1$s</strong></p><p>%2$s</p></div>', esc_attr( $error->get_error_code() ), esc_html( $error->get_error_message() ) );
  }

  add_action( 'admin_notices', 'acf_requirement_admin_notice__error' );

  return;
}
