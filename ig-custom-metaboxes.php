<?php
/*
Plugin Name: iG:Custom Metaboxes
Description: A custom field manager which provides an Object Oriented clean API to create custom metaboxes and attach custom data input fields to them which are saved as post meta automatically on post save.
Version: 0.1
Author: Amit Gupta
Author URI: http://igeek.info/
License: GPLv2 or later
*/


function ig_custom_metaboxes_loader() {
	/*
	 * Helpers
	 */
	require_once __DIR__ . '/class-ig-metabox-helper.php';

	/*
	 * Core
	 */
	require_once __DIR__ . '/class-ig-metabox.php';
	require_once __DIR__ . '/class-ig-metabox-field.php';

	/*
	 * Field type classes
	 */
	require_once __DIR__ . '/class-ig-metabox-text-field.php';
	require_once __DIR__ . '/class-ig-metabox-number-field.php';
	require_once __DIR__ . '/class-ig-metabox-email-field.php';
	require_once __DIR__ . '/class-ig-metabox-color-field.php';
	require_once __DIR__ . '/class-ig-metabox-date-field.php';
	require_once __DIR__ . '/class-ig-metabox-date-time-field.php';
	require_once __DIR__ . '/class-ig-metabox-select-field.php';
}

ig_custom_metaboxes_loader();


//EOF