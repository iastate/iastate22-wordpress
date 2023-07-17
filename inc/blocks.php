<?php
include_once( ABSPATH . 'wp-admin/includes/plugin.php' );

add_action('acf/init', 'idf_acf_init');

// Register new custom block category, and add as first item in list.
function idf_plugin_block_categories( $categories ) {
    $custom_isu_category = array(
        'slug' => 'isu-blocks',
        'title' => __( 'Iowa State Theme', 'isu-boilerplate' )
    );
    array_unshift( $categories, $custom_isu_category );
    return $categories;
}
add_action( 'block_categories_all', 'idf_plugin_block_categories', 10, 2 );

function idf_acf_init() {
    // if(! function_exists(" acf_register_block_type" )) {
    //     return;
    // }

    if (is_plugin_active('isu-profile-cpt/isu-profile-cpt.php')){
        $activated_profile_plugin = true;
    } else {
        $activated_profile_plugin = false;
    }

    $gtb_preview_image_path = get_template_directory_uri() . "/gtb-preview-images";

    acf_register_block_type( array(
        'name'  => 'landing_hero',
        'title' => __( 'Home Hero'),
        'description' => __( 'Home Hero Section'),
        'render_callback' => 'idf_acf_block_render_landing_hero',
        'category' => 'isu-blocks',
        'align' => 'wide',
        'supports' => array('align' => array('wide','full' ),'multiple' => false), // wide and full are optional,
        'icon' => 'welcome-widgets-menus',
        'mode' => 'edit',
        'keywords' => array('Hero', 'Heroes', 'Top', 'Landing'),
        'example'  => array()
    ));
    acf_register_block_type( array(
        'name'  => 'interior_hero',
        'title' => __( 'Hero'),
        'description' => __( 'Hero Section'),
        'render_callback' => 'idf_acf_block_render_interior_hero',
        'category' => 'isu-blocks',
        'align' => 'wide',
        'supports' => array('align' => array('wide','full' ),'multiple' => false), // wide and full are optional,
        'icon' => 'superhero',
        'mode' => 'edit',
        'keywords' => array('Hero', 'Heroes', 'Top', 'Interior'),
        
    ));
    acf_register_block_type( array(
        'name'  => 'text_content',
        'title' => __( 'Rich Text/WYSIWYG'),
        'description' => __( 'Rich Text/WYSIWYG Editor'),
        'render_callback' => 'idf_acf_block_render_text_content',
        'category' => 'isu-blocks',
        'align' => 'wide',
        'icon' => 'text',
        'mode' => 'edit',
        'keywords' => array('Text', 'Paragraph', 'Copy', 'Editor'),
        
    ));
    acf_register_block_type( array(
        'name'  => 'button_set',
        'title' => __( 'Button Set'),
        'description' => __( 'Link Buttons'),
        'render_callback' => 'idf_acf_block_render_button_set',
        'category' => 'isu-blocks',
        'align' => 'wide',
        'icon' => 'button',
        'mode' => 'edit',
        'keywords' => array('Button', 'Link', 'Switch', 'Control'),
        
    ));
    acf_register_block_type( array(
        'name'  => 'link_set',
        'title' => __( 'Link Set'),
        'description' => __( 'Links'),
        'render_callback' => 'idf_acf_block_render_link_set',
        'category' => 'isu-blocks',
        'align' => 'wide',
        'icon' => 'admin-links',
        'mode' => 'edit',
        'keywords' => array('Link'),
        
    ));
    acf_register_block_type( array(
        'name'  => 'multi_column_content',
        'title' => __( 'Multi Column Content'),
        'description' => __( 'Content column blocks. Maximum of 3'),
        'render_callback' => 'idf_acf_block_render_multi_column_content',
        'category' => 'isu-blocks',
        'align' => 'wide',
        'icon' => 'images-alt2',
        'mode' => 'edit',
        'keywords' => array('Columns', 'Content', 'Multi', 'Layout'),
        
    ));
    acf_register_block_type( array(
        'name'  => 'carousel',
        'title' => __( 'Carousel'),
        'description' => __( 'Carousel'),
        'render_callback' => 'idf_acf_block_render_carousel',
        'category' => 'isu-blocks',
        'align' => 'wide',
        'icon' => 'images-alt2',
        'mode' => 'edit',
        'keywords' => array('Carousel', 'Images', 'Slide', 'Show', 'Slideshow'),
        
    ));
    acf_register_block_type( array(
        'name'  => 'tables',
        'title' => __( 'Table'),
        'description' => __( 'A Data Table'),
        'render_callback' => 'idf_acf_block_render_table',
        'category' => 'isu-blocks',
        'align' => 'wide',
        'icon' => 'editor-table',
        'mode' => 'edit',
        'keywords' => array('Table', 'Data'),
        
    ));
    acf_register_block_type( array(
        'name'  => 'accordion',
        'title' => __( 'Accordion'),
        'description' => __( 'A set of collapsable content areas'),
        'render_callback' => 'idf_acf_block_render_accordion',
        'category' => 'isu-blocks',
        'align' => 'wide',
        'icon' => 'editor-justify',
        'mode' => 'edit',
        'keywords' => array('Accordion', 'Menu', "List"),
        
    ));
    acf_register_block_type( array(
        'name'  => 'statistic-with-text',
        'title' => __( 'Statistic'),
        'description' => __( 'A split column layout with a decorative statistic option'),
        'render_callback' => 'idf_acf_block_render_statistic_with_text',
        'category' => 'isu-blocks',
        'align' => 'wide',
        'icon' => 'analytics',
        'mode' => 'edit',
        'keywords' => array('Statistic', 'Text', "Decorative", "Feature"),
        
    ));
    acf_register_block_type( array(
        'name'  => 'callout-with-image',
        'title' => __( 'Feature With Small Image'),
        'description' => __( 'A decorative image side card'),
        'render_callback' => 'idf_acf_block_render_callout_with_image',
        'category' => 'isu-blocks',
        'align' => 'wide',
        'icon' => 'embed-photo',
        'mode' => 'edit',
        'keywords' => array('Image', 'Callout', "CTA", "Feature", "Card"),
        
    ));
    acf_register_block_type( array(
        'name'  => 'callout-set',
        'title' => __( 'Feature Set'),
        'description' => __( 'A set of decorative side cards with a gray outer background'),
        'render_callback' => 'idf_acf_block_render_callout_set',
        'category' => 'isu-blocks',
        'align' => 'wide',
        'icon' => 'embed-photo',
        'mode' => 'edit',
        'keywords' => array('Image', 'Callout', "CTA", "Feature", "Set", "Cards"),
        
    ));
    acf_register_block_type( array(
        'name'  => 'image-grid-with-text',
        'title' => __( 'Image Grid with Text'),
        'description' => __( 'A set of decorative side cards with a gray outer background'),
        'render_callback' => 'idf_acf_block_render_image_grid_with_text',
        'category' => 'isu-blocks',
        'align' => 'wide',
        'icon' => 'grid-view',
        'mode' => 'edit',
        'keywords' => array('Image', 'Grid', "CTA", "Feature", "Card"),
        
    ));
    acf_register_block_type( array(
        'name'  => 'video',
        'title' => __( 'Video Embed'),
        'description' => __( 'A video player'),
        'render_callback' => 'idf_acf_block_render_video',
        'category' => 'isu-blocks',
        'align' => 'wide',
        'icon' => 'video-alt3',
        'mode' => 'edit',
        'keywords' => array('Video', 'Youtube', "Player"),
        
    ));
    acf_register_block_type( array(
        'name'  => 'link-block',
        'title' => __( 'Link Block'),
        'description' => __( 'A block with a set of links'),
        'render_callback' => 'idf_acf_block_render_link_block',
        'category' => 'isu-blocks',
        'align' => 'wide',
        'icon' => 'admin-links',
        'mode' => 'edit',
        'keywords' => array('Video', 'Youtube', "Player"),
        
    ));
    acf_register_block_type( array(
        'name'  => 'external-news-story',
        'title' => __( 'External News Story'),
        'description' => __( 'A block display for an external news story'),
        'render_callback' => 'idf_acf_block_render_external_news_story',
        'category' => 'isu-blocks',
        'align' => 'wide',
        'icon' => 'admin-links',
        'mode' => 'edit',
        'keywords' => array('Feature', 'News', 'Story'),
        
    ));
    acf_register_block_type( array(
        'name'  => 'blockquote',
        'title' => __( 'Blockquote'),
        'description' => __( 'A decorative blockquote with attribution'),
        'render_callback' => 'idf_acf_block_render_blockquote',
        'category' => 'isu-blocks',
        'align' => 'wide',
        'icon' => 'format-quote',
        'mode' => 'edit',
        'keywords' => array('Quote', 'Blockquote'),
        
    ));
    acf_register_block_type( array(
        'name'  => 'cards',
        'title' => __( 'Card Set'),
        'description' => __( 'A set of image cards'),
        'render_callback' => 'idf_acf_block_render_cards',
        'category' => 'isu-blocks',
        'align' => 'wide',
        'icon' => 'screenoptions',
        'mode' => 'edit',
        'keywords' => array('Card', 'Cards', 'Set'),
        
    ));
    acf_register_block_type( array(
        'name'  => 'feature-with-large-image',
        'title' => __( 'Feature with Large Image'),
        'description' => __( 'A large image with a CTA overlay'),
        'render_callback' => 'idf_acf_block_render_feature_with_large_image',
        'category' => 'isu-blocks',
        'align' => 'wide',
        'icon' => 'format-image',
        'mode' => 'edit',
        'keywords' => array('Feature', 'Image'),
        
    ));
    acf_register_block_type( array(
        'name'  => 'callout-inset',
        'title' => __( 'Feature With Large Image And Intro'),
        'description' => __( 'A large image with a CTA overlay inset in a content area'),
        'render_callback' => 'idf_acf_block_render_callout_inset',
        'category' => 'isu-blocks',
        'align' => 'wide',
        'icon' => 'feedback',
        'mode' => 'edit',
        'keywords' => array('Feature', 'Inset', 'Image'),
        
    ));
    acf_register_block_type( array(
        'name'  => 'cta-small',
        'title' => __( 'Call to Action: Small'),
        'description' => __( 'A small CTA banner'),
        'render_callback' => 'idf_acf_block_render_cta_small',
        'category' => 'isu-blocks',
        'align' => 'wide',
        'icon' => 'align-wide',
        'mode' => 'edit',
        'keywords' => array('Feature', 'Inset', 'Image'),
        
    ));
    acf_register_block_type( array(
        'name'  => 'cta-large',
        'title' => __( 'Call to Action: Large'),
        'description' => __( 'A large CTA banner with a background image'),
        'render_callback' => 'idf_acf_block_render_cta_large',
        'category' => 'isu-blocks',
        'align' => 'wide',
        'icon' => 'align-wide',
        'mode' => 'edit',
        'keywords' => array('Feature', 'Inset', 'Image'),
    ));

    acf_register_block_type( array(
        'name'  => 'full_width_image',
        'title' => __( 'Full Width Image'),
        'description' => __( 'Full Width Image with optional lower CTA'),
        'render_callback' => 'idf_acf_block_render_full_width_image',
        'align' => 'wide',
        'category' => 'isu-blocks',
        'icon' => 'format-image',
        'mode' => 'edit',
        'keywords' => array('Image', 'Full'),
    ));

    acf_register_block_type( array(
        'name'  => 'directory-hero',
        'title' => __( 'Hero -- Directory'),
        'description' => __( 'A plain ecosystem hero with search filtering options'),
        'render_callback' => 'idf_acf_block_render_directory_hero',
        'supports' => array('align' => array('wide','full' ),'multiple' => false),
        'category' => 'isu-blocks',
        'align' => 'wide',
        'icon' => 'align-wide',
        'mode' => 'edit',
        'keywords' => array('Faculty', 'Staff', 'Hero', 'Directory', 'People', "Profiles"),
    ));

    acf_register_block_type( array(
        'name'  => 'featured-events',
        'title' => __( 'Featured Event Cards'),
        'description' => __( 'A curated block of events'),
        'render_callback' => 'idf_acf_block_render_featured_events',
        'supports' => array('align' => array('wide','full' )),
        'category' => 'isu-blocks',
        'align' => 'wide',
        'icon' => 'align-wide',
        'mode' => 'edit',
        'keywords' => array('Events', 'Featured', 'Calendar', 'Dates'),
    ));
    acf_register_block_type( array(
        'name'  => 'upcoming-events',
        'title' => __( 'Upcoming Events'),
        'description' => __( 'A listing of the next four upcoming events'),
        'render_callback' => 'idf_acf_block_render_upcoming_events',
        'supports' => array('align' => array('wide','full' )),
        'category' => 'isu-blocks',
        'align' => 'wide',
        'icon' => 'align-wide',
        'mode' => 'edit',
        'keywords' => array('Events', 'Featured', 'Calendar', 'Dates'),
    ));
    acf_register_block_type( array(
        'name'  => 'featured-event-with-calendar',
        'title' => __( 'Featured Event with Calendar'),
        'description' => __( 'A featured event card with a listing of the next four events'),
        'render_callback' => 'idf_acf_block_render_featured_event_with_calendar',
        'supports' => array('align' => array('wide','full' )),
        'category' => 'isu-blocks',
        'align' => 'wide',
        'icon' => 'align-wide',
        'mode' => 'edit',
        'keywords' => array('Events', 'Featured', 'Calendar', 'Dates'),
    ));
    acf_register_block_type( array(
        'name'  => 'featured-news',
        'title' => __( 'Featured News'),
        'description' => __( 'A featured news block with the option to switch between a two and form item side feed.'),
        'render_callback' => 'idf_acf_block_render_featured_news',
        'supports' => array('align' => array('wide','full' )),
        'category' => 'isu-blocks',
        'align' => 'wide',
        'icon' => 'align-wide',
        'mode' => 'edit',
        'keywords' => array('News', 'Featured'),
    ));
    acf_register_block_type( array(
        'name'  => 'recent-articles',
        'title' => __( 'Recent News Cards'),
        'description' => __( 'A set of up to three cards of the latest News Posts'),
        'render_callback' => 'idf_acf_block_render_recent_articles',
        'supports' => array('align' => array('wide','full' )),
        'category' => 'isu-blocks',
        'align' => 'wide',
        'icon' => 'align-wide',
        'mode' => 'edit',
        'keywords' => array('News', 'Recent', 'Feed', 'Newsfeed'),
    ));
    acf_register_block_type( array(
        'name'  => 'news-items',
        'title' => __( 'In The News'),
        'description' => __( 'A stylized source-able block intended to highlight news stories'),
        'render_callback' => 'idf_acf_block_render_news_stories',
        'supports' => array('align' => array('wide','full' )),
        'category' => 'isu-blocks',
        'align' => 'wide',
        'icon' => 'align-wide',
        'mode' => 'edit',
        'keywords' => array('News', 'Stories', 'Story'),
    ));
}



