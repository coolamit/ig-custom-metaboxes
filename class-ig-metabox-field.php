<?php

abstract class iG_Metabox_Field {

	protected $_field = array(
		'type'     => 'text',
		'required' => false,
		'readonly' => false,
		'disabled' => false,
	);

	protected $_default_value = null;
	protected $_values = array();

	protected $_sanitize = array(
		'callback' => 'wp_kses_post',
		'args'     => array(),
	);

	protected $_render = array(
		'callback' => '',
		'args'     => array(),
	);

	abstract protected function _sub_init();

	final public function __construct( $id, $label ) {
		if ( empty( $id ) || ! is_string( $id ) ) {
			throw new ErrorException( 'Field ID is required and must be a string' );
		}

		if ( empty( $label ) || ! is_string( $label ) ) {
			throw new ErrorException( 'Field label is required and must be a string' );
		}

		$this->_field['id'] = $id;
		$this->_field['name'] = $id;
		$this->_field['label'] = $label;

		$this->_render['callback'] = array( $this, '_render_input_field' );

		$this->_sub_init();
	}

	//factory
	final public static function create( $id, $label ) {
		$class = get_called_class();

		return new $class( $id, $label );
	}

	public function set_description( $description ) {
		if ( empty( $description ) || ! is_string( $description ) ) {
			throw new ErrorException( 'Metabox field description needs to be a string' );
		}

		$this->_field['description'] = $description;

		return $this;
	}

	public function set_placeholder( $placeholder ) {
		if ( empty( $placeholder ) || ! is_string( $placeholder ) ) {
			throw new ErrorException( 'Metabox field placeholder needs to be a string' );
		}

		$this->_field['placeholder'] = $placeholder;

		return $this;
	}

	public function set_default_value( $value ) {
		if ( empty( $value ) || ! is_string( $value ) ) {
			throw new ErrorException( 'Metabox field value needs to be a string' );
		}

		$this->_default_value = $value;

		return $this;
	}

	//for select & radio buttons
	public function set_values( array $values ) {
		if ( empty( $values ) ) {
			throw new ErrorException( 'Metabox field values need to be in an array' );
		}

		$this->_values = $values;

		return $this;
	}

	public function set_css_class( $class ) {
		if ( empty( $class ) || ! is_string( $class ) ) {
			throw new ErrorException( 'Metabox field CSS class needs to be a string' );
		}

		$this->_field['class'] = $class;

		return $this;
	}

	public function set_sanitize_callback( $callback, array $args = array() ) {
		$this->_sanitize['callback'] = $callback;
		$this->_sanitize['args'] = $args;

		return $this;
	}

	public function set_render_callback( $callback, array $args = array() ) {
		$this->_render['callback'] = $callback;
		$this->_render['args'] = $args;

		return $this;
	}

	//field required if this is called
	public function is_required() {
		if ( $this->_field['readonly'] === true || $this->_field['disabled'] === true ) {
			throw new ErrorException( 'Cannot require input on a read-only or disabled field' );
		}

		$this->_field['required'] = true;

		return $this;
	}

	//field read-only if this is called
	public function is_readonly() {
		if ( $this->_field['required'] === true ) {
			throw new ErrorException( 'A required field cannot be made read only' );
		}

		$this->_field['readonly'] = true;

		return $this;
	}

	//field disabled if this is called
	public function is_disabled() {
		if ( $this->_field['required'] === true ) {
			throw new ErrorException( 'A required field cannot be disabled' );
		}

		$this->_field['disabled'] = true;

		return $this;
	}

	public function get_id() {
		return $this->_field['id'];
	}

	final public function sanitize_data( $data ) {
		$args = array_merge( array( $data ), $this->_sanitize['args'] );

		if ( ! is_callable( $this->_sanitize['callback'] ) ) {
			throw new ErrorException( sprintf( 'Data sanitization callback defined for field %s is uncallable', $this->get_id() ) );
		}

		return call_user_func_array( $this->_sanitize['callback'], $args );
	}

	final public function render( $post_id = 0 ) {
		$args = array_merge( array( $post_id ), $this->_render['args'] );

		if ( ! is_callable( $this->_render['callback'] ) ) {
			throw new ErrorException( sprintf( 'Meta field render callback defined for field %s is uncallable', $this->get_id() ) );
		}

		return call_user_func_array( $this->_render['callback'], $args );
	}

	final public function get_data( $post_id ) {
		if ( empty( $post_id ) || intval( $post_id ) < 1 ) {
			return false;
		}

		$data = get_post_meta( $post_id, $this->get_id(), true );

		if ( ! is_bool( $data ) && empty( $data ) ) {
			return false;
		}

		return $data;
	}

	final public function save_data( $post_id, $data = '' ) {
		if ( empty( $post_id ) || intval( $post_id ) < 1 ) {
			return false;
		}

		$data = $this->sanitize_data( $data );

		if ( ! is_bool( $data ) && empty( $data ) ) {
			delete_post_meta( $post_id, $this->get_id() );

			return true;
		}

		update_post_meta( $post_id, $this->get_id(), $data );

		return true;
	}

	protected function _render_input_field( $post_id = 0 ) {
//		if ( empty( $field ) ) {
//			throw new ErrorException( 'Field attributes not defined' );
//		}

		$post_id = intval( $post_id );

		if ( empty( $this->_field['value'] ) && $post_id > 0 ) {
			$this->_field['value'] = $this->get_data( $post_id );

			if ( empty( $this->_field['value'] ) && ! is_null( $this->_default_value ) ) {
				$this->_field['value'] = $this->_default_value;
			}
		}

		$html = '';

		if ( ! empty( $this->_field['label'] ) ) {
			$html .= sprintf( '<label for="%s"><strong>%s</strong></label><br>', esc_attr( $this->_field['id'] ), esc_attr( $this->_field['label'] ) );
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

}	//end class



//EOF