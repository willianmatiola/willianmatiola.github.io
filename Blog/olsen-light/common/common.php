<?php
/**
 * Common theme features.
 */

/**
 * Common assets registration
 */
function olsen_light_register_common_assets() {
	$theme = wp_get_theme();
	wp_register_style( 'olsen-light-common', get_template_directory_uri() . '/common/css/global.css', array(), $theme->get( 'Version' ) );
}
add_action( 'init', 'olsen_light_register_common_assets', 8 );