function idf_acf_block_render_landing_hero( $block, $content = '', $is_preview = false ) {
    $context = Timber::context();

    $context['block'] = $block;
    $context['fields'] = get_fields();
    if ( $is_preview && ! empty( $block['data'] ) ) {
        echo '<img src="'. $block['data']['preview_image_help'] .'" style="width:100%; height:auto;">';
        return;
    } elseif ( $is_preview ) {
        echo 'Other condition';
        return;
    }
    $context['is_preview'] = $is_preview;
    
    Timber::render('templates/blocks/landing-hero.twig', $context);
}

function idf_acf_block_render_interior_hero( $block, $content = '', $is_preview = false ) {
    $context = Timber::context();

    $timber_post = new Timber\Post();
    $rent = $timber_post->post_parent;
    $parent_title = get_the_title($rent);
    $arg = (array(
        'parent' => $rent,
        'sort_order'=> 'ASC',
        'sort_column' => 'menu_order'
    ));
    $childrens = array();
    foreach(get_pages($arg) as $d) {
        $arr = (array(
            'parent' => $d->ID,
            'sort_order'=> 'ASC',
            'sort_column' => 'menu_order'
        ));
        array_push($childrens, get_pages($arr));
    }
    $context['block'] = $block;
    $context['fields'] = get_fields();
    $context['siblings'] = get_pages($arg);
    $context['current'] = $timber_post->ID;
    $context['childrens'] = $childrens;
    $context['rent'] = $rent;
    $context['parent_title'] = $parent_title;
    $context['is_preview'] = $is_preview;
    
    Timber::render('templates/blocks/interior-hero.twig', $context);
}

