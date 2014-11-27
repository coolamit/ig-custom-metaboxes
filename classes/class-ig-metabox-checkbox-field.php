<?php
/**
 * The checkbox field class.
 *
 * @author Amit Gupta <http://amitgupta.in/>
 */

class iG_Metabox_Checkbox_Field extends iG_Metabox_Text_Field {

	/**
	 * Field initialization stuff
	 *
	 * @return void
	 */
	protected function _sub_init() {
		parent::_sub_init();

		$this->_field['type'] = 'checkbox';

		$this->set_render_callback( array( $this, '_render_input_checkbox_field' ) );
	}

	/**
	 * Parent method override, method is not applicable to this field. Using this will result in an exception being thrown.
	 */
	public function set_placeholder( $placeholder ) {
		throw new ErrorException( 'Cannot set placeholder for checkbox type metabox field' );
	}

	/**
	 * Parent method override, method is not applicable to this field. Using this will result in an exception being thrown.
	 */
	public function set_size( $size ) {
		throw new ErrorException( 'Cannot set size for checkbox type metabox field' );
	}

	/**
	 * Parent method override, method is not applicable to this field. Using this will result in an exception being thrown.
	 */
	public function set_maxlength( $maxlength ) {
		throw new ErrorException( 'Cannot set maxlength for checkbox type metabox field' );
	}

	/**
	 * Parent method override, method is not applicable to this field. Using this will result in an exception being thrown.
	 */
	public function is_readonly() {
		throw new ErrorException( 'Cannot make checkbox type metabox field read only' );
	}

	/**
	 * Set value for the field
	 *
	 * @param string $placeholder
	 * @return iG_Metabox_Field
	 */
	public function set_value( $value ) {
		if ( empty( $value ) || ! is_string( $value ) ) {
			throw new ErrorException( 'Metabox field value needs to be a string' );
		}

		$this->_field['value'] = $value;

		return $this;
	}

	/**
	 * This method checks for required properties of the field before rendering the field.
	 *
	 * @return boolean Returns TRUE if all ok else throws an exception
	 */
	protected function _run_pre_flight() {
		if ( empty( $this->_field['value'] ) ) {
			throw new ErrorException( 'Value must be defined for checkbox type metabox field' );
		}

		return true;
	}

	/**
	 * This method renders the UI for the field
	 *
	 * @param int $post_id ID of the post for which field is being created
	 * @return string Field UI markup
	 */
	protected function _render_input_checkbox_field( $post_id = 0 ) {
		$this->_run_pre_flight();

		if ( $this->_field['value'] == $this->get_data( $post_id ) ) {
			$this->_field['checked'] = 'checked';
		}

		return $this->_render_input_field( $post_id );
	}

}	//end of class



//EOF