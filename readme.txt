=== iG:Custom Metaboxes ===
Contributors: amit
Tags: meta, metabox, admin, library, custom field, form, custom metadata, custom post types, metabox, metadata, post meta, postmeta
Requires at least: 4.0
Tested up to: 4.1.1
Stable tag: trunk
License: GNU GPL v2

A WordPress plugin to provide an object oriented and clean API for creating custom meta-boxes on wp-admin *post_type* add/edit screens.

== Description ==

**iG:Custom Metaboxes** is a WordPress plugin to provide an object oriented and clean API for creating custom meta-boxes on wp-admin *post_type* add/edit screens.

This plugin is more or less a library which is meant to be used in other plugins or themes. On its own this plugin does nothing, it has no UI nor any configuration to be set/tweaked. This plugin allows addition of HTML5 fields however the functionality of specialized fields like color picker, date picker etc. is browser dependent. The plugin does not add any JavaScript to make these fields compatible with browsers which don't support HTML5 or which don't add functionality to these specialized fields.

This plugin has been written with WordPress.com VIP compliance in mind, so you can use it as is even if you host your website with WordPress.com VIP.

**Requirements**: This plugin requires PHP 5.4 or better and is supported on WordPress 4.0 or better. It might work on a lower version of PHP or WordPress but no support would be provided for those platforms.

Pull requests on Github are welcome.

Github: https://github.com/coolamit/ig-custom-metaboxes


== Installation ==

###Installing The Plugin###

Extract all files from the zip file and then upload it to `/wp-content/plugins/`. **Make sure to keep the file/folder structure intact.**

Go to WordPress admin section, click on "Plugins" in the menu bar and then click "Activate" link under "iG:Custom Metaboxes".

**See Also:** ["Installing Plugins" article on the WP Codex](http://codex.wordpress.org/Managing_Plugins#Installing_Plugins)

== Other Notes ==

###Plugin Usage###

On its own this plugin will not do anything, it is a library meant to be used in other plugins/themes. It provides an API to create custom metaboxes in wp-admin on post_type screens in an object oriented way.

**Usage Example:**

`
$metabox = new iG\Metabox\Metabox( 'my-metabox-1' );

$metabox->set_title( 'My Cool Metabox' )
		->set_context( 'normal' )
		->set_priority( 'default' )
		->set_css_class( 'my-metabox' )
		->set_post_type( 'post' )
		->add_field(	//add a simple text input field
			iG\Metabox\Text_Field::create( 'my-txt-1', 'My Text Field 1' )
								->set_description( 'Some Desc for 1st Field' )
								->set_css_class( 'my-txt-css-1 second-css-cls' )
								->set_placeholder( 'Enter the first text here' )
								->set_size( 50 )
		)
		->add_field(	//add a HTML5 number input field
			iG\Metabox\Number_Field::create( 'my-num-1', 'My Numb Field A' )
								->set_description( 'Some Desc for 1st Number Field' )
								->set_css_class( 'my-num-css-1' )
								->set_placeholder( 'Enter the first number here' )
								->set_step( 2.5 )
		)
		->add_field(	//add a HTML5 color picker
			iG\Metabox\Color_Field::create( 'my-col-1', 'My Color Field 1a' )
								->set_description( 'Some Desc for 1st Color Field' )
								->set_css_class( 'my-colr-css-1' )
		)
		->add_field(	//add a HTML date picker
			iG\Metabox\Date_Field::create( 'my-dt-1', 'My Date Field 1a' )
								->set_description( 'Some Desc for 1st Date Field' )
								->set_css_class( 'my-dt-css-1' )
								->set_min( '2014-05-01' )
		)
		->add_field(	//add a HTML5 date time picker
			iG\Metabox\Date_Time_Field::create( 'my-dttm-1', 'My Date Time Field A' )
								->set_description( 'Some Desc for 1st Date Time Field' )
								->set_css_class( 'my-dttm-css-1' )
								->set_min( '2014-05-01T05:00' )
		)
		->add_field(	//add a radio button group
			iG\Metabox\Radio_Group::create( 'my-rdg-1', 'My Radio Group A' )
								->set_description( 'Some Desc for 1st Radio Group' )
								->set_css_class( 'my-rdg-css-1' )
								->set_values( array(
									'apple' => 'Apple',
									'grape' => 'Grape',
									'banana' => 'Banana',
									'orange' => 'Orange',
									'peach' => 'Peach',
								) )
		)
		->add();
`

== Frequently Asked Questions ==

= I activated the plugin and I get all kinds of errors on my screen. What is wrong? =

Please make sure you are using PHP 5.4 or better and WordPress 4.0 or better. If you are not a developer then this plugin likely came with some other plugin or theme you installed and you should contact the developer of that plugin/theme for support.

= I see some code that I can improve. Do you accept pull requests? =

By all means, feel free to submit a pull request.

= I want XYZ feature. Can you implement it? =

Please feel free to suggest a new feature. Its inclusion might be speedier if you can provide the code to make it work.

== ChangeLog ==

= v1.0 =

* Initial release