function idf_acf_block_render_text_content( $block, $content = '', $is_preview = false ) {
    $context = Timber::context();
    $context['block'] = $block;
    $context['fields'] = get_fields();
    $context['is_preview'] = $is_preview;
    Timber::render('templates/blocks/text-content.twig', $context);
}

function idf_acf_block_render_button_set( $block, $content = '', $is_preview = false ) {
    $context = Timber::context();
    $context['block'] = $block;
    $context['fields'] = get_fields();
    $context['is_preview'] = $is_preview;
    Timber::render('templates/blocks/button-set.twig', $context);
}

function idf_acf_block_render_link_set( $block, $content = '', $is_preview = false ) {
    $context = Timber::context();
    $context['block'] = $block;
    $context['fields'] = get_fields();
    $context['is_preview'] = $is_preview;
    Timber::render('templates/blocks/link-set.twig', $context);
}

function idf_acf_block_render_multi_column_content( $block, $content = '', $is_preview = false ) {
    $context = Timber::context();
    $context['block'] = $block;
    $context['fields'] = get_fields();
    $context['is_preview'] = $is_preview;
    Timber::render('templates/blocks/multi-column-content.twig', $context);
}

function idf_acf_block_render_carousel( $block, $content = '', $is_preview = false ) {
    $context = Timber::context();
    $context['block'] = $block;
    $context['fields'] = get_fields();
    $context['is_preview'] = $is_preview;
    Timber::render('templates/blocks/carousel.twig', $context);
}

