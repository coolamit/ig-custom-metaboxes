<?php

class iG_Metabox_Radio_Group extends iG_Metabox_Field {

	protected function _sub_init() {
		$this->_field['type'] = 'radio';

		$this->set_render_callback( array( $this, '_render_input_radio_group' ) );
	}

	public function set_placeholder( $placeholder ) {
		throw new ErrorException( 'Cannot set placeholder for radio type metabox field' );
	}

	public function is_readonly() {
		throw new ErrorException( 'Cannot make radio type metabox field read only' );
	}

	protected function _run_pre_flight() {
		if ( empty( $this->_values ) ) {
			throw new ErrorException( 'One or more values must be defined for radio type metabox field' );
		}

		return true;
	}

	protected function _render_input_radio_group( $post_id = 0 ) {
		$this->_run_pre_flight();

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
		$selected_value = $this->_field['value'];

		unset( $this->_field['value'] );

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
		} elseif ( $this->_field['disabled'] === true ) {
			$status = ' disabled';
		}

		unset( $this->_field['required'], $this->_field['readonly'], $this->_field['disabled'] );

		return iG_Metabox_Helper::render_template( IG_CUSTOM_METABOXES_ROOT . '/templates/field-ui/input-radio.php', array(
			'attributes'     => $this->_field,
			'values'         => $this->_values,
			'label'          => $label,
			'description'    => $description,
			'status'         => $status,
			'selected_value' => $selected_value,
		) );
	}

}	//end of class



//EOF