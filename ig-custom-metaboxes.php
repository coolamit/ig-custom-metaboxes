<?php
/*
Plugin Name: iG:Custom Metaboxes
Description: A custom field manager which provides an Object Oriented clean API to create custom metaboxes and attach custom data input fields to them which are saved as post meta automatically on post save.
Version: 0.1
Author: Amit Gupta
Author URI: http://igeek.info/
License: GPLv2 or later
*/

define( 'IG_CUSTOM_METABOXES_ROOT', __DIR__ );

function ig_custom_metaboxes_loader() {
	/*
	 * Helpers
	 */
	require_once IG_CUSTOM_METABOXES_ROOT . '/classes/class-ig-metabox-helper.php';

	/*
	 * Core
	 */
	require_once IG_CUSTOM_METABOXES_ROOT . '/classes/class-ig-metabox.php';
	require_once IG_CUSTOM_METABOXES_ROOT . '/classes/class-ig-metabox-field.php';

	/*
	 * Field type classes
	 */
	require_once IG_CUSTOM_METABOXES_ROOT . '/classes/class-ig-metabox-text-field.php';
	require_once IG_CUSTOM_METABOXES_ROOT . '/classes/class-ig-metabox-number-field.php';
	require_once IG_CUSTOM_METABOXES_ROOT . '/classes/class-ig-metabox-email-field.php';
	require_once IG_CUSTOM_METABOXES_ROOT . '/classes/class-ig-metabox-color-field.php';
	require_once IG_CUSTOM_METABOXES_ROOT . '/classes/class-ig-metabox-date-field.php';
	require_once IG_CUSTOM_METABOXES_ROOT . '/classes/class-ig-metabox-date-time-field.php';
	require_once IG_CUSTOM_METABOXES_ROOT . '/classes/class-ig-metabox-select-field.php';
	require_once IG_CUSTOM_METABOXES_ROOT . '/classes/class-ig-metabox-checkbox-field.php';
}

ig_custom_metaboxes_loader();


//EOF