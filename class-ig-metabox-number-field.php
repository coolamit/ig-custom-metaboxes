<?php

class iG_Metabox_Number_Field extends iG_Metabox_Text_Field {

	protected function _sub_init() {
		parent::_sub_init();

		$this->_field['type'] = 'number';
		$this->set_sanitize_callback( 'floatval' );
	}

	public function set_maxlength( $maxlength ) {
		throw new ErrorException( 'Cannot set maxlength for number type metabox field' );
	}

	public function set_min( $value ) {
		if ( empty( $value ) || ! is_numeric( $value ) ) {
			throw new ErrorException( 'Minimum value for number type metabox field must be a number' );
		}

		$this->_field['min'] = floatval( $value );

		return $this;
	}

	public function set_max( $value ) {
		if ( empty( $value ) || ! is_numeric( $value ) ) {
			throw new ErrorException( 'Maximum value for number type metabox field must be a number' );
		}

		$this->_field['max'] = floatval( $value );

		return $this;
	}

	public function set_step( $value ) {
		if ( empty( $value ) || ! is_numeric( $value ) ) {
			throw new ErrorException( 'Step value for number type metabox field must be a number' );
		}

		$this->_field['step'] = floatval( $value );

		return $this;
	}

}	//end of class



//EOF