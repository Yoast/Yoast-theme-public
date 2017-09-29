<?php
/**
 * @package Yoast\YoastCom
 */

namespace Yoast\YoastCom\Theme;

use Yoast\YoastCom\Core\WordPress\Settings\Hide_Comments;

/**
 * Adds yoast.com theme functionality
 */
class Theme {

	const VERSION = '5.0.0';

	/**
	 * @var Excerpt_Manager
	 */
	public $excerpt;

	/**
	 * @var Color_Scheme
	 */
	public $color;

	/**
	 * @var Shortcodes
	 */
	public $shortcodes;


	/**
	 * @var Yoast_Navigation
	 */
	public $navigation;

	/**
	 * Constructor. Adds WordPress hooks.
	 */
	public function __construct() {
		add_action( 'after_setup_theme', array( $this, 'after_setup_theme' ) );

		add_action( 'init', array( $this, 'register_assets' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_assets' ), 20 );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_styles' ), 20 );

		add_action( 'wp', [ $this, 'control_navigation'] );
		add_filter( 'body_class', [ $this, 'add_navigation_body_class' ] );

		add_action( 'widgets_init', array( $this, 'register_widgets' ) );

		add_action( 'yst_body_open', array( $this, 'add_google_tag_manager' ) );

		add_filter( 'site_icon_meta_tags', array( $this, 'site_icons' ) );

		add_filter( 'comments_template_query_args', array( $this, 'reverse_comments_order' ) );

		new Widget_Color_Setting();
		new Text_Changes();
		new Term();
		new Page_Academy_Settings();
		new Query();
		new Featured_Image();

		if ( is_admin() ) {
			$theme_settings = new Theme_Settings();
			$theme_settings->hooks();
		}

		$this->shortcodes = new Shortcodes();
		add_action( 'init', array( $this->shortcodes, 'add_shortcodes' ) );

		$yoast_domains = new Domains();
		add_filter( 'yoast:url', array( $yoast_domains, 'get_url' ) );
		add_filter( 'yoast:domain', array( $yoast_domains, 'get_domain' ) );

		$this->color      = new Color_Scheme();
		$this->page_type  = new Page_Menu_Type();
		$this->extra_head = new Extra_Head();
		$this->navigation = new Yoast_Navigation();

		$this->excerpt = new Excerpt_Manager();
		$this->excerpt->add_hooks();

		$this->content_width();

		do_action( 'yoast_after_theme_setup' );
	}

	/**
	 * Adds a body class when the navigation is disabled.
	 *
	 * @param array $classes List of body classes.
	 *
	 * @return array Extended list of body classes.
	 */
	public function add_navigation_body_class( $classes ) {
		if ( ! apply_filters( 'yoast_theme-show_navigation', true ) ) {
			$classes[] = 'no-navigation';
		}
		return $classes;
	}

	/**
	 * Adds a filter to remove the navigation if the current post has the setting enabled.
	 */
	public function control_navigation() {
		if ( 'on' === get_post_meta( get_queried_object_id(), 'hide_navigation', true ) ) {
			add_filter( 'yoast_theme-show_navigation', '__return_false' );
		}
	}

	/**
	 * Sets the global content width
	 */
	private function content_width() {
		global $content_width;
		if ( ! isset( $content_width ) ) {
			$content_width = 600;
		}
	}

	/**
	 * Reverse the comments order
	 *
	 * To display the most recent one on top
	 *
	 * @param array $args
	 *
	 * @return array
	 */
	public function reverse_comments_order( $args ) {
		$args['order'] = 'DESC';

		return $args;
	}

	/**
	 * @return string Color Scheme
	 */
	public function get_color_scheme() {
		return $this->color->get_color_scheme();
	}

	/**
	 * Registers all theme assets
	 */
	public function register_assets() {
		$dir = get_template_directory_uri();

		// Third party libraries.
		wp_register_style( 'chosen', $dir . '/css/includes/chosen.min.css', array(), '1.4.2' );
		wp_register_script( 'chosen', $dir . '/js/includes/chosen.jquery.min.js', array( 'jquery' ), '1.4.2', true );

		$this->register_asset( 'style', 'yoast-com', 'css/style.min.css', array() );

		$this->register_asset( 'script', 'yoast-com', 'js/yoast.js', array( 'jquery' ) );

		$this->register_asset( 'script', 'yoast-com-academy', 'js/academy.min.js', array(
			'jquery',
		) );

		$this->register_asset( 'script', 'jquery-modal', 'js/includes/jquery.modal.min.js', array( 'jquery' ) );
	}

	public function enqueue_styles() {
		wp_enqueue_style( 'open-sans', 'https://fonts.googleapis.com/css?family=Merriweather:300,700,300italic|Open+Sans:400italic,400,300' );
		wp_enqueue_style( 'font-awesome', 'https://maxcdn.bootstrapcdn.com/font-awesome/4.6.3/css/font-awesome.min.css' );
	}

	/**
	 * Registers a theme asset
	 *
	 * @param string $script_or_style Whether it is a script or a style.
	 * @param string $handle          The handle for this asset.
	 * @param string $file_path       The file path for this asset.
	 * @param array  $dependencies    The dependencies of this asset.
	 */
	private function register_asset( $script_or_style, $handle, $file_path, $dependencies = array() ) {
		$url           = trailingslashit( get_template_directory_uri() ) . $file_path;
		$global_path   = trailingslashit( get_template_directory() ) . $file_path;
		$last_modified = filemtime( $global_path );

		if ( 'style' === $script_or_style ) {
			wp_register_style( $handle, $url, $dependencies, $last_modified );
		} elseif ( 'script' === $script_or_style ) {
			wp_register_script( $handle, $url, $dependencies, $last_modified, true );
		}
	}

