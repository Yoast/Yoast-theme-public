<?php


namespace Yoast\YoastCom\Theme;


class Shortcodes {

	protected $show_read_more = true;

	/**
	 * Adds the themes shortcodes
	 */
	public function add_shortcodes() {
		add_shortcode( 'announcement', array( $this, 'announcement' ) );
		add_shortcode( 'aside', array( $this, 'sidebar_content' ) );
		add_shortcode( 'banner', array( $this, 'banner' ) );
		add_shortcode( 'bundle', array( $this, 'bundle' ) );
		add_shortcode( 'ebook-banner', array( $this, 'ebook_banner' ) );
		add_shortcode( 'featured-image', array( $this, 'featured_image' ) );
		add_shortcode( 'featured-img', array( $this, 'featured_image' ) );
		add_shortcode( 'pdf-button', array( $this, 'pdf_button' ) );
		add_shortcode( 'plugin-info', array( $this, 'plugin_info' ) );
		add_shortcode( 'plugin-cta', array( $this, 'plugin_cta' ) );
		add_shortcode( 'plugin-stats', array( $this, 'plugin_stats' ) );
		add_shortcode( 'readmore', array( $this, 'read_more_link' ) );
		add_shortcode( 'sidebar-content', array( $this, 'sidebar_content' ) );
		add_shortcode( 'sidebar-payment', array( $this, 'sidebar_payment_method' ) );
		add_shortcode( 'testimonial', array( $this, 'testimonial' ) );
		add_shortcode( 'yst_review_box', array( $this, 'review_box' ) );

		// Deprecated shortcodes.
		add_shortcode( 'box', array( $this, 'deprecate_box' ) );
		add_shortcode( 'download_button', array( $this, 'deprecated_download_button' ) );
		add_shortcode( 'support', array( $this, 'support' ) );

		add_shortcode( 'llms_take_quiz', array( $this, 'llms_complete_lesson' ) );

		add_action( 'instant_articles_before_transform_post', function() {
			$this->set_show_read_more( false );
		});

		add_action( 'instant_articles_after_transform_post', function() {
			$this->set_show_read_more( true );
		});
	}

	/**
	 * Handler for the box shortcode, outputs nothing except a deprecation notice.
	 */
	public function deprecate_box() {
		return '<!-- The "box" shortcode is deprecated -->';
	}

	/**
	 * Handler for the box shortcode, outputs nothing except a deprecation notice.
	 */
	public function deprecated_download_button() {
		return '<!-- The "download_button" shortcode is deprecated -->';
	}

	/**
	 * Place the featured image at the shortcode location.
	 *
	 * @return string
	 */
	public function featured_image() {
		if ( has_post_thumbnail() ) {
			return get_the_post_thumbnail();
		}

		return '';
	}

	/**
	 * Handler for the support shortcode. With this shortcode you used to be able to make a grid of boxes with content
	 * in them.
	 *
	 * @param array $args The shortcode arguments.
	 * @param string $content The content inside the shortcode.
	 *
	 * @return string HTML to output.
	 */
	public function support( $args, $content ) {

		$class = '';
		if ( isset( $args['class'] ) ) {
			$class = $args['class'];
		}

		$name = 'Add a name attribute!';
		if ( isset( $args['name'] ) ) {
			$name = $args['name'];
		}

		return get_template_part( 'html_includes/shortcodes/support', array(
			'return'  => true,
			'class'   => $class,
			'name'    => $name,
			'content' => $content,
		) );
	}

	/**
	 * Handler for the plugin-info shortcode. Outputs some information about the plugin
	 *
	 * @return string
	 */
	public function plugin_info() {
		return
			$this->get_break_out_content()
			. get_template_part( 'html_includes/shortcodes/plugin-info', array( 'return' => true ) )
			. '<hr>'
			. $this->get_content_restart();
	}

	/**
	 * Returns HTML tags to break out of the content container to allow for full width shortcodes
	 *
	 * @return string
	 */
	private function get_break_out_content() {
		$break_out_content = '';

		if ( is_singular( array( 'yoast_plugins', 'yoast_courses' ) ) ) {
			$break_out_content = '</section></div>';
		} elseif ( is_singular() ) {
			$break_out_content = '</div></article>';
		}

		return $break_out_content;
	}

	/**
	 * Restart the content area to make sure content after the shortcode is still valid
	 */
	private function get_content_restart() {
		$restart_content = '';

		if ( is_singular( array( 'yoast_plugins', 'yoast_courses' ) ) ) {
			$restart_content = '<div class="row island iceberg"><section class="content">';
		} elseif ( is_singular() ) {
			$restart_content = '<article class="row"><div class="content">';
		}

		return $restart_content;
	}

	/**
	 * Handler for the plugin-cta shortcode. Outputs the buttons to buy or download the plugin.
	 *
	 * @return string
	 */
	public function plugin_cta() {
		return get_template_part( 'html_includes/shortcodes/plugin-cta', array( 'return' => true ) );
	}

