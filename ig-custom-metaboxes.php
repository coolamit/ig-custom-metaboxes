<?php
/*
Plugin Name: iG:Custom Metaboxes
Description: A custom field manager which provides an object oriented & clean API to create custom metaboxes and attach custom input fields to them which are saved as post meta automatically on post save.
Version: 1.0 beta
Author: Amit Gupta
Author URI: http://igeek.info/
License: GPLv2 or later
*/

define( 'IG_CUSTOM_METABOXES_ROOT', __DIR__ );
define( 'IG_CUSTOM_METABOXES_VERSION', '1.0-beta' );

function ig_custom_metaboxes_loader() {
	/*
	 * Load autoloader
	 */
	require_once IG_CUSTOM_METABOXES_ROOT . '/autoload.php';
}

ig_custom_metaboxes_loader();


//EOF