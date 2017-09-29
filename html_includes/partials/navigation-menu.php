<?php

namespace Yoast\YoastCom\Theme;

if ( class_exists( 'Yoast\YoastCom\Menu\Menu_Structure' ) ) {

	/** @noinspection PhpUndefinedVariableInspection */
	$main_menu_items = $template_args['menu_data'];

	echo '<div id="yoast-main-menu">';
	echo '<nav role="navigation" aria-label="' . __( "Main menu", "yoastcom" ) .'">';

	get_template_part( 'html_includes/partials/navigation-menu-list', array(
		'menu_items' => $main_menu_items,
		'menu_type'  => 'main'
	) );

	echo '</ul>'; // main menu
	echo '</nav>'; // nav holder

	echo '</div>'; // main menu id
}
else {
	?>
	<nav role="navigation" class="sitenav sticky" data-sticky data-sticky-desktop aria-hidden="true">
		<?php wp_nav_menu( array(
			'theme_location'  => 'primary',
			'container_class' => 'mainnav',
			'walker'          => new Menu_Walker(),
		) );
		?>
	</nav>

	<nav role="navigation" class="sitenav sitenav--offcanvas">
		<?php wp_nav_menu( array(
			'theme_location'  => 'primary',
			'container_class' => 'mainnav',
			'walker'          => new Menu_Walker(),
		) );
		?>
	</nav>
	<?php
}
