## iG:Custom Metaboxes
-----------------------

WordPress plugin to provide an object oriented and clean API for creating custom metaboxes.

**_This is a work in progress and is as such in Alpha stage. The API works in its current form but is likely to change before it reaches Beta._**

Example:

```php
$metabox = new iG_Metabox( 'my-metabox-1' );

$metabox->set_title( 'My Cool Metabox' )
		->set_context( 'normal' )
		->set_priority( 'default' )
		->set_css_class( 'my-metabox' )
		->set_post_type( 'post' )
		->add_field(
			iG_Metabox_Text_Field::create( 'my-txt-1', 'My Text Field 1' )
								->set_description( 'Some Desc for 1st Field' )
								->set_css_class( 'my-txt-css-1' )
								->set_placeholder( 'Enter the first text here' )
								->set_size( 50 )
		)
		->add_field(
			iG_Metabox_Number_Field::create( 'my-num-1', 'My Numb Field A' )
								->set_description( 'Some Desc for 1st Number Field' )
								->set_css_class( 'my-num-css-1' )
								->set_placeholder( 'Enter the first number here' )
								->set_step( 2.5 )
		)
		->add_field(
			iG_Metabox_Color_Field::create( 'my-col-1', 'My Color Field 1a' )
								->set_description( 'Some Desc for 1st Color Field' )
								->set_css_class( 'my-colr-css-1' )
		)
		->add_field(
			iG_Metabox_Date_Field::create( 'my-dt-1', 'My Date Field 1a' )
								->set_description( 'Some Desc for 1st Date Field' )
								->set_css_class( 'my-dt-css-1' )
								->set_min( '2014-05-01' )
		)
		->add_field(
			iG_Metabox_Date_Time_Field::create( 'my-dttm-1', 'My Date Time Field A' )
								->set_description( 'Some Desc for 1st Date Time Field' )
								->set_css_class( 'my-dttm-css-1' )
								->set_min( '2014-05-01T05:00' )
		)
		->add();
```