function idf_acf_block_render_table( $block, $content = '', $is_preview = false ) {
    $context = Timber::context();
    $context['block'] = $block;
    $context['fields'] = get_fields();
    $context['is_preview'] = $is_preview;
    Timber::render('templates/blocks/table.twig', $context);
}

function idf_acf_block_render_accordion( $block, $content = '', $is_preview = false ) {
    $context = Timber::context();
    $context['block'] = $block;
    $context['fields'] = get_fields();
    $context['is_preview'] = $is_preview;
    Timber::render('templates/blocks/accordion.twig', $context);
}

function idf_acf_block_render_full_width_image( $block, $content = '', $is_preview = false ) {
    $context = Timber::context();
    $context['block'] = $block;
    $context['fields'] = get_fields();
    $context['is_preview'] = $is_preview;
    Timber::render('templates/blocks/full-width-image.twig', $context);
}

function idf_acf_block_render_statistic_with_text( $block, $content = '', $is_preview = false ) {
    $context = Timber::context();
    $context['block'] = $block;
    $context['fields'] = get_fields();
    $context['is_preview'] = $is_preview;
    Timber::render('templates/blocks/statistic-with-text.twig', $context);
}
function idf_acf_block_render_callout_with_image( $block, $content = '', $is_preview = false ) {
    $context = Timber::context();
    $context['block'] = $block;
    $context['fields'] = get_fields();
    $context['is_preview'] = $is_preview;
    Timber::render('templates/blocks/callout-with-image.twig', $context);
}

