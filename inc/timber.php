<?php

/**
 * Initialize Timber.
 */

use Timber\Menu as TimberMenu;
use Timber\PostQuery;
use Timber\Site as TimberSite;
use Timber\Timber;
use Twig\Environment;
use Twig\Extension\StringLoaderExtension;

$composer_autoload = get_template_directory() . '/vendor/autoload.php';
if ( file_exists( $composer_autoload ) ) {
	require_once $composer_autoload;
	$timber = new Timber();
}

/**
 * This ensures that Timber is loaded and available as a PHP class.
 * If not, it gives an error message to help direct developers on where to activate
 */
if ( iastate22_wordpress_is_environment_compatible() !== true ) {
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

add_action( 'acf/init', function () {
	if ( function_exists( 'acf_add_options_page' ) ) {
		acf_add_options_page(
				array(
						'page_title' => __( 'Theme General Settings' ),
						'menu_title' => __( 'Options' ),
						'menu_slug'  => 'acf-options',
						'capability' => 'manage_options',
						'autoload'   => true,
				)
		);
	}
} );

/**
 * We're going to configure our theme inside a subclass of Timber\Site
 * You can move this to its own file and include here via php's include("MySite.php")
 */
class StarterSite extends TimberSite {
	/** Add timber support. */
	public function __construct() {
		add_action( 'after_setup_theme', array( $this, 'theme_supports' ) );
		add_filter( 'timber/context', array( $this, 'add_to_context' ) );
		add_filter( 'timber/twig', array( $this, 'add_to_twig' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'load_styles' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'load_scripts' ) );
		parent::__construct();
	}

	/** This is where you add some context
	 *
	 * @param string $context context['this'] Being the Twig's {{ this }}.
	 */
	public function add_to_context( array $context ) {
		if ( has_nav_menu( 'main-menu' ) ) {
			$context['main_menu'] = new TimberMenu( "main-menu" );
		}
		if ( has_nav_menu( 'utility-menu' ) ) {
			$context['utility_menu'] = new TimberMenu( "utility-menu" );
		}
		if ( has_nav_menu( 'social-menu' ) ) {
			$context['social_menu'] = new TimberMenu( "social-menu" );
		}
		if ( has_nav_menu( 'footer-primary-menu' ) ) {
			$context['footer_primary_menu'] = new TimberMenu( "footer-primary-menu" );
		}
		if ( has_nav_menu( 'footer-secondary-menu' ) ) {
			$context['footer_secondary_menu'] = new TimberMenu( "footer-secondary-menu" );
		}
		if ( has_nav_menu( 'footer-utility-menu' ) ) {
			$context['footer_utility_menu'] = new TimberMenu( "footer-utility-menu" );
		}
		if ( has_nav_menu( 'subnav-side-menu' ) ) {
			$context['subnav_side_menu'] = new TimberMenu( "subnav-side-menu" );
		}
		$context['options'] = get_fields( 'options' );
		$context['site']    = $this;
		$profileTax         = get_object_taxonomies( array( 'post_type' => 'profiles' ), 'objects' );
		$profileTerms       = array();
		foreach ( $profileTax as $item ) {
			$slug  = $item->name;
			$terms = get_terms( array(
				'taxonomy'   => $item->name,
				'hide_empty' => $slug,
			) );
			$profileTerms[] = $terms;
		}
		$context['profile_tax']   = $profileTax;
		$context['profile_terms'] = $profileTerms;
		$context['post_tax']      = get_object_taxonomies( array( 'post_type' => 'post' ), 'objects' );
		$context['categories']    = Timber::get_terms( 'categories' );
		$context['tags']          = Timber::get_terms( 'tags' );
		if ( function_exists( 'bcn_display' ) ) {
			$context['breadcrumbs'] = bcn_display( true );
		}
		$next_four       = array(
			'post_type'      => 'events',
			'meta_key'       => 'event_start_date_start_date',
			'posts_per_page' => - 1,
			'orderby'        => 'meta_value',
			'order'          => 'ASC',
			'meta_query'     => array(
				array(
					'key'     => 'event_start_date_start_date',
					'compare' => '>=',
					'value'   => date( "Ymd" ),
					'type'    => 'DATE'
				)
			)
		);
		$upcoming_events = array(
			'post_type'      => 'events',
			'meta_key'       => 'event_start_date_start_date',
			'posts_per_page' => - 1,
			'orderby'        => 'meta_value',
			'order'          => 'ASC',
			'meta_query'     => array(
				array(
					'key'     => 'event_start_date_start_date',
					'compare' => '>=',
					'value'   => date( "Ymd" ),
					'type'    => 'DATE'
				)
			)
		);

		$context['next_four']       = new PostQuery( $next_four );
		$context['upcoming_events'] = new PostQuery( $upcoming_events );

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
	 * @param Environment $twig get extension.
	 *
	 * @throws \Twig\Error\RuntimeError
*/
	public function add_to_twig( $twig ) {
		$twig->addExtension( new StringLoaderExtension() );
		$twig->addFilter( new \Timber\Twig_Filter( 'boolval', 'wp_validate_boolean' ) );

		$twig->addFunction(
				new \Timber\Twig_Function(
						'is_post_expired',
						array( $this, 'is_post_expired' ),
						array(
								'needs_context' => true,
						)
				)
		);

		$twig->addFunction(
				new \Timber\Twig_Function(
						'show_last_updated_date',
						array( $this, 'show_last_updated_date' ),
						array(
								'needs_context' => true,
						)
				)
		);

		$esc_attr = function ( Environment $env, $string ) {
			return esc_attr( $string );
		};
		$escaper_extension = class_exists( 'Twig\Extension\EscaperExtension' ) ?
			$twig->getExtension( 'Twig\Extension\EscaperExtension' ) :
			$twig->getExtension( 'Twig\Extension\CoreExtension' );
		$escaper_extension->setEscaper( 'esc_attr', $esc_attr );

		return $twig;
	}

	function load_styles() {
		$version = $this->public_version_key();
		wp_enqueue_style( 'screen', get_template_directory_uri() . '/vendor/iastate/frontend-component-library/build/css/index.css', array(), $version, 'screen' );
		wp_enqueue_style( 'printcss', get_template_directory_uri() . '/vendor/iastate/frontend-component-library/build/css/print.css', array(), $version, 'print' );
		wp_enqueue_style( 'wp_only', get_template_directory_uri() . '/wp_components/build/css/index.css', array(), $version, 'screen' );
		wp_enqueue_style( 'default', get_template_directory_uri() . '/style.css', array(), $version, 'all' );
	}

	function load_scripts() {
		$version = $this->public_version_key();
		wp_enqueue_script( 'main', get_template_directory_uri() . '/vendor/iastate/frontend-component-library/build/js/index.js', array(), $version, true );
		wp_enqueue_script( 'fontawesome', 'https://kit.fontawesome.com/b658fac974.js', array(), '1.0.0', true );
	}

	/**
	 * Get version key for public urls.
	 *
	 * Returns a random version string when WP_DEBUG is true.
	 *
	 * @return string
	 * @since 1.3.2
	 */
	function public_version_key() {
		if ( defined( 'WP_DEBUG' ) && true === WP_DEBUG ) {
			return rawurlencode( uniqid( $this->theme->get( 'Version' ) . '-', false ) );
		}

		return rawurlencode( $this->theme->get( 'Version' ) );
	}

	/**
	 * Check if a post is past the expiration date.
	 *
	 * @param array $context The Timber context
	 * @param string $expiration [optional]
	 * <p>A date/time string. Valid formats are explained in {@link https://secure.php.net/manual/en/datetime.formats.php Date and Time Formats}.</p>
	 *
	 * @return bool Returns true only if the post's modified date is earlier than the expiration date.
	 * @throws WP_Exception DateTime error messages fed through {@see wp_trigger_error}
	 * @since 1.3.0
	 */
	function is_post_expired( $context, $expiration = '2 years ago' ) {
		if ( ! isset( $context['post'] ) ) {
			return false;
		}

		$post = $context['post'];

		if ( ! $post instanceof \Timber\Post ) {
			return false;
		}

		// only test post/news post types
		if ( $post->post_type !== 'post' ) {
			return false;
		}

		if ( $post->has_field( 'archive_banner' ) ) {
			$archive_banner = $post->get_field( 'archive_banner' );

			if ( isset( $archive_banner['visibility'] ) ) {
				switch ( $archive_banner['visibility'] ) {
					case 'on':
						return true;
					case 'off':
						return false;
					case 'default':
					default:
						break;
				}

			}
		}

		try {
			$post_date       = new DateTimeImmutable( $post->date( DateTimeInterface::RFC822 ) );
			$expiration_date = new DateTimeImmutable( $expiration );
		} catch ( \Exception $e ) {
			wp_trigger_error( __FUNCTION__, $e->getMessage(), E_USER_ERROR );

			return false;
		}

		return $expiration_date > $post_date;
	}

	/**
	 * Determine if the modified_date should be displayed.
	 *
	 * @param array $context The Timber context
	 *
	 * @return bool
	 * @throws WP_Exception
	 * @since 1.3.2
	 */
	function show_last_updated_date( $context ) {
		if ( ! isset( $context['post'] ) ) {
			return true;
		}

		$post = $context['post'];

		if ( ! $post instanceof \Timber\Post ) {
			return true;
		}

		$post_date     = $post->date( 'Y-m-d' );
		$modified_date = $post->modified_date( 'Y-m-d' );

		// Don't show if modified on the same day
		if ( $post_date === $modified_date ) {
			return false;
		}

		try {
			$post_datetime     = new DateTimeImmutable( $post->date( DateTimeInterface::RFC822 ) );
			$modified_datetime = new DateTimeImmutable( $post->modified_date( DateTimeInterface::RFC822 ) );
		} catch ( \Exception $e ) {
			wp_trigger_error( __FUNCTION__, $e->getMessage(), E_USER_ERROR );

			return true;
		}

		// Don't show if published date is in the future
		if ( $modified_datetime < $post_datetime ) {
			return false;
		}

		// Manual override
		if ( $post->has_field( 'last_updated_visibility' ) &&
		     true === $post->get_field( 'last_updated_visibility' ) ) {
			return false;
		}

		return true;
	}
}

new StarterSite();
