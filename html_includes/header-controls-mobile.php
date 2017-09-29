<?php

namespace Yoast\YoastCom\Theme;

$cart = apply_filters( 'yoast:url', 'cart' );

?>
<div class="header-controls header-controls--mobile  hide-on-desktop">

	<a class="cart" href="<?php echo $cart ?>">
		<img src="<?php echo get_template_directory_uri() ?>/images/cart.svg">
		<span class="visuallyhidden focusable">Cart</span>
		<div class="num-items-container">
			<span class="num-items"></span>
		</div>
	</a>

	<a href="#mobile-search" data-toggle="data-show-mobile-search" data-hide-on-active="data-show-mobile-nav"><span
			class="visuallyhidden focusable"><?php _e( 'Search', 'yoastcom' ); ?></span><span
			class="text-icon">&#xf002;</span></a>

	<button data-toggle="data-show-mobile-nav" data-hide-on-active="data-show-mobile-search" class="button--naked" id="mobile-show-nav"><span
			class="visuallyhidden focusable"><?php _e( 'Navigation', 'yoastcom' ); ?></span><span class="text-icon">&#xf0c9;</span>
	</button>
</div>