function idf_acf_block_render_callout_set( $block, $content = '', $is_preview = false ) {
    $context = Timber::context();
    $context['block'] = $block;
    $context['fields'] = get_fields();
    $context['is_preview'] = $is_preview;
    Timber::render('templates/blocks/callout-set.twig', $context);
}

function idf_acf_block_render_image_grid_with_text( $block, $content = '', $is_preview = false ) {
    $context = Timber::context();
    $context['block'] = $block;
    $context['fields'] = get_fields();
    $context['is_preview'] = $is_preview;
    Timber::render('templates/blocks/image-grid-with-text.twig', $context);
}

function idf_acf_block_render_video( $block, $content = '', $is_preview = false ) {
    $context = Timber::context();
    $context['block'] = $block;
    $context['fields'] = get_fields();
    $context['is_preview'] = $is_preview;
    Timber::render('templates/blocks/video.twig', $context);
}

function idf_acf_block_render_link_block( $block, $content = '', $is_preview = false ) {
    $context = Timber::context();
    $context['block'] = $block;
    $context['fields'] = get_fields();
    $context['is_preview'] = $is_preview;
    Timber::render('templates/blocks/link-block.twig', $context);
}

function idf_acf_block_render_external_news_story( $block, $content = '', $is_preview = false ) {
    $context = Timber::context();
    $context['block'] = $block;
    $context['fields'] = get_fields();
    $context['is_preview'] = $is_preview;
    Timber::render('templates/blocks/external-news-story.twig', $context);
}

