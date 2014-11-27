<?php
/**
 * The select dropdown field class.
 *
 * @author Amit Gupta <http://amitgupta.in/>
 */

class iG_Metabox_Select_Field extends iG_Metabox_Field {

	/*
	 * Import trait
	 */
	use iG_Set_Values;


	/**
	 * Field initialization stuff
	 *
	 * @return void
	 */
	protected function _sub_init() {
		$this->set_render_callback( array( $this, '_render_select_field' ) );
	}

	/**
	 * Parent method override, method is not applicable to this field. Using this will result in an exception being thrown.
	 */
	public function is_readonly() {
		throw new ErrorException( 'Cannot make select type metabox field read only' );
	}

	/**
	 * Set placeholder text for the field
	 *
	 * @param string $placeholder
	 * @return iG_Metabox_Field
	 */
	public function set_placeholder( $placeholder ) {
		if ( empty( $placeholder ) || ! is_string( $placeholder ) ) {
			throw new ErrorException( 'Metabox field placeholder needs to be a string' );
		}

		$this->_field['placeholder'] = $placeholder;

		return $this;
	}

	/**
	 * This method renders the UI for the field
	 *
	 * @param int $post_id ID of the post for which field is being created
	 * @return string Field UI markup
	 */
	protected function _render_select_field( $post_id = 0 ) {
		$post_id = intval( $post_id );

		if ( empty( $this->_field['value'] ) && $post_id > 0 ) {
			$this->_field['value'] = $this->get_data( $post_id );
		}

		if ( empty( $this->_field['value'] ) && ! is_null( $this->_default_value ) ) {
			$this->_field['value'] = $this->_default_value;
		}

		if ( ! empty( $this->_field['placeholder'] ) ) {
			$this->_values = array_merge( array( '' => $this->_field['placeholder'] ), $this->_values );
		}

		$status = '';

		if ( $this->_field['required'] === true ) {
			$status = ' required';
		} elseif ( $this->_field['disabled'] === true ) {
			$status = ' disabled';
		}

		unset( $this->_field['required'], $this->_field['readonly'], $this->_field['disabled'] );

		return iG_Metabox_Helper::render_template( IG_CUSTOM_METABOXES_ROOT . '/templates/field-ui/select.php', array(
			'field'   => $this->_field,
			'options' => $this->_values,
			'status'  => $status,
		) );
	}

}	//end of class



//EOF