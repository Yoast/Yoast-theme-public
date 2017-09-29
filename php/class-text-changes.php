<?php
/**
 * @package Yoast\YoastCom
 */

namespace Yoast\YoastCom\Theme;

/**
 * Adds filters to change default WordPress texts
 */
class Text_Changes {

	/**
	 * Class constructor
	 */
	public function __construct() {
		add_filter( 'get_the_archive_title', array( $this, 'filter_archive_title' ) );

		add_action( 'wp_editor_expand', array( $this, 'reinstate_editor_for_posts_page' ) );

		add_filter( 'oembed_result', array( $this, 'add_video_container' ), 10, 2 );

		add_filter( 'comment_form_defaults', array( $this, 'comment_form_labels' ) );

		add_filter( 'wpseo_breadcrumb_links', array( $this, 'filter_crumbs' ) );
	}

	/**
	 * Make sure we can edit the posts page text
	 */
	public function reinstate_editor_for_posts_page() {
		global $post;
		if ( $post->ID == get_option( 'page_for_posts' ) && $post->post_content === '' ) {
			$post->post_content = ' ';
		}
	}

	/**
	 * Filters the archive title
	 *
	 * @param string $title The previous archive title.
	 *
	 * @return string
	 */
	public function filter_archive_title( $title ) {
		if ( is_home() ) {
			if ( is_front_page() ) {
				$title = __( 'Blog', 'yoastcom' );
			}
			else {
				$title = get_the_title( get_option( 'page_for_posts' ) );
			}
		}
		if ( is_category() || is_tag() || is_tax() ) {
			$title = single_term_title( '', false );
		}

		if ( is_post_type_archive( 'yoast_dev_article' ) ) {
			$title = __( 'Dev Blog', 'yoastcom' );
		}

		if ( is_search() ) {
			$title = __( 'Search', 'yoastcom' );
		}

		if ( is_author() ) {
			$title = get_the_author();
		}

		return $title;
	}


	/**
	 * Adds responsive video container to youtube auto embed
	 *
	 * @param string $html The current youtube embed HTML.
	 * @param string $url The URL that has been auto embedded.
	 * @param array $args The auto embed arguments.
	 *
	 * @return string
	 */
	public function add_video_container( $html, $url ) {

		$providers = array( 'youtube', 'vimeo' );
		foreach ( $providers as $provider ) {
			if ( false !== strpos( $url, $provider ) ) {
				return '<div class="videowrapper">' . $html . '</div>';
			}
		}

		return $html;
	}

	/**
	 * Change the comment form labels to match our style
	 *
	 * @param array $defaults
	 *
	 * @return array
	 */
	public function comment_form_labels( $defaults ) {
		$defaults['title_reply']    = __( 'Leave a reply' );
		$defaults['title_reply_to'] = __( 'Leave a reply to %s' );
		$defaults['label_submit']   = __( 'Post comment', 'yoastcom' );

		return $defaults;
	}

	/**
	 * Filter the breadcrumbs for pages that have a parent but don't show it otherwise
	 *
	 * @param array $links
	 *
	 * @return array
	 */
	public function filter_crumbs( $links ) {
		$this->filter_crumbs_helper( $links, 'theme-plugins', 478759 );
		$this->filter_crumbs_helper( $links, 'theme-courses', 409369 );

		return $links;
	}

	/**
	 * Helper function to filter the breadcrumbs
	 *
	 * @param array $links
	 * @param string $page_theme
	 * @param string $page_id
	 *
	 * @return array
	 */
	private function filter_crumbs_helper( &$links, $page_theme, $page_id ) {
		static $theme;
		if ( ! isset( $theme ) ) {
			$theme = theme_object()->color->get_color_scheme();
		}

		if ( $theme == $page_theme ) {
			if ( isset( $links[1] ) ) {
				if ( isset( $links[1]['id'] ) && $links[1]['id'] === $page_id ) {
					return $links;
				}
			}
			$links = array_merge(
				array_slice( $links, 0, 1 ),
				array( array( 'id' => $page_id ) ),
				array_slice( $links, 1, count( $links ) )
			);
		}

		return $links;
	}
}