	/**
	 * Handler for the bundle shortcode
	 *
	 * @return string
	 */
	public function bundle( $args ) {
		$heading = __( 'Bundle products and save money', 'yoastcom' );
		if ( isset( $args['heading'] ) ) {
			$heading = $args['heading'];
		}

		$bundles = array();
		if ( isset( $args['bundles'] ) ) {
			$bundles = explode( ',', $args['bundles'] );
		}

		if ( ! count( $bundles ) > 0 ) {
			return '<p><strong>You need to define the bundles to show using the <code>bundles</code> attribute, comma separated by ID, like this: <code>[bundle bundles="1,2"]</code>.</strong></p>';
		}

		$out = $this->get_break_out_content();
		$out .= get_template_part( 'html_includes/shortcodes/bundle', array(
				'bundles' => $bundles,
				'heading' => $heading,
				'return'  => true,
			)
		);
		$out .= $this->get_content_restart();

		return $out;
	}

	/**
	 * Handler for the plugin-stats shortcode. Outputs some statistics about the plugin
	 *
	 * @return string
	 */
	public function plugin_stats() {
		return $this->get_break_out_content() . get_template_part( 'html_includes/shortcodes/plugin-stats', array( 'return' => true ) ) . $this->get_content_restart();
	}

	/**
	 * Handler for the yst_review_box shortcode. Outputs a price comparison box for the site reviews.
	 *
	 * @return string
	 */
	public function review_box() {
		return $this->get_break_out_content() . '<section class="row">' . do_shortcode( post_meta( 'review-box' ) ) . '</section>' . $this->get_content_restart();
	}

	/**
	 * Handler for the testimonial shortcode. Outputs a neat testimonial box with the possibility of an image.
	 *
	 * @param array $atts The shortcode attributes.
	 * @param string $content The shortcode content.
	 *
	 * @return string
	 */
	public function testimonial( $atts, $content ) {
		$args = array(
			'return' => true,
		);

		if ( isset( $atts['image_url'] ) ) {
			$args['image_url'] = $atts['image_url'];
		}

		if ( isset( $atts['image_id'] ) ) {
			$args['image_id'] = $atts['image_id'];
		}

		if ( ! empty( $content ) ) {
			$args['testimonial'] = $content;
		}

		return $this->get_break_out_content() . get_template_part( 'html_includes/shortcodes/testimonial', $args ) . $this->get_content_restart();
	}

	/**
	 * Generates an accepted payment methods box in the sidebar
	 *
	 * @param array $atts
	 *
	 * @return string
	 */
	public function sidebar_payment_method( $atts ) {
		$atts         = wp_parse_args( $atts, array(
			'title' => 'Accepted payment methods',
		) );
		$atts['icon'] = false;

		$content = '<i class="fa fa-icon fa-cc-paypal" title="PayPal"></i>';
		$content .= '<i class="fa fa-icon fa-cc-mastercard" title="MasterCard"></i>';
		$content .= '<i class="fa fa-icon fa-cc-visa" title="VISA"></i>';
		$content .= '<i class="fa fa-icon fa-cc-amex" title="American Express"></i>';
		$content .= '<i class="fa fa-icon fa-bank-transfer" title="Bank Transfer"></i>';

		return $this->sidebar_content( $atts, $content );
	}

	/**
	 * Allows putting the sidebar content higher up along the content than it would normally happen if there is other break out content
	 *
	 * @param array $atts
	 * @param string $content
	 *
	 * @return string
	 */
	public function sidebar_content( $atts, $content = null ) {
		$atts = wp_parse_args( $atts, array(
			'id'    => '',
			'title' => false,
			'icon'  => false,
		) );

		$out = $this->get_break_out_body();
		$out .= '<section class="alignright extra" id="' . $atts['id'] . '">';
		if ( $atts['title'] ) {
			if ( $atts['icon'] ) {
				$atts['title'] = '<i class="fa fa-' . $atts['icon'] . '"></i> ' . $atts['title'];
			}
			$out .= '<h4>' . $atts['title'] . '</h4>';
		}
		if ( ! is_null( $content ) ) {
			$content = trim( $content );
		}

		if ( '' === $content ) {
			$content = wp_kses_post( post_meta( 'sidebar_content' ) );
		}

		// Prevent infinite loops:
		remove_shortcode( 'aside' );
		remove_shortcode( 'sidebar-content' );

		$content = do_shortcode( shortcode_unautop( wpautop( $content ) ) );

		// Re-attach shortcodes.
		add_shortcode( 'aside', array( $this, 'sidebar_content' ) );
		add_shortcode( 'sidebar-content', array( $this, 'sidebar_content' ) );

		// Remove empty paragraphs.
		$content = str_replace( '<p></p>', '', $content );

		$out .= $content;
		$out .= '</section>';
		$out .= $this->get_restart_body();

		return $out;
	}

	/**
	 * Break out of the article body
	 *
	 * @return string
	 */
	public function get_break_out_body() {
		$break_out_content = '';

		if ( is_singular( array( 'yoast_plugins', 'yoast_courses' ) ) ) {
			$break_out_content = '</section>';
		} elseif ( is_page() || is_singular() ) {
			$break_out_content = '</div>';
		}

		return $break_out_content;
	}

