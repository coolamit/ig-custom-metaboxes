<?php

class iG_Metabox_Select_Field extends iG_Metabox_Field {

	protected function _sub_init() {
		$this->set_render_callback( array( $this, '_render_select_field' ) );
	}

	public function set_size( $size ) {
		throw new ErrorException( 'Cannot set size for select type metabox field' );
	}

	public function set_maxlength( $maxlength ) {
		throw new ErrorException( 'Cannot set maxlength for select type metabox field' );
	}

	public function is_readonly() {
		throw new ErrorException( 'Cannot make select type metabox field read only' );
	}

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

		return iG_Metabox_Helper::render_template( __DIR__ . '/templates/field-ui/select.php', array(
			'field'   => $this->_field,
			'options' => $this->_values,
			'status'  => $status,
		) );
	}

}	//end of class



//EOF