<?php
add_action( 'after_setup_theme', '_gutenberg_css' );

function _gutenberg_css(){

	add_theme_support( 'editor-styles' ); // if you don't add this line, your stylesheet won't be added
	add_editor_style( '/wp_components/build/css/editor.css' ); // tries to include style-editor.css directly from your theme folder
}

//add_action('enqueue_block_editor_assets', '_gutenberg_js');

//function _gutenberg_js() {
//    wp_register_script('editor-js', get_stylesheet_directory_uri() . '/vendor/iastate/frontend-component-library/build/js/editor.js', array(), '', true);
//    wp_enqueue_script('editor-js');
//}

function customWYSIWYG($arr){
	$arr['block_formats'] = 'Paragraph=p;Heading 2=h2;Heading 3=h3;Heading 4=h4';
	return $arr;
}

add_filter('tiny_mce_before_init', 'customWYSIWYG');

# Remove visual editor buttons
function this_tinymce_buttons($buttons)
{
    # Remove the text color selector
    $remove = array('blockquote','wp_adv','fullscreen','wp_more'); //Add other button names to this array
    # Find the array key and then unset
    return array_diff($buttons,$remove);
}
add_filter(
    'mce_buttons',
    'this_tinymce_buttons'
);

/**
  * @param $options
  *
  * @return array
 */
function iastate22_theme_tinymce_options( $options ) {
    if ( isset( $options['body_class'] ) ) {
        $options['body_class'] .= ' text-content';
    } else {
        $options['body_class'] = 'text-content';
    }

    return $options;
}

add_filter( 'tiny_mce_before_init', 'iastate22_theme_tinymce_options' );

add_action( 'init', 'wpse325327_add_excerpts_to_pages' );
function wpse325327_add_excerpts_to_pages() {
    add_post_type_support( 'page', 'excerpt' );
}