function idf_acf_block_render_blockquote( $block, $content = '', $is_preview = false ) {
    $context = Timber::context();
    $context['block'] = $block;
    $context['fields'] = get_fields();
    $context['is_preview'] = $is_preview;
    Timber::render('templates/blocks/blockquote.twig', $context);
}

function idf_acf_block_render_cards( $block, $content = '', $is_preview = false ) {
    $context = Timber::context();
    $context['block'] = $block;
    $context['fields'] = get_fields();
    $context['is_preview'] = $is_preview;
    Timber::render('templates/blocks/cards.twig', $context);
}

function idf_acf_block_render_feature_with_large_image( $block, $content = '', $is_preview = false ) {
    $context = Timber::context();
    $context['block'] = $block;
    $context['fields'] = get_fields();
    $context['is_preview'] = $is_preview;
    Timber::render('templates/blocks/feature-with-large-image.twig', $context);
}

function idf_acf_block_render_callout_inset( $block, $content = '', $is_preview = false ) {
    $context = Timber::context();
    $context['block'] = $block;
    $context['fields'] = get_fields();
    $context['is_preview'] = $is_preview;
    Timber::render('templates/blocks/callout-inset.twig', $context);
}

function idf_acf_block_render_cta_small( $block, $content = '', $is_preview = false ) {
    $context = Timber::context();
    $context['block'] = $block;
    $context['fields'] = get_fields();
    $context['is_preview'] = $is_preview;
    Timber::render('templates/blocks/cta-small.twig', $context);
}

function idf_acf_block_render_cta_large( $block, $content = '', $is_preview = false ) {
    $context = Timber::context();
    $context['block'] = $block;
    $context['fields'] = get_fields();
    $context['is_preview'] = $is_preview;
    Timber::render('templates/blocks/cta-large.twig', $context);
}

function idf_acf_block_render_directory( $block, $content = '', $is_preview = false ) {
    $context = Timber::context();
    $context['block'] = $block;
    $context['fields'] = get_fields();
    $context['is_preview'] = $is_preview;
    Timber::render('templates/blocks/directory.twig', $context);
}

function idf_acf_block_render_featured_events( $block, $content = '', $is_preview = false ) {
    $context = Timber::context();
    $context['block'] = $block;
    $context['fields'] = get_fields();
    $context['is_preview'] = $is_preview;
    Timber::render('templates/blocks/featured-events.twig', $context);
}

function idf_acf_block_render_upcoming_events( $block, $content = '', $is_preview = false ) {
    $context = Timber::context();
    $context['block'] = $block;
    $context['fields'] = get_fields();
    $context['is_preview'] = $is_preview;
    Timber::render('templates/blocks/upcoming-events.twig', $context);
}

function idf_acf_block_render_featured_event_with_calendar( $block, $content = '', $is_preview = false ) {
    $context = Timber::context();
    $context['block'] = $block;
    $context['fields'] = get_fields();
    $context['is_preview'] = $is_preview;
    Timber::render('templates/blocks/featured-event-with-calendar.twig', $context);
}

