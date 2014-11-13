<?php

class iG_Metabox_Date_Time_Field extends iG_Metabox_Text_Field {

	protected function _sub_init() {
		parent::_sub_init();

		$this->_field['type'] = 'datetime-local';
		$this->set_sanitize_callback( array( $this, 'sanitize_datetime' ) );
	}

	public function set_maxlength( $maxlength ) {
		throw new ErrorException( 'Cannot set maxlength for datetime type metabox field' );
	}

	public function set_placeholder( $placeholder ) {
		throw new ErrorException( 'Cannot set placeholder text for datetime type metabox field' );
	}

	public function set_min( $value ) {
		if ( empty( $value ) || ! $this->is_datetime( $value ) ) {
			throw new ErrorException( 'Minimum value for datetime type metabox field must be a date in YYYY-MM-DDTHH:MM format' );
		}

		$this->_field['min'] = $this->sanitize_data( $value );

		return $this;
	}

	public function set_max( $value ) {
		if ( empty( $value ) || ! $this->is_datetime( $value ) ) {
			throw new ErrorException( 'Maximum value for datetime type metabox field must be a date in YYYY-MM-DDTHH:MM format' );
		}

		$this->_field['max'] = $this->sanitize_data( $value );

		return $this;
	}

	public function is_datetime( $value ) {
		$response = $this->sanitize_data( $value );

		return (bool) ( ! empty( $response ) );
	}

	public function sanitize_datetime( $value ) {
		if ( empty( $value ) || ! is_string( $value ) ) {
			return '';
		}

		$pattern = '/^(\d\d\d\d)-(0[1-9]|1[0-2])-(0[1-9]|[12][0-9]|3[01])T([01][0-9]|2[0-3]):([0-4][0-9]|5[0-9])$/';

		if ( ! preg_match( $pattern, $value ) ) {
			//not a valid date
			return '';
		}

		return $value;
	}

}	//end of class



//EOF