<?php
/**
 * @package Yoast\YoastCom
 *
 * Theme functions.php
 */

namespace Yoast\YoastCom\Theme;

require_once __DIR__ . '/php/functions-helpers.php';
require_once __DIR__ . '/php/functions-links.php';

spl_autoload_register( function( $classname ) {
	if ( false !== strpos( $classname, 'Yoast\\YoastCom\\Theme\\' ) ) {
		$classname = str_replace( 'Yoast\\YoastCom\\Theme\\', '', $classname );

		$classname = strtolower( $classname );
		$classname = str_replace( '_', '-', $classname );

		require_once __DIR__ . '/php/class-' . $classname . '.php';
	}
});

/**
 * Return the instantiated theme object
 *
 * @return Theme
 */
function theme_object() {
	static $theme_object;

	if ( null === $theme_object ) {
		$theme_object = new Theme();
	}

	return $theme_object;
}

theme_object();
