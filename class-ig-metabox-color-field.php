<?php

class iG_Metabox_Color_Field extends iG_Metabox_Text_Field {

	protected function _sub_init() {
		parent::_sub_init();

		$this->_field['type'] = 'color';
		$this->set_sanitize_callback( array( $this, 'sanitize_hexadecimal_color_code' ) );
	}

	public function set_maxlength( $maxlength ) {
		throw new ErrorException( 'Cannot set maxlength for color type metabox field' );
	}

	public function set_placeholder( $placeholder ) {
		throw new ErrorException( 'Cannot set placeholder text for color type metabox field' );
	}

	public function is_readonly() {
		throw new ErrorException( 'Cannot make color type metabox field read only' );
	}

	public function sanitize_hexadecimal_color_code( $color_code ) {
		if ( empty( $color_code ) || ! is_string( $color_code ) ) {
			return '';
		}

		$pattern = '/^#(?:[0-9a-fA-F]{3}){1,2}$/';

		if ( ! preg_match( $pattern, $color_code ) ) {
			//not a valid hexadecimal color code
			return '';
		}

		return strtoupper( $color_code );
	}

}	//end of class



//EOF