	/**
	 * Restart the article body
	 *
	 * @return string
	 */
	public function get_restart_body() {
		$restart_content = '';

		if ( is_singular( array( 'yoast_plugins', 'yoast_courses' ) ) ) {
			$restart_content = '<section class="content">';
		} elseif ( is_page() || is_singular() ) {
			$restart_content = '<div class="content">';
		}

		return $restart_content;
	}

	/**
	 * Generates a banner for eBooks
	 *
	 * @param array $atts
	 *
	 * @return string
	 */
	public function ebook_banner( $atts ) {
		if ( ! isset( $atts['book'] ) ) {
			$atts['book'] = 'content-seo';
		}

		switch ( $atts['book'] ) {
			case 'content-seo':
				$atts['text'] = 'Want to learn more about content-writing, keyword research, and creating a good site structure? Get our Content SEO eBook &raquo;';
				$atts['url']  = apply_filters( 'yoast:url', 'ebook_content-seo' );
				break;

			case 'ux-conversion':
				$atts['text'] = 'Want to improve your site\'s user experience and conversion? Get our eBook: UX & Conversion from a Holistic SEO perspective &raquo;';
				$atts['url']  = apply_filters( 'yoast:url', 'ebook_conversion-seo' );
				break;
		}

		$args = wp_parse_args( $atts, array(
			'banner'    => 'academy',
			'icon'      => 'book',
			'class'     => 'announcement--pointer',
			'no-sticky' => true,
			'return'    => true,
		) );

		return $this->get_break_out_content() . get_template_part( 'html_includes/partials/announcement', $args ) . $this->get_content_restart();
	}

	/**
	 * Handler for the announcement shortcode.
	 *
	 * @param array $atts The shortcode attributes.
	 *
	 * @return string The content to output on the page.
	 */
	public function announcement( $atts ) {

		$args = wp_parse_args( $atts, array(
			'banner'    => 'academy',
			'text'      => '',
			'url'       => '',
			'no-sticky' => true,
			'return'    => true,
		) );

		$template = 'html_includes/partials/fullbanner';
		if ( ! empty( $args['text'] ) && ! empty( $args['url'] ) ) {
			$template = 'html_includes/partials/banner-announcement';
		}

		return $this->get_break_out_content() . get_template_part( $template, $args ) . $this->get_content_restart();
	}

	/**
	 * Handler for the banner shortcode.
	 *
	 * @param array $atts The shortcode attributes.
	 *
	 * @return string The content to output on the page.
	 */
	public function banner( $atts ) {

		$args = wp_parse_args( $atts, array(
			'text'   => '',
			'url'    => '',
			'icon'   => '',
			'class'  => 'announcement--pointer',
			'return' => true,
		) );

		return $this->get_break_out_content() . get_template_part( 'html_includes/partials/announcement', $args ) . $this->get_content_restart();
	}

	/**
	 * Handler for the PDF button shortcode
	 *
	 * @param array $atts The shortcode attributes
	 *
	 * @return string The content to output on the page.
	 */
	public function pdf_button( $atts ) {
		$args = wp_parse_args( $atts, array(
			'icon' => 'file-pdf-o',
			'text' => 'Read this PDF',
			'url'  => '',
		) );

		return '<p><a target="_blank" class="button default" href="' . esc_url( $args['url'] ) . '"><i class="fa fa-' . esc_attr( $args['icon'] ) . '"></i>' . esc_html( $args['text'] ) . '</a></p>';
	}

	/**
	 * Handler for the read more link shortcode
	 *
	 * @param array $atts The shortcode attributes
	 * @param string $content
	 *
	 * @return string The content to output on the page.
	 */
	public function read_more_link( $atts, $content ) {
		if ( ! $this->show_read_more() ) {
			return '';
		}

		$args = wp_parse_args( $atts, array(
			'url'    => '',
			'prefix' => '',
		) );

		if ( $args['url'] === '' || $content === '' ) {
			return '';
		}

		if ( $args['prefix'] === '' ) {
			global $readmore_counter;
			if ( ! isset( $readmore_counter ) || $readmore_counter > 2 ) {
				$readmore_counter = 0;
			}

			$prefixes       = array(
				'Read more',
				'Keep reading',
				'Read on',
				'Keep on reading',
			);
			$args['prefix'] = $prefixes[ $readmore_counter ];

			$readmore_counter ++;
		}

		return '<p class="readmore"><a title="' . esc_attr( strip_tags( $content ) ) . '" data-prefix="' . esc_attr( $args['prefix'] ) . '" href="' . esc_attr( $args['url'] ) . '">' . $args['prefix'] . ': &lsquo;' . strip_tags( $content ) . '&rsquo; &raquo;</a></p>';
	}

	public function show_read_more() {
		return $this->show_read_more;
	}

	public function set_show_read_more( $enabled = true ) {
		$this->show_read_more = (bool) $enabled;
	}
}
