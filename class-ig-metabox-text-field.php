<?php

class iG_Metabox_Text_Field extends iG_Metabox_Field {

	protected function _sub_init() {
		$this->_field['size'] = 25;
		$this->_field['type'] = 'text';
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

}	//end of class



//EOF