<?php
/**
 * The searchform.php template.
 *
 * Used any time that get_search_form() is called.
 *
 * @link https://developer.wordpress.org/reference/functions/wp_unique_id/
 * @link https://developer.wordpress.org/reference/functions/get_search_form/
 *
 * @package WordPress
 * @subpackage Twenty_Twenty_One
 * @since 1.0.0
 */

/*
 * Generate a unique ID for each form and a string containing an aria-label
 * if one was passed to get_search_form() in the args array.
 */
$search_form_id = wp_unique_id( 'searchForm_' );
?>

<label class="visible-for-screen-readers" for="<?php echo esc_attr( $search_form_id ) ?>">Search</label>
<input type="search" id="<?php echo esc_attr( $search_form_id ) ?>" name="s" placeholder="Search" class="site-search">
<button type="submit"><span class="fak fa-iastate22-magnifying-glass" aria-hidden="true"></span><span
		class="visible-for-screen-readers">Submit Search</span></button>
