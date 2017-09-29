<?php

namespace Yoast\YoastCom\Theme;

class Domains {

	/**
	 * Gets the currently needed full URL for the specified type
	 *
	 * @param string $type Type of URL to get.
	 *
	 * @return null|string
	 */
	public function get_url( $type ) {

		switch ( $type ) {
			case 'website_review':
				$domain = apply_filters( 'yoast:domain', 'yoast.com' );

				return $domain . '/hire-us/website-review/';

			case 'ebooks_archive':
				$domain = apply_filters( 'yoast:domain', 'yoast.com' );

				return $domain . '/ebooks/';

			case 'ebook_content-seo':
				return $this->get_url( 'ebooks_archive' ) . 'content-seo/';

			case 'ebook_conversion-seo':
				return $this->get_url( 'ebooks_archive' ) . 'ux-conversion-seo/';

			case 'academy_overview':
				$domain = apply_filters( 'yoast:domain', 'yoast.com' );

				return $domain . '/academy/';

			case 'courses_archive':
				return $this->get_url( 'academy_overview' ) . 'courses';

			case 'plugin_overview':
				$domain = apply_filters( 'yoast:domain', 'yoast.com' );

				return $domain . '/wordpress/plugins/';

			case 'checkout':
				$domain = apply_filters( 'yoast:domain', 'yoast.com' );

				return $domain . '/checkout/';

			case 'cart':
				$domain = apply_filters( 'yoast:domain', 'yoast.com' );

				return $domain . '/cart/';

			case 'newsletter':
				$domain = apply_filters( 'yoast:domain', 'yoast.com' );

				return $domain . '/newsletter/';

			case 'shop':
				$domain = apply_filters( 'yoast:domain', 'yoast.com' );

				return $domain . '/shop/';

			case 'shop_counter_ajax':
				$domain = apply_filters( 'yoast:domain', 'my.yoast.com' );

				return $domain . '/wp-admin/admin-ajax.php';

		}

		return $type;
	}

	/**
	 * Converts a domain to the currently needed one
	 *
	 * Takes development environment into consideration.
	 *
	 * @param string $source Domain to retrieve
	 *
	 * @return mixed|string
	 */
	public function get_domain( $source ) {
		static $development;

		if ( ! isset( $development ) ) {
			$development = ( defined( 'YOAST_ENVIRONMENT' ) && YOAST_ENVIRONMENT === 'development' );
		}

		$domain = preg_replace( '~^https?:\/\/~', '', $source );
		$domain = rtrim( $domain, '/' );

		// Return with trailing slash if requested with it.
		$trailing_slash = ( substr( $source, - 1, 1 ) === '/' );

		$domains = ( $development ? $this->get_development_domains() : $this->get_production_domains() );
		if ( ! isset( $domains[ $domain ] ) ) {
			return $source;
		}

		$domain = $domains[ $domain ];

		// Add slash if it was presented.
		if ( $trailing_slash ) {
			$domain .= '/';
		}

		return $domain;
	}

	/**
	 * The development domain names
	 *
	 * @return array
	 */
	private function get_development_domains() {
		return [
			'yoast.com'     => defined( 'YOAST_COM_DEV_BASEURL' ) ? YOAST_COM_DEV_BASEURL : 'http://yoast.dev',
			'kb.yoast.com'  => defined( 'KB_YOAST_DEV_BASEURL' ) ? KB_YOAST_DEV_BASEURL : 'http://kb.yoast.dev',
			'my.yoast.com'  => defined( 'MY_YOAST_DEV_BASEURL' ) ? MY_YOAST_DEV_BASEURL : 'http://my.yoast.dev',
			'yoast.academy' => defined( 'YOAST_ACADEMY_DEV_BASEURL' ) ? YOAST_ACADEMY_DEV_BASEURL : 'http://yoast.academy.dev',
		];
	}

	/**
	 * The production domain names
	 *
	 * @return array
	 */
	private function get_production_domains() {
		return [
			'yoast.com'     => 'https://yoast.com',
			'kb.yoast.com'  => 'https://kb.yoast.com',
			'my.yoast.com'  => 'https://my.yoast.com',
			'yoast.academy' => 'https://yoast.academy',
		];
	}
}
