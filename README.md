## iG:Custom Metaboxes v1.0
---------------------------------

**iG:Custom Metaboxes** is a WordPress plugin to provide an object oriented and clean API for creating custom meta-boxes on wp-admin *post_type* add/edit screens.

##### **Minimum Requirements**
- WordPress 4.0 or above
- PHP 5.4 or above

**WordPress.org:** [http://wordpress.org/plugins/ig-custom-metaboxes/](http://wordpress.org/plugins/ig-custom-metaboxes/)

This plugin is more or less a library which is meant to be used in other plugins or themes. On its own this plugin does nothing, it has no UI nor any configuration to be set/tweaked. This plugin allows addition of HTML5 fields however the functionality of specialized fields like color picker, date picker etc. is browser dependent. The plugin does not add any JavaScript to make these fields compatible with browsers which don't support HTML5 or which don't add functionality to these specialized fields.

A new metabox is created by creating a new object of ```iG\Metabox\Metabox``` class. Form field objects are added to metabox object to create the metabox UI.

##### **Adding More Form Fields**
All form field objects are of classes that descend from ```iG\Metabox\Field``` class. If you would like to add more field types then just create a new class and extend ```iG\Metabox\Field``` class. To add more functionality to an existing field class, you must create a new class and extend the field class to which you wish to add said functionality and then use that new class for creating form field objects which are attached to your metabox.

.
##### **Usage Example:**

```php
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
```