	/**
	 * Enqueues all theme assets
	 */
	public function enqueue_assets() {
		wp_enqueue_style( 'yoast-com' );
		wp_enqueue_script( 'yoast-com' );

		/**
		 * Apply hotjar tracking code if supplied by the child theme.
		 */
		$tracking_code = apply_filters( 'yoast_hotjar_tracking_code', null );
		if ( is_array( $tracking_code ) ) {
			wp_localize_script(
				'yoast-com',
				'yoast_hotjar',
				[
					'id' => $tracking_code['id'],
					'sv' => $tracking_code['sv']
				]
			);
		}


		if ( is_singular( 'yoast_plugins' ) ) {
			wp_enqueue_script( 'jquery-modal' );
		}


		if ( is_singular( array(
				'post',
				'yoast_dev_article'
			) ) && ! Hide_Comments::hide_comments() && comments_open()
		) {
			wp_enqueue_script( 'comment-reply' );
		}

		// Academy scripts.
		if ( is_singular( array( 'course', 'lesson', 'llms_quiz' ) ) ) {
			wp_enqueue_script( 'yoast-com-academy' );
			wp_enqueue_script( 'jquery-ui-sortable', false, array( 'jquery', 'jquery-ui' ) );

			wp_localize_script( 'yoast-com-academy', 'AcademyAjax', array( 'ajaxurl' => apply_filters( 'yoast:domain', 'https://yoast.academy' ) . '/wp-admin/admin-ajax.php' ) );
		}
	}

	/**
	 * Registers widgets
	 */
	public function register_widgets() {
		register_widget( __NAMESPACE__ . '\\Promo_Widget' );
		register_widget( __NAMESPACE__ . '\\Social_Widget' );
	}

	/**
	 * Adds the google tag manager to the body.
	 */
	public function add_google_tag_manager() {
		if ( function_exists( 'gtm4wp_the_gtm_tag' ) ) {
			gtm4wp_the_gtm_tag();
		}
	}

	/**
	 * Adds all theme support to WordPress
	 */
	public function after_setup_theme() {
		$this->register_menus();
		$this->register_sidebars();
		$this->register_theme_support();
		$this->inject_vwo_options();

		load_theme_textdomain( 'yoastcom', get_template_directory() . '/languages' );

		add_editor_style( get_template_directory_uri() . '/css/editor-style.css' );
	}

	/**
	 * Force add configuration options in VWO script.
	 */
	protected function inject_vwo_options() {
		global $clhf_header_script_async;

		// Because this script is global, we can modify it before it is rendered in `wp_head` at prio `1`.
		$clhf_header_script_async = str_replace( '<script type=\'text/javascript\'>', '<script type=\'text/javascript\'>' . PHP_EOL . '_vis_opt_check_segment = {"global" : true};', $clhf_header_script_async );
	}

	/**
	 * Adds several sizes of apple touch icon and a pinned tabs icon
	 *
	 * @param $meta_tags
	 *
	 * @return array
	 */
	public function site_icons( $meta_tags ) {
		// Apple touch icons
		$meta_tags[] = '<link rel="apple-touch-icon" href="' . get_template_directory_uri() . '/images/yoast-logo-icon-120x120.png" sizes="120x120">';
		$meta_tags[] = '<link rel="apple-touch-icon" href="' . get_template_directory_uri() . '/images/yoast-logo-icon-152x152.png" sizes="152x152">';
		$meta_tags[] = '<link rel="apple-touch-icon" href="' . get_template_directory_uri() . '/images/yoast-logo-icon-512x512.png" sizes="512x512">';

		// Mask icon for Safari pinned tabs
		$meta_tags[] = "<link rel='mask-icon' color='#a4286a' href='" . get_template_directory_uri() . "/images/yoast-logo-icon-black.svg'>";

		return $meta_tags;
	}

	/**
	 * Registers theme support, but also image sizes
	 */
	private function register_theme_support() {
		add_image_size( 'thumbnail-recent-articles', 250, 150 );

		add_theme_support( 'post-thumbnails' );
	}

	/**
	 * Registers the navigation menus
	 */
	private function register_menus() {
		register_nav_menus( array(
			'primary'   => __( 'Primary Navigation Menu', 'yoastcom' ),
			'secondary' => __( 'Secondary Navigation Menu', 'yoastcom' ),
		) );
	}

	/**
	 * Registers sidebars
	 */
	private function register_sidebars() {
		register_sidebars( 3, array(
			'name'          => __( 'Footer %d', 'yoastcom' ),
			'id'            => 'footer',
			'description'   => '',
			'class'         => '',
			'before_widget' => '<div id="widget-%1$s" class="widget promoblock arrowed-medium %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<span class="h4 widgettitle">',
			'after_title'   => '</span>',
		) );

		register_sidebars( 3, array(
			'name'          => __( 'Homepage Promo Block %d', 'yoastcom' ),
			'id'            => 'promo-homepage',
			'before_widget' => '<div id="widget-%1$s" class="widget %2$s">',
			'after_widget'  => '</div>',
		) );
	}

}
