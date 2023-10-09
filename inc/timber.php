<?php

/**
 * This ensures that Timber is loaded and available as a PHP class.
 * If not, it gives an error message to help direct developers on where to activate
 */
if ( ! class_exists( 'Timber' ) ) {

	add_action(
		'admin_notices',
		function() {
			echo '<div class="error"><p>Timber not activated. Make sure you activate the plugin in <a href="' . esc_url( admin_url( 'plugins.php#timber' ) ) . '">' . esc_url( admin_url( 'plugins.php' ) ) . '</a></p></div>';
		}
	);

	add_filter(
		'template_include',
		function( $template ) {
			return get_stylesheet_directory() . '/static/no-timber.html';
		}
	);
	return;
}

/**
 * Sets the directories (inside your theme) to find .twig files
 */
Timber::$dirname = array( 'templates', 'views' );

/**
 * By default, Timber does NOT autoescape values. Want to enable Twig's autoescape?
 * No prob! Just set this value to true
 */
Timber::$autoescape = false;

if( function_exists('acf_add_options_page') ) {
	acf_add_options_page();
}

/**
 * We're going to configure our theme inside of a subclass of Timber\Site
 * You can move this to its own file and include here via php's include("MySite.php")
 */
class StarterSite extends Timber\Site {
	/** Add timber support. */
	public function __construct() {
		add_action( 'after_setup_theme', array( $this, 'theme_supports' ) );
		add_filter( 'timber/context', array( $this, 'add_to_context' ) );
		add_filter( 'timber/twig', array( $this, 'add_to_twig' ) );
		add_action( 'init', array( $this, 'register_post_types' ) );
		add_action( 'init', array( $this, 'register_taxonomies' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'load_styles' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'load_scripts' ) );
		parent::__construct();
	}

	/** This is where you add some context
	 *
	 * @param string $context context['this'] Being the Twig's {{ this }}.
	 */
	public function add_to_context( $context ) {
		$defaults = array(
			'menu'            => 'Main Menu'
		);
		if(has_nav_menu('main-menu')) {
			$context['main_menu']  = new TimberMenu("main-menu");
		}
		if(has_nav_menu('utility-menu')) {
			$context['utility_menu']  = new TimberMenu("utility-menu");
		}
		if(has_nav_menu('social-menu')) {
			$context['social_menu']  = new TimberMenu("social-menu");
		}
		if(has_nav_menu('footer-primary-menu')) {
			$context['footer_primary_menu']  = new TimberMenu("footer-primary-menu");
		}
		if(has_nav_menu('footer-secondary-menu')) {
			$context['footer_secondary_menu']  = new TimberMenu("footer-secondary-menu");
		}
		if(has_nav_menu('footer-utility-menu')) {
			$context['footer_utility_menu']  = new TimberMenu("footer-utility-menu");
		}
		if(has_nav_menu('subnav-side-menu')) {
			$context['subnav_side_menu']  = new TimberMenu("subnav-side-menu");
		}
		$context['options'] = get_fields('options');
		$context['site']  = $this;
		$profileTax = get_object_taxonomies(array('post_type' => 'profiles'), 'objects');
		$profileTerms = array();
		foreach($profileTax as $item) {
			$slug = $item->name;
			$terms = get_terms( array(
				'taxonomy' => $item->name,
				'hide_empty' => $slug,
			) );
			array_push($profileTerms, $terms);
		}
		$context['profile_tax'] = $profileTax;
		$context['profile_terms'] = $profileTerms;
		$context['post_tax'] = get_object_taxonomies(array('post_type' => 'post'), 'objects');
		$context['categories'] = Timber::get_terms('categories');
		$context['tags'] = Timber::get_terms('tags');
		if (function_exists('bcn_display')) {
			$context['breadcrumbs'] = bcn_display(true);
		}
		$next_four = array(
			'post_type' => 'events',
			'meta_key' => 'event_start_date_start_date',
			'posts_per_page' => -1,
			'orderby' => 'meta_value',
			'order' => 'ASC',
			'meta_query'=> array(
				array(
				'key' => 'event_start_date_start_date',
				'compare' => '>=',
				'value' => date("Ymd"),
				'type' => 'DATE'
				)
			)
		);
		$upcoming_events = array(
			'post_type' => 'events',
			'meta_key' => 'event_start_date_start_date',
			'posts_per_page' => -1,
			'orderby' => 'meta_value',
			'order' => 'ASC',
			'meta_query'=> array(
				array(
				'key' => 'event_start_date_start_date',
				'compare' => '>=',
				'value' => date("Ymd"),
				'type' => 'DATE'
				)
			)
		);
		$context['next_four'] = new Timber\PostQuery($next_four);
		$context['upcoming_events'] = new Timber\PostQuery($upcoming_events);
		return $context;
	}

	public function theme_supports() {
		// Add default posts and comments RSS feed links to head.
		add_theme_support( 'automatic-feed-links' );

		/*
		 * Let WordPress manage the document title.
		 * By adding theme support, we declare that this theme does not use a
		 * hard-coded <title> tag in the document head, and expect WordPress to
		 * provide it for us.
		 */
		add_theme_support( 'title-tag' );

		/*
		 * Enable support for Post Thumbnails on posts and pages.
		 *
		 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
		 */
		add_theme_support( 'post-thumbnails' );

		/*
		 * Switch default core markup for search form, comment form, and comments
		 * to output valid HTML5.
		 */
		add_theme_support(
			'html5',
			array(
				'comment-form',
				'comment-list',
				'gallery',
				'caption',
			)
		);

		/*
		 * Enable support for Post Formats.
		 *
		 * See: https://codex.wordpress.org/Post_Formats
		 */
		add_theme_support(
			'post-formats',
			array(
				'aside',
				'image',
				'video',
				'quote',
				'link',
				'gallery',
				'audio',
			)
		);

		add_theme_support( 'menus' );
	}

	/** This is where you can add your own functions to twig.
	 *
	 * @param \Twig\Environment $twig get extension.
	 */
	public function add_to_twig( $twig ) {
		$twig->addExtension( new Twig\Extension\StringLoaderExtension() );
		$twig->addFilter( new Timber\Twig_Filter( 'boolval', 'wp_validate_boolean') );

		$esc_attr = function( \Twig\Environment $env, $string ) {
			return esc_attr( $string );
		};
		$escaper_extension = class_exists( 'Twig\Extension\EscaperExtension' ) ?
			$twig->getExtension( 'Twig\Extension\EscaperExtension' ) :
			$twig->getExtension( 'Twig\Extension\CoreExtension' );
		$escaper_extension->setEscaper( 'esc_attr', $esc_attr );

		return $twig;
	}

	function load_styles() {
		wp_enqueue_style( 'screen', get_template_directory_uri() . '/vendor/iastate/frontend-component-library/build/css/index.css', array(), '0.0.1', 'screen' );
		wp_enqueue_style( 'wp_only', get_template_directory_uri() . '/wp_components/build/css/index.css', array(), '0.0.1', 'screen' );
		wp_enqueue_style( 'printcss', get_template_directory_uri() . '/vendor/iastate/frontend-component-library/build/css/print.css', array(), '0.0.1', 'print' );
		wp_enqueue_style( 'default', get_template_directory_uri() . '/style.css', array(), '' );
	}
	function load_scripts() {
		wp_enqueue_script( 'main', get_template_directory_uri() . '/vendor/iastate/frontend-component-library/build/js/index.js', array(), '1.0.0', true );
		wp_enqueue_script( 'fontawesome', 'https://kit.fontawesome.com/b658fac974.js', array(), '1.0.0', true );
	}

}
new StarterSite();
