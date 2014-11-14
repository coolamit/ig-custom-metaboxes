<?php

class iG_Metabox_Checkbox_Field extends iG_Metabox_Text_Field {

	protected function _sub_init() {
		parent::_sub_init();

		$this->_field['type'] = 'checkbox';

		$this->set_render_callback( array( $this, '_render_input_checkbox_field' ) );
	}

	public function set_placeholder( $placeholder ) {
		throw new ErrorException( 'Cannot set placeholder for checkbox type metabox field' );
	}

	public function set_size( $size ) {
		throw new ErrorException( 'Cannot set size for checkbox type metabox field' );
	}

	public function set_maxlength( $maxlength ) {
		throw new ErrorException( 'Cannot set maxlength for checkbox type metabox field' );
	}

	public function is_readonly() {
		throw new ErrorException( 'Cannot make checkbox type metabox field read only' );
	}

	protected function _run_pre_flight() {
		if ( empty( $this->_field['value'] ) ) {
			throw new ErrorException( 'Value must be defined for checkbox type metabox field' );
		}

		return true;
	}

	protected function _render_input_checkbox_field( $post_id = 0 ) {
		$this->_run_pre_flight();

		if ( $this->_field['value'] == $this->get_data( $post_id ) ) {
			$this->_field['checked'] = 'checked';
		}

		return $this->_render_input_field( $post_id );
	}

}	//end of class



//EOF