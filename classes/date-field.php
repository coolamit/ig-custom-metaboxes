<?php
/**
 * The date field class.
 *
 * @author Amit Gupta <http://amitgupta.in/>
 */

namespace iG\Metabox;

class Date_Field extends Text_Field {

	/**
	 * Field initialization stuff
	 *
	 * @return void
	 */
	protected function _sub_init() {
		parent::_sub_init();

		$this->_field['type'] = 'date';
		$this->set_sanitize_callback( array( $this, 'sanitize_date' ) );
	}

	/**
	 * Parent method override, method is not applicable to this field. Using this will result in an exception being thrown.
	 */
	public function set_maxlength( $maxlength ) {
		throw new \ErrorException( 'Cannot set maxlength for date type metabox field' );
	}

	/**
	 * Parent method override, method is not applicable to this field. Using this will result in an exception being thrown.
	 */
	public function set_placeholder( $placeholder ) {
		throw new \ErrorException( 'Cannot set placeholder text for date type metabox field' );
	}

	/**
	 * Set minimum date
	 *
	 * @param string $value Date in YYYY-MM-DD format
	 * @return iG\Metabox\Field
	 */
	public function set_min( $value ) {
		if ( empty( $value ) || ! $this->is_date( $value ) ) {
			throw new \ErrorException( 'Minimum value for date type metabox field must be a date in YYYY-MM-DD format' );
		}

		$this->_field['min'] = $this->sanitize_data( $value );

		return $this;
	}

	/**
	 * Set maximum date
	 *
	 * @param string $value Date in YYYY-MM-DD format
	 * @return iG\Metabox\Field
	 */
	public function set_max( $value ) {
		if ( empty( $value ) || ! $this->is_date( $value ) ) {
			throw new \ErrorException( 'Maximum value for date type metabox field must be a date in YYYY-MM-DD format' );
		}

		$this->_field['max'] = $this->sanitize_data( $value );

		return $this;
	}

	/**
	 * This method checks if the passed value is a valid date for this field or not.
	 *
	 * @param string $value Date in YYYY-MM-DD format
	 * @return boolean Returns TRUE if value is valid else FALSE
	 */
	public function is_date( $value ) {
		$response = $this->sanitize_data( $value );

		return (bool) ( ! empty( $response ) );
	}

	/**
	 * This method sanitizes the data of the field
	 *
	 * @param string $value Data of the field
	 * @return string Sanitized data
	 */
	public function sanitize_date( $value ) {
		if ( empty( $value ) || ! is_string( $value ) ) {
			return '';
		}

		$pattern = '/^(\d\d\d\d)-(0[1-9]|1[012])-(0[1-9]|[12][0-9]|3[01])$/';	//date must be in YYYY-MM-DD format

		if ( ! preg_match( $pattern, $value ) ) {
			//not a valid date
			return '';
		}

		return $value;
	}

}	//end of class



//EOF