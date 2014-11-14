<?php

class iG_Metabox_Text_Field extends iG_Metabox_Field {

	protected function _sub_init() {
		$this->_field['size'] = 25;
		$this->_field['type'] = 'text';

		$this->set_render_callback( array( $this, '_render_input_field' ) );
	}

	public function set_size( $size ) {
		if ( empty( $size ) || ! is_numeric( $size ) ) {
			throw new ErrorException( 'Metabox field size needs to be a number' );
		}

		$this->_field['size'] = intval( $size );

		return $this;
	}

	public function set_maxlength( $maxlength ) {
		if ( empty( $maxlength ) || ! is_numeric( $maxlength ) ) {
			throw new ErrorException( 'Metabox field maxlength needs to be a number' );
		}

		$this->_field['maxlength'] = intval( $maxlength );

		return $this;
	}

	public function set_values( array $values ) {
		throw new ErrorException( 'Multiple values array can be set only for select or radio type metabox fields' );
	}

	protected function _render_input_field( $post_id = 0 ) {
		$post_id = intval( $post_id );

		if ( empty( $this->_field['value'] ) && $post_id > 0 ) {
			$this->_field['value'] = $this->get_data( $post_id );
		}

		if ( empty( $this->_field['value'] ) && ! is_null( $this->_default_value ) ) {
			$this->_field['value'] = $this->_default_value;
		}

		$html = '';

		if ( ! empty( $this->_field['label'] ) ) {
			$html .= sprintf( '<label for="%s"><strong>%s</strong></label><br>', esc_attr( $this->_field['id'] ), wp_kses_post( $this->_field['label'] ) );
			unset( $this->_field['label'] );
		}

		if ( ! empty( $this->_field['description'] ) ) {
			$description = $this->_field['description'];
			unset( $this->_field['description'] );
		}

		$html .= '<input';

		if ( $this->_field['required'] === true ) {
			$status = ' required';
		} elseif ( $this->_field['readonly'] === true ) {
			$status = ' readonly';
		} elseif ( $this->_field['disabled'] === true ) {
			$status = ' disabled';
		}

		unset( $this->_field['required'], $this->_field['readonly'], $this->_field['disabled'] );

		foreach ( $this->_field as $attribute => $value ) {
			$html .= sprintf( ' %s="%s"', $attribute, esc_attr( $value ) );
		}

		if ( ! empty( $status ) ) {
			$html .= ' ' . $status;
		}

		$html .= '>';

		if ( ! empty( $description ) ) {
			$html .= sprintf( '<br><span class="description">%s</span>', wp_kses_post( $description ) );
		}

		return $html;
	}

}	//end of class



//EOF