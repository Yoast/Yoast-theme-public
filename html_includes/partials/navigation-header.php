<?php
namespace Yoast\YoastCom\Theme;

echo '<ul class="controls">';

$profile_button_text = apply_filters( 'yst_profile_button_text', __( 'Login', 'yoastcom' ) );
printf( '<li class="login"><a href="%s"><span class="fa fa-user"></span>%s</a></li>', esc_url( apply_filters( 'yoast:domain', 'my.yoast.com' ) ), esc_html( $profile_button_text ) );

echo '<li class="checkout"><a class="cart" href="' . apply_filters( 'yoast:url', 'cart' ) . '">';
echo '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 463.4 379.9"><style type="text/css"></style><path class="st0" d="M327.8 319.5c-16.7 0-30.2 13.6-30.2 30.2s13.6 30.2 30.2 30.2 30.2-13.6 30.2-30.2S344.4 319.5 327.8 319.5z"/><path class="st0" d="M173.8 319.5c-16.7 0-30.2 13.6-30.2 30.2s13.6 30.2 30.2 30.2 30.2-13.6 30.2-30.2S190.5 319.5 173.8 319.5z"/><polygon class="st0" points="463.4 59.4 416.3 59.4 370.2 185 145.6 185 83.1 0 0 0 0 38 48.6 38 139.1 302 354 302 354 267 173.6 267 161.3 231 401.6 231 "/></svg>';
echo '<span>' . esc_html( __( 'Cart', 'yoastcom' ) ) . '</span>';
echo '<div class="num-items-container"><span class="num-items">' . apply_filters( 'yoastcom/items_in_cart_count', '' ) . '</span></div>';
echo '</a></li>';

// Only for Yoast.com & my.yoast.com:
if ( class_exists( 'Yoast\YoastCom\VisitorCurrency\Currency_Controller' ) ) {
	get_template_part( 'html_includes/partials/navigation-currency-switcher', Checkout_HTML::get_currency_switch_template_arguments() );
}

echo '</ul>';
