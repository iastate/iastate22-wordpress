<?php
/**
 * Timber starter-theme
 * https://github.com/timber/starter-theme
 *
 * @package  WordPress
 * @subpackage  Timber
 * @since   Timber 0.1
 */

 /**
 * Initialize Timber.
 */
$composer_autoload = __DIR__ . '/vendor/autoload.php';
if ( file_exists( $composer_autoload ) ) {
	require_once $composer_autoload;
	$timber = new Timber\Timber();
}

include('inc/timber.php');
include('inc/blocks.php');
include('inc/editor.php');
include('inc/menus.php');
include('inc/overrides.php');
include('inc/validation.php');
include('inc/breadcrumbs.php');
include('inc/search.php');


// Won't work as an include
require 'plugin-update-checker/plugin-update-checker.php';
$myUpdateChecker = Puc_v4_Factory::buildUpdateChecker(
	'https://github.com/idfive/Iowa_test',
	__FILE__,
	'iowa-state'
);

//Set the branch that contains the stable release.
$myUpdateChecker->setBranch('');

//Optional: If you're using a private repository, specify the access token like this:
$myUpdateChecker->setAuthentication('your-token-here');

/* Define the custom box */


add_action( 'add_meta_boxes', 'post_options_metabox' );
add_action( 'admin_init', 'post_options_metabox', 1 );
add_action( 'save_post', 'save_post_options' );

function post_options_metabox() {
    add_meta_box( 'post_options', __( 'Show/Hide Post Author' ), 'post_options_code', 'post', 'normal', 'high' );
}

function post_options_code( $post ) { 
    wp_nonce_field( plugin_basename( __FILE__ ), $post->post_type . '_noncename' );
    $author_info = get_post_meta( $post->ID, '_meta_info', true) ? get_post_meta( $post->ID, '_meta_info', true) : 1; 
	
	
	?>
    <div class="alignleft">
		<input id="show_author" type="radio" name="_meta_info" value="show_author"<?php checked( 'show_author', $author_info ); ?> <?php echo ( $author_info == 1 )?' checked="checked"' : ''; ?>/>
		<label for="show_author" class="selectit"><?php _e( 'Show author' ); ?></label>
		<br />
        <input id="hide_author" type="radio" name="_meta_info" value="hide_author"<?php checked( 'hide_author', $author_info ); ?> />
		<label for="hide_author" class="selectit"><?php _e( 'Hide author' ); ?></label>
		<br />
    </div>
    <div class="alignright">
        <span class="description"><?php _e( 'This shows or hides the post author, in the front end template.' ); ?></span>
    </div>
    <div class="clear"></div><?php
}

function save_post_options( $post_id ) {
	// verify if this is an auto save routine. 
	// If it is our form has not been submitted, so we dont want to do anything
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) 
		return;
  
	// verify this came from the our screen and with proper authorization,
	// because save_post can be triggered at other times
	if ( !wp_verify_nonce( @$_POST[$_POST['post_type'] . '_noncename'], plugin_basename( __FILE__ ) ) )
		return;
  
	// Check permissions
	if ( !current_user_can( 'edit_post', $post_id ) )
	   return;
  
	// OK, we're authenticated: we need to find and save the data
	if( 'post' == $_POST['post_type'] ) {
		if ( !current_user_can( 'edit_post', $post_id ) ) {
			return;
		} else {
			update_post_meta( $post_id, '_meta_info', $_POST['_meta_info'] );
		}
	} 
  
  }