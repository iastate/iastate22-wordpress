<?php

/**
 * Initialize Timber.
 */

use Timber\Menu as TimberMenu;
use Timber\Post as TimberPost;
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

add_action( 'acf/init', static function () {
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
 * Modified Timber post class
 */
class StarterPost extends TimberPost {
	/**
	 * {@inheritDoc}
	 *
	 * - Patched pagination's bad behavior of overriding global `$post` object with Timber class.
	 *
	 * @return array
	 */
	public function pagination() {
		global $post;
		$old_global_post = $post;

		$ret = parent::pagination();

		if ( ! $post instanceof WP_Post
		     && $old_global_post instanceof WP_Post ) {
			$post = $old_global_post;
		}

		return $ret;
	}
}

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
	 * @param array $context context['this'] Being the Twig's {{ this }}.
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

		$this->maybe_load_profile_taxonomy_context( $context );

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

		$twig->addFunction( new \Timber\Twig_Function(
				'is_post_expired',
				array( $this, 'is_post_expired' ),
				array( 'needs_context' => true, )
		) );
		$twig->addFunction( new \Timber\Twig_Function(
				'show_last_updated_date',
				array( $this, 'show_last_updated_date' ),
				array( 'needs_context' => true, )
		) );
		$twig->addFunction( new \Timber\Twig_Function(
				'show_post_author',
				array( $this, 'show_theme_post_author' ),
				array( 'needs_context' => true, )
		) );
		$twig->addFunction( new \Timber\Twig_Function(
				'theme_post_thumbnail',
				array( $this, 'get_theme_post_thumbnail' ),
				array( 'needs_context' => true, )
		) );
		$twig->addFunction( new \Timber\Twig_Function(
				'theme_post_preview',
				array( $this, 'get_theme_post_preview' ),
				array( 'needs_context' => true, )
		) );
		$twig->addFunction( new \Timber\Twig_Function(
				'acf_inline_text',
				array( $this, 'acf_inline_text_editing_wrapper' ),
				array(
						'needs_context' => true,
						'is_safe'       => true,
				)
		) );
		$twig->addFunction( new \Timber\Twig_Function(
				'acf_inline_toolbar',
				array( $this, 'acf_inline_toolbar_editing_wrapper' ),
				array(
						'needs_context' => true,
						'is_safe'       => true,
				)
		) );
		$twig->addFunction( new \Timber\Twig_Function(
				'media_image',
				array( $this, 'get_attachment_image_wrapper' ),
				array( 'needs_context' => false, )
		) );

		$esc_attr          = function ( Environment $env, $string ) {
			return esc_attr( $string );
		};
		$escaper_extension = class_exists( 'Twig\Extension\EscaperExtension' ) ?
				$twig->getExtension( 'Twig\Extension\EscaperExtension' ) :
				$twig->getExtension( 'Twig\Extension\CoreExtension' );
		$escaper_extension->setEscaper( 'esc_attr', $esc_attr );

		return $twig;
	}

	public function load_styles() {
		$version = $this->public_version_key();
		wp_enqueue_style( 'screen', get_template_directory_uri() . '/vendor/iastate/frontend-component-library/build/css/index.css', array(), $version, 'screen' );
		wp_enqueue_style( 'printcss', get_template_directory_uri() . '/vendor/iastate/frontend-component-library/build/css/print.css', array(), $version, 'print' );
		wp_enqueue_style( 'wp_only', get_template_directory_uri() . '/wp_components/build/css/index.css', array(), $version, 'screen' );
		wp_enqueue_style( 'default', get_template_directory_uri() . '/style.css', array(), $version, 'all' );
	}

	/**
	 * Get version key for public urls.
	 *
	 * Returns a random version string when WP_DEBUG is true.
	 *
	 * @return string
	 * @since 1.3.2
	 */
	public function public_version_key() {
		if ( defined( 'WP_DEBUG' ) && true === WP_DEBUG ) {
			return rawurlencode( uniqid( $this->theme->get( 'Version' ) . '-', false ) );
		}

		return rawurlencode( $this->theme->get( 'Version' ) );
	}

	public function load_scripts() {
		$version = $this->public_version_key();
		wp_enqueue_script( 'main', get_template_directory_uri() . '/vendor/iastate/frontend-component-library/build/js/index.js', array(), $version, true );
		wp_enqueue_script( 'fontawesome', 'https://kit.fontawesome.com/b658fac974.js', array(), '1.0.0', true );
	}

	/**
	 * Check if a post is past the expiration date.
	 *
	 * @param array $context The Timber context
	 * @param string $expiration [optional]
	 * <p>A date/time string. Valid formats are explained in {@link https://secure.php.net/manual/en/datetime.formats.php Date and Time Formats}.</p>
	 * @param \Timber\Post|null $post
	 *
	 * @return bool Returns true only if the post's modified date is earlier than the expiration date.
	 * @throws WP_Exception DateTime error messages fed through {@see wp_trigger_error}
	 * @since 1.3.0
	 */
	public function is_post_expired( $context, $expiration = '2 years ago', $post = null ) {
		if ( null === $post ) {
			if ( ! isset( $context['post'] ) ) {
				return false;
			}

			$post = $context['post'];
		}

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
	 * @param \Timber\Post|null $post
	 *
	 * @return bool
	 * @throws WP_Exception
	 * @since 1.3.2
	 */
	public function show_last_updated_date( $context, $post = null ) {
		if ( null === $post ) {
			if ( ! isset( $context['post'] ) ) {
				return false;
			}

			$post = $context['post'];
		}

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

	/**
	 * Determine if the author credits should be displayed.
	 *
	 * @param array $context The Timber context
	 * @param \Timber\Post|null $post
	 *
	 * @return bool
	 * @since 1.3.3
	 */
	public function show_theme_post_author( $context, $post = null ) {
		if ( null === $post ) {
			if ( ! isset( $context['post'] ) ) {
				return true;
			}

			$post = $context['post'];
		}

		if ( ! $post instanceof \Timber\Post ) {
			return true;
		}

		$show_author = $post->get_field( 'show_author' );

		if ( ! is_bool( $show_author ) ) {
			return true;
		}

		return $show_author;
	}

	/**
	 * Return either the post or theme's thumbnail
	 *
	 * @param array $context The Timber context
	 * @param \Timber\Post|null $post
	 *
	 * @return \Timber\Image|null
	 * @since 1.3.4
	 */
	public function get_theme_post_thumbnail( $context, $post = null ) {
		if ( null === $post ) {
			if ( ! isset( $context['post'] ) ) {
				return null;
			}

			$post = $context['post'];
		}

		if ( ! $post instanceof \Timber\Post ) {
			return null;
		}

		$thumbnail = $post->thumbnail();

		if ( $thumbnail instanceof \Timber\Image ) {
			if ( null === $thumbnail->file ) {
				return null;
			}

			return $thumbnail;
		}

		if ( $post->has_field( 'hero_image' ) ) {
			$thumbnail = $post->get_field( 'hero_image' );

			if ( isset( $thumbnail['ID'] ) ) {
				return new \Timber\Image( $thumbnail['ID'] );
			}
		}

		if ( 'profiles' === $post->type()->slug && $post->has_field( 'photo' ) ) {
			$thumbnail = $post->get_field( 'photo' );

			if ( isset( $thumbnail['ID'] ) ) {
				return new \Timber\Image( $thumbnail['ID'] );
			}
		}


		return null;
	}

	/**
	 * Return and configure the post preview if checks are passed
	 *
	 * @param array $context The Timber context
	 * @param \Timber\Post|null $post
	 *
	 * @return \Timber\PostPreview|null
	 * @since 1.3.4
	 */
	public function get_theme_post_preview( $context, $post = null ) {
		if ( null === $post ) {
			if ( ! isset( $context['post'] ) ) {
				return null;
			}

			$post = $context['post'];
		}

		if ( ! $post instanceof \Timber\Post ) {
			return null;
		}

		if ( ! isset( $context['options']['news_page_options'] ) ) {
			return null;
		}

		$preview_settings = $context['options']['news_page_options'];

		if ( isset( $preview_settings['show_preview'] ) && $preview_settings['show_preview'] ) {
			if ( empty( $post->post_excerpt ) ) {
				switch ( $post->type()->slug ) {
					case 'profiles':
						$first_name = $post->get_field( 'first_name' );
						$last_name  = $post->get_field( 'last_name' );

						$post->post_excerpt = sprintf(
								'%s',
								esc_html( $last_name . ', ' . $first_name ),
						);
						break;
					case 'events':
						$post->post_excerpt = $post->get_field( 'location' );
						break;
				}
			}

			$preview        = $post->preview();
			$preview_length = $preview_settings['preview_length'] ?? 50;
			$read_more      = ! empty( $preview_settings['read_more'] ) ? esc_html( $preview_settings['read_more'] ) : false;

			return $preview
					->length( (int) $preview_length )
					->force( false )
					->read_more( $read_more );
		}

		return null;
	}

	/**
	 * @param int|mixed $attachment_id
	 * @param string|array $attr
	 *
	 * @return string
	 */
	public function get_attachment_image_wrapper( $attachment_id, $options = [], $attr = '' ) {
		$html        = '';
		$size        = $options['size'] ?? 'large';
		$icon        = $options['icon'] ?? false;
		$placeholder = (bool) ( $options['placeholder'] ?? false );

		if ( ! is_scalar( $attachment_id ) ) {
			if ( is_array( $attachment_id ) ) {
				$attachment_id = $attachment_id['ID'] ?? null;
			}
			if ( $attachment_id instanceof \Timber\Image ) {
				$attachment_id = (int) $attachment_id->ID;
			}
		}

		if ( ! empty( $attachment_id ) ) {
			$html = wp_get_attachment_image( $attachment_id, $size, $icon, $attr );
		}

		if ( $placeholder && empty( $html ) ) {
			return sprintf( '<img class="bg-placeholder" src="%s" alt="Placeholder" decoding="async" loading="lazy">',
					esc_url( $this->theme->uri . '/img/placeholder.png' )
			);
		}

		return $html;
	}

	/**
	 * ACF text wrapper
	 *
	 * @return string
	 */
	public function acf_inline_text_editing_wrapper( $context, $field_name, $args = array() ) {
		if ( isset( $context['is_preview'] ) && true !== $context['is_preview'] ) {
			return '';
		}

		if ( ! function_exists( 'acf_inline_text_editing_attrs' ) ) {
			return '';
		}

		return acf_inline_text_editing_attrs( $field_name, $args );
	}

	/**
	 * ACF toolbar wrapper
	 *
	 * @return string
	 */
	public function acf_inline_toolbar_editing_wrapper( $context, $fields, $args = array() ) {
		if ( isset( $context['is_preview'] ) && true !== $context['is_preview'] ) {
			return '';
		}

		if ( ! function_exists( 'acf_inline_toolbar_editing_attrs' ) ) {
			return '';
		}

		if ( is_string( $fields ) ) {
			$fields = array( $fields );
		}

		return acf_inline_toolbar_editing_attrs( $fields, $args );
	}

	/**
	 * Load profile taxonomy into Timber context when conditions are met.
	 *
	 * @param &$context
	 *
	 * @return bool true if context has been loaded
	 */
	protected function maybe_load_profile_taxonomy_context( &$context ): bool {
		if ( true !== static::is_post_type_profile_active() ) {
			return false;
		}

		$queried_object = get_queried_object();

		// Search pages will be one of these 2 queried objects
		if ( ! $queried_object instanceof WP_Post_Type && ! $queried_object instanceof WP_Term ) {
			return false;
		}

		// Load when `profiles` are the Post Object Type
		if ( ( $queried_object instanceof WP_Post_Type ) && $queried_object->name !== 'profiles' ) {
			return false;
		}

		// Load when either associated taxonomies are the queried object
		if ( ( $queried_object instanceof WP_Term ) && ! in_array( $queried_object->taxonomy,
						array(
								'affiliation',
								'department'
						),
						true ) ) {
			return false;
		}

		$profileTerms = array();
		$profileTax   = get_object_taxonomies(
				array(
						'post_type' => 'profiles'
				),
				'objects'
		);

		foreach ( $profileTax as $item ) {
			$slug           = $item->name;
			$terms          = get_terms(
					array(
							'taxonomy'   => $item->name,
							'hide_empty' => $slug,
					)
			);
			$profileTerms[] = $terms;
		}

		$context['profile_tax']   = $profileTax;
		$context['profile_terms'] = $profileTerms;

		return true;
	}

	/**
	 * Alter WP query for Profile searching
	 *
	 * @param array $context
	 *
	 * @return bool true if context has been loaded
	 */
	public static function maybe_load_profile_search_context( array &$context ): bool {
		if ( true !== static::is_post_type_profile_active() ) {
			return false;
		}
		if ( get_query_var( 'post_type' ) !== 'profiles' ) {
			return false;
		}

		$search_letter = get_query_var( 'search_letter' );
		$paged         = get_query_var( 'paged', 1 );
		$s             = get_query_var( 's' );
		$tax_query     = array();
		$meta_query    = array();
		$param_array   = array();
		$tax_params    = array();

		foreach ( $_GET as $key => $value ) {
			if ( $key !== "post_type" && $key !== "search_letter" && $key !== "s" && strlen( $value ) ) {
				$param_array[] = [ $key, $value ];
			}
		}

		foreach ( $param_array as $tax ) {
			$tax_params[] = array(
					'taxonomy' => $tax[0],
					'field'    => 'slug',
					'terms'    => $tax[1],
			);
		}

		if ( count( $param_array ) > 0 ) {
			$tax_query = array(
					'relation' => 'AND',
					$tax_params
			);
		}

		if ( ! empty( $search_letter ) ) {
			$meta_query = array(
					array(
							'key'     => 'last_name',
							'value'   => "^[" . $search_letter . "]",
							'compare' => 'REGEXP'
					)
			);
		}

		$arr  = array(
				'post_type'  => 'profiles',
				'order'      => 'ASC',
				'orderby'    => array(
						'last_name_clause'  => 'ASC',
						'first_name_clause' => 'ASC',
				),
				'meta_key'   => 'last_name',
				'paged'      => $paged,
				's'          => $s,
				'tax_query'  => $tax_query,
				'meta_query' => array(
						'relation'          => 'AND',
						'last_name_clause'  => array(
								'key'     => 'last_name',
								'compare' => 'EXISTS'
						),
						'first_name_clause' => array(
								'key'     => 'first_name',
								'compare' => 'EXISTS'
						),
						$meta_query
				)
		);
		$argh = array(
				'post_type'      => 'profiles',
				'posts_per_page' => - 1,
				'order'          => 'DESC',
				'orderby'        => 'meta_value',
				'paged'          => $paged,
				's'              => $s,
				'tax_query'      => $tax_query,
				'meta_query'     => array(
						'relation'          => 'AND',
						'last_name_clause'  => array(
								'key'     => 'last_name',
								'compare' => 'EXISTS'
						),
						'first_name_clause' => array(
								'key'     => 'first_name',
								'compare' => 'EXISTS'
						),
						$meta_query
				)
		);

		$context['posts']    = new PostQuery( $arr );
		$context['allposts'] = new PostQuery( $argh );

		// This works for the search filter, but not the search query or Taxonomies.
		return true;
	}

	/**
	 * Configure title and templates for archive pages.
	 *
	 * @param array &$context
	 * @param array &$templates
	 */
	public static function set_archive_title_context( array &$context, array &$templates ): void {
		$context['title'] = 'Archive';

		if ( is_day() ) {
			$context['title'] = 'Archive: ' . get_the_date( 'D M Y' );
		} elseif ( is_month() ) {
			$context['title'] = 'Archive: ' . get_the_date( 'M Y' );
		} elseif ( is_year() ) {
			$context['title'] = 'Archive: ' . get_the_date( 'Y' );
		} elseif ( is_tag() ) {
			$context['title'] = single_tag_title( '', false );
		} elseif ( is_category() ) {
			$context['title'] = single_cat_title( '', false );
			array_unshift( $templates, 'archive-' . get_query_var( 'cat' ) . '.twig' );
		} elseif ( is_post_type_archive() ) {
			$context['title'] = post_type_archive_title( '', false );
			array_unshift( $templates, 'archive-' . get_post_type() . '.twig' );
		}

	}

	/**
	 * Check if the Profile post type is active.
	 *
	 * @return bool|null true if post type is active, null is returned if called before settings are loaded
	 */
	public static function is_post_type_profile_active(): ?bool {
		if ( did_action( 'init' ) < 1 ) {
			return null;
		}

		return true === get_field( 'profiles_enabled', 'options' );
	}

	/**
	 * Check if the Event post type is active.
	 *
	 * @return bool|null true if post type is active, null is returned if called before settings are loaded
	 */
	public static function is_post_type_events_active(): ?bool {
		if ( did_action( 'init' ) < 1 ) {
			return null;
		}

		$acf_event_option = get_field( 'events_options', 'options' );

		if ( ! is_array( $acf_event_option ) ) {
			return false;
		}

		return (bool) ( $acf_event_option['enabled'] ?? false );
	}
}

new StarterSite();
