<?php
/**
 * @package Yoast\YoastCom
 */

namespace Yoast\YoastCom\Theme;

/**
 * Class Affiliate
 * @package Yoast\YoastCom\Theme
 *
 *
 * @see     edd_receipt_shortcode()
 *
 * Test with ?payment_key=[purchase_key] to load a specific purchase
 */

class Affiliate {

	const ACCOUNT_ID = '2747-a0800c';
	const GROUP = 'yoast';

	public function __construct() {
		add_action( 'yst_body_open', array( $this, 'output_affiliate_code' ) );
	}

	public function output_affiliate_code() {

		if ( $this->is_checkout_success() ) {
			$this->output_conversion_code();

			return;
		}

		get_template_part( 'html_includes/partials/affiliate-code', array( 'account_id' => self::ACCOUNT_ID ) );
	}

	/**
	 * Output the HTML needed to register conversion
	 */
	public function output_conversion_code() {
		global $edd_receipt_args;

		$template = 'html_includes/partials/affiliate-conversion';

		$payment     = new \EDD_Payment( $edd_receipt_args['id'] );
		$commissions = $this->get_commissions( $payment );

		$arguments = array(
			'account_id'  => self::ACCOUNT_ID,
			'order_id'    => $payment->ID,
			'commissions' => $commissions,
			'group'       => self::GROUP,
		);

		get_template_part( $template, $arguments );
	}

	/**
	 * @return bool
	 */
	private function is_checkout_success() {
		global $edd_receipt_args;

		// Call edd_receipt_shortcode to trigger $edd_receipt_args to be filled.
		edd_receipt_shortcode( array() );

		if ( ! edd_is_payment_complete( $edd_receipt_args['id'] ) ) {
			return false;
		}

		// EDD_settings success_page
		$success_page = edd_get_option( 'success_page', '' );

		return ( get_the_ID() === (int) $success_page );
	}

	/**
	 * Build a list of commissions
	 *
	 * Logic has been extracted from [edd]/templates/shortcode-receipt.php
	 *
	 * @param \EDD_Payment $payment Payment.
	 *
	 * @return array
	 */
	private function get_commissions( $payment ) {
		$output = array();

		$cart = edd_get_payment_meta_cart_details( $payment->ID, true );

		if ( ! $cart || array() === $cart ) {
			return $output;
		}

		$commissions = array();

		foreach ( $cart as $key => $item ) {
			$amount_override = null;

			$download_id = $item['id'];
			$price_id    = edd_get_cart_item_price_id( $item );

			/*
			 * Use the price of the option when it was present
			 */
			if ( edd_has_variable_prices( $download_id ) && ! is_null( $price_id ) ) {
				$amount_override = edd_get_price_option_amount( $download_id, $price_id );
			}

			/*
			 * Get payment meta
			 * Needed to get the discount from the order
			 */
			$payment_meta = $payment->get_meta();

			$price = edd_get_download_final_price( $download_id, $payment_meta['user_info'], $amount_override );

			/*
			 * Determine the commission type based on taxonomy or bundle
			 */
			if ( edd_is_bundled_product( $download_id ) ) {
				$commission_type = 'bundle';
			} else {
				$commission_type = 'other';

				/** @var \WP_Term[] $terms */
				$terms = get_the_terms( $item['id'], 'download_category' );
				if ( count( $terms ) === 1 ) {
					$commission_type = $terms[0]->slug;
				}
			}

			$commissions[ $commission_type ][] = $price;
		}

		/*
		 * Group by download_category taxonomy
		 * Format:
		 * sub_amount: {number}
		 * commission_type: '{taxonomy}'
		 */
		if ( $commissions ) {
			foreach ( $commissions as $commission => $prices ) {
				$output[] = array(
					'sub_amount'      => array_sum( $prices ),
					'commission_type' => $commission,
				);
			}
		}

		return $output;
	}
}