function idf_acf_block_render_featured_news( $block, $content = '', $is_preview = false ) {
    $context = Timber::context();
    $context['block'] = $block;
    $context['fields'] = get_fields();
    $context['is_preview'] = $is_preview;
    Timber::render('templates/blocks/featured-news.twig', $context);
}

function idf_acf_block_render_recent_articles( $block, $content = '', $is_preview = false ) {
    $context = Timber::context();
    $context['block'] = $block;
    $context['fields'] = get_fields();
    $context['is_preview'] = $is_preview;
    $postCount = $context['fields']['news_stories'];
    if ($context['fields']['feed_style']['value'] == "syndicated") {
        $order = $context['fields']['feed_order']['value'];
        $cat = $context['fields']['feed_category'];
        $tag = $context['fields']['feed_tags'];
		$argh = 'post_type=post&numberposts='.$postCount.'&category='.$cat.'&tag_id='.$tag.'&orderby=date&order='.$order.'';
        $context['post_count'] = intval($postCount);
		$context['recent_articles'] = Timber::get_posts($argh); // uses wp_query format.
	}

    Timber::render('templates/blocks/recent-articles.twig', $context);
}

function idf_acf_block_render_news_stories( $block, $content = '', $is_preview = false ) {
    $context = Timber::context();
    $context['block'] = $block;
    $context['fields'] = get_fields();
    $context['is_preview'] = $is_preview;
    Timber::render('templates/blocks/news-stories.twig', $context);
}



// Block Reset/Whitelist

add_filter('allowed_block_types_all', function($block_types, $editor_context) {
    // global $post;
    // $rents = get_post_ancestors( $post );

    $home_id = get_option( 'page_on_front');
    $id = $editor_context->post->ID;
    $type = $editor_context->post->post_type;
    $name = $editor_context->post->post_name;
    $rent = $editor_context->post->post_parent;

    $landingblocks = [
        'acf/landing-hero',
        'acf/text-content',
        'acf/button-set',
        'acf/link-set',
        'acf/multi-column-content',
        'acf/carousel',
        'acf/full-width-image',
        'acf/tables',
        'acf/accordion',
        'acf/statistic-with-text',
        'acf/callout-with-image',
        'acf/callout-set',
        'acf/image-grid-with-text',
        'acf/video',
        'acf/link-block',
        'acf/external-news-story',
        'acf/blockquote',
        'acf/cards',
        'acf/feature-with-large-image',
        'acf/callout-inset',
        'acf/cta-small',
        'acf/cta-large',
        'acf/directory',
        'core/table'
    ];
    $interiorblocks = [
        'acf/interior-hero',
        'acf/text-content',
        'acf/button-set',
        'acf/link-set',
        'acf/multi-column-content',
        'acf/carousel',
        'acf/full-width-image',
        'acf/tables',
        'acf/accordion',
        'acf/statistic-with-text',
        'acf/callout-with-image',
        'acf/callout-set',
        'acf/image-grid-with-text',
        'acf/video',
        'acf/link-block',
        'acf/external-news-story',
        'acf/blockquote',
        'acf/cards',
        'acf/feature-with-large-image',
        'acf/callout-inset',
        'acf/cta-small',
        'acf/cta-large',
        'acf/directory',
        'core/table'
    ];
    // $directoryblocks = [
    //     'acf/directory-hero'
    // ];
    // if($name === 'faculty') {
    //     return $directoryblocks;
    // }
    
    // if($home_id == $id) {
    //     return $landingblocks;
    // } else {
    //     return $interiorblocks;
    // }
   return $block_types;
}, 10, 2);

add_filter( 'render_block', 'wrap_gb_block', 10, 2 );

function wrap_gb_block( $block_content, $block ) {
    if ( 'core/paragraph' === $block['blockName'] || 'core/heading' === $block['blockName']) {
        $block_content = '<div class="paragraph-widget paragraph-widget--text-html"><div class="text-content">' . $block_content . '</div></div>';
    } elseif ( 'core/table' === $block['blockName'] ) {
        $block_content = '<div class="paragraph-widget paragraph-widget--table"><div class="iastate22-table" role="region" aria-labelledby="caption_one" tabindex="0">' . $block_content . '</div></div>';
    }
    return $block_content;
}