<?php
/**
 * The text field class.
 *
 * @author Amit Gupta <http://amitgupta.in/>
 */

class iG_Metabox_Text_Field extends iG_Metabox_Field {

	/**
	 * Field initialization stuff
	 *
	 * @return void
	 */
	protected function _sub_init() {
		$this->_field['size'] = 25;
		$this->_field['type'] = 'text';

		$this->set_render_callback( array( $this, '_render_input_field' ) );
	}

	/**
	 * Set size of the field.
	 *
	 * @param int $size
	 * @return iG_Metabox_Field
	 */
	public function set_size( $size ) {
		if ( empty( $size ) || ! is_numeric( $size ) ) {
			throw new ErrorException( 'Metabox field size needs to be a number' );
		}

		$this->_field['size'] = intval( $size );

		return $this;
	}

	/**
	 * Set maxlength of the field.
	 *
	 * @param int $maxlength
	 * @return iG_Metabox_Field
	 */
	public function set_maxlength( $maxlength ) {
		if ( empty( $maxlength ) || ! is_numeric( $maxlength ) ) {
			throw new ErrorException( 'Metabox field maxlength needs to be a number' );
		}

		$this->_field['maxlength'] = intval( $maxlength );

		return $this;
	}

	/**
	 * Set value of the field.
	 *
	 * @param string $value
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
	 * Parent method override, method is not applicable to this field. Using this will result in an exception being thrown.
	 */
	public function set_values( array $values ) {
		throw new ErrorException( 'Multiple values array can be set only for select or radio type metabox fields' );
	}

	/**
	 * This method renders the UI for the field
	 *
	 * @param int $post_id ID of the post for which field is being created
	 * @return string Field UI markup
	 */
	protected function _render_input_field( $post_id = 0 ) {
		$post_id = intval( $post_id );

		if ( empty( $this->_field['value'] ) && $post_id > 0 ) {
			$this->_field['value'] = $this->get_data( $post_id );
		}

		if ( empty( $this->_field['value'] ) && ! is_null( $this->_default_value ) ) {
			$this->_field['value'] = $this->_default_value;
		}

		$label = '';
		$description = '';
		$status = '';

		if ( ! empty( $this->_field['label'] ) ) {
			$label = $this->_field['label'];
		}

		unset( $this->_field['label'] );

		if ( ! empty( $this->_field['description'] ) ) {
			$description = $this->_field['description'];
		}

		unset( $this->_field['description'] );

		if ( $this->_field['required'] === true ) {
			$status = ' required';
		} elseif ( $this->_field['readonly'] === true ) {
			$status = ' readonly';
		} elseif ( $this->_field['disabled'] === true ) {
			$status = ' disabled';
		}

		unset( $this->_field['required'], $this->_field['readonly'], $this->_field['disabled'] );

		return iG_Metabox_Helper::render_template( IG_CUSTOM_METABOXES_ROOT . '/templates/field-ui/input.php', array(
			'attributes'   => $this->_field,
			'label'        => $label,
			'description'  => $description,
			'status'       => $status,
		) );
	}

}	//end of class



//EOF