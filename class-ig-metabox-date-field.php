<?php

class iG_Metabox_Date_Field extends iG_Metabox_Text_Field {

	protected function _sub_init() {
		parent::_sub_init();

		$this->_field['type'] = 'date';
		$this->set_sanitize_callback( array( $this, 'sanitize_date' ) );
	}

	public function set_maxlength( $maxlength ) {
		throw new ErrorException( 'Cannot set maxlength for date type metabox field' );
	}

	public function set_placeholder( $placeholder ) {
		throw new ErrorException( 'Cannot set placeholder text for date type metabox field' );
	}

	public function set_min( $value ) {
		if ( empty( $value ) || ! $this->is_date( $value ) ) {
			throw new ErrorException( 'Minimum value for date type metabox field must be a date in YYYY-MM-DD format' );
		}

		$this->_field['min'] = $this->sanitize_data( $value );

		return $this;
	}

	public function set_max( $value ) {
		if ( empty( $value ) || ! $this->is_date( $value ) ) {
			throw new ErrorException( 'Maximum value for date type metabox field must be a date in YYYY-MM-DD format' );
		}

		$this->_field['max'] = $this->sanitize_data( $value );

		return $this;
	}

	public function is_date( $value ) {
		$response = $this->sanitize_data( $value );

		return (bool) ( ! empty( $response ) );
	}

	public function sanitize_date( $value ) {
		if ( empty( $value ) || ! is_string( $value ) ) {
			return '';
		}

		$pattern = '/^(\d\d\d\d)-(0[1-9]|1[012])-(0[1-9]|[12][0-9]|3[01])$/';

		if ( ! preg_match( $pattern, $value ) ) {
			//not a valid date
			return '';
		}

		return $value;
	}

}	//end of class



//EOF