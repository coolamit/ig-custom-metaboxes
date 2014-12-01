<?php
/**
 * The color field class.
 *
 * @author Amit Gupta <http://amitgupta.in/>
 */

namespace iG\Metabox;

class Color_Field extends Text_Field {

	/**
	 * Field initialization stuff
	 *
	 * @return void
	 */
	protected function _sub_init() {
		parent::_sub_init();

		$this->_field['type'] = 'color';
		$this->set_sanitize_callback( array( $this, 'sanitize_hexadecimal_color_code' ) );
		$this->set_css_class( 'ig-cmf-input-color' );
	}

	/**
	 * Parent method override, method is not applicable to this field. Using this will result in an exception being thrown.
	 */
	public function set_maxlength( $maxlength ) {
		throw new \ErrorException( 'Cannot set maxlength for color type metabox field' );
	}

	/**
	 * Parent method override, method is not applicable to this field. Using this will result in an exception being thrown.
	 */
	public function set_placeholder( $placeholder ) {
		throw new \ErrorException( 'Cannot set placeholder text for color type metabox field' );
	}

	/**
	 * Parent method override, method is not applicable to this field. Using this will result in an exception being thrown.
	 */
	public function is_readonly() {
		throw new \ErrorException( 'Cannot make color type metabox field read only' );
	}

	/**
	 * This method sanitizes the data of the field
	 *
	 * @param string $color_code Data of the field
	 * @return string Sanitized data
	 */
	public function sanitize_hexadecimal_color_code( $color_code ) {
		if ( empty( $color_code ) || ! is_string( $color_code ) ) {
			return '';
		}

		$pattern = '/^#(?:[0-9a-fA-F]{3}){1,2}$/';	//allow only fully qualified hexadecimal color code (with hash mark prefixed)

		if ( ! preg_match( $pattern, $color_code ) ) {
			//not a valid hexadecimal color code
			return '';
		}

		return strtoupper( $color_code );
	}

}	//end of class



//EOF