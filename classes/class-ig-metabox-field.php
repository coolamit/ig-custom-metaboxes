<?php
/**
 * The custom metabox field abstract class. All field classes must extend this class.
 *
 * @author Amit Gupta <http://amitgupta.in/>
 */

abstract class iG_Metabox_Field {

	/**
	 * @var array Field properties
	 */
	protected $_field = array(
		'type'     => 'text',
		'value'    => '',
		'required' => false,
		'readonly' => false,
		'disabled' => false,
	);

	/**
	 * @var string Default value of the field
	 */
	protected $_default_value = null;

	/**
	 * @var array Values of the field which allow multiple options like select dropdown and radio button group
	 */
	protected $_values = array();

	/**
	 * @var array Array containing callback function to sanitize data before it is saved
	 */
	protected $_sanitize = array(
		'callback' => 'wp_kses_post',
		'args'     => array(),
	);

	/**
	 * @var array Array containing callback function to render the field
	 */
	protected $_render = array(
		'callback' => '',
		'args'     => array(),
	);


	/**
	 * Method to allow initialization stuff in child classes.
	 *
	 * @return void
	 */
	abstract protected function _sub_init();

	/**
	 * Field class constructor. This is a final method as child classes should have no need to override this.
	 *
	 * @param string $id Unique ID of the field
	 * @param string $label Label to display for the field
	 * @return void
	 */
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

		/*
		 * Run child class initialization stuff
		 */
		$this->_sub_init();
	}

	/**
	 * Factory method to instantiate a field class.
	 *
	 * @param string $id Unique ID of the field
	 * @param string $label Label to display for the field
	 * @return iG_Metabox_Field Object of the specific field class
	 */
	final public static function create( $id, $label ) {
		$class = get_called_class();

		return new $class( $id, $label );
	}

	/**
	 * Set description of the field
	 *
	 * @param string $description
	 * @return iG_Metabox_Field
	 */
	public function set_description( $description ) {
		if ( empty( $description ) || ! is_string( $description ) ) {
			throw new ErrorException( 'Metabox field description needs to be a string' );
		}

		$this->_field['description'] = $description;

		return $this;
	}

	/**
	 * Set default value of the field
	 *
	 * @param string $value
	 * @return iG_Metabox_Field
	 */
	public function set_default_value( $value ) {
		if ( empty( $value ) || ! is_string( $value ) ) {
			throw new ErrorException( 'Metabox field value needs to be a string' );
		}

		$this->_default_value = $value;

		return $this;
	}

	/**
	 * Set CSS class for the field. Multiple classes can be set by passing them in a string with class names separated by single space.
	 *
	 * @param string $class
	 * @return iG_Metabox_Field
	 */
	public function set_css_class( $class ) {
		if ( empty( $class ) || ! is_string( $class ) ) {
			throw new ErrorException( 'Metabox field CSS class needs to be a string' );
		}

		$this->_field['class'] = $class;

		return $this;
	}

	/**
	 * Set callback which is used to sanitize field data before it is saved.
	 *
	 * @param string|array $callback
	 * @param array $args Optional parameters which are passed to the callback
	 * @return iG_Metabox_Field
	 */
	public function set_sanitize_callback( $callback, array $args = array() ) {
		$this->_sanitize['callback'] = $callback;
		$this->_sanitize['args'] = $args;

		return $this;
	}

	/**
	 * Set callback which is used to render the field UI.
	 *
	 * @param string|array $callback
	 * @param array $args Optional parameters which are passed to the callback
	 * @return iG_Metabox_Field
	 */
	public function set_render_callback( $callback, array $args = array() ) {
		$this->_render['callback'] = $callback;
		$this->_render['args'] = $args;

		return $this;
	}

	/**
	 * Set the field as required
	 *
	 * @return iG_Metabox_Field
	 */
	public function is_required() {
		if ( $this->_field['readonly'] === true || $this->_field['disabled'] === true ) {
			throw new ErrorException( 'Cannot require input on a read-only or disabled field' );
		}

		$this->_field['required'] = true;

		return $this;
	}

	/**
	 * Set the field as read only
	 *
	 * @return iG_Metabox_Field
	 */
	public function is_readonly() {
		if ( $this->_field['required'] === true ) {
			throw new ErrorException( 'A required field cannot be made read only' );
		}

		$this->_field['readonly'] = true;

		return $this;
	}

	/**
	 * Set the field as disabled
	 *
	 * @return iG_Metabox_Field
	 */
	public function is_disabled() {
		if ( $this->_field['required'] === true ) {
			throw new ErrorException( 'A required field cannot be disabled' );
		}

		$this->_field['disabled'] = true;

		return $this;
	}

	/**
	 * This method returns the ID of the current field
	 *
	 * @return string
	 */
	public function get_id() {
		return $this->_field['id'];
	}

	/**
	 * This method uses the callback set for the field's data sanitization and sanitizes it. This is called automatically on data save and should not be used directly.
	 *
	 * @param string $data
	 * @return string
	 */
	final public function sanitize_data( $data ) {
		$args = array_merge( array( $data ), $this->_sanitize['args'] );

		if ( ! is_callable( $this->_sanitize['callback'] ) ) {
			throw new ErrorException( sprintf( 'Data sanitization callback defined for field %s is uncallable', $this->get_id() ) );
		}

		return call_user_func_array( $this->_sanitize['callback'], $args );
	}

	/**
	 * This method uses the callback set for rendering the UI of the field and renders it. This is called automatically and must not be used directly.
	 *
	 * @param int $post_id ID of the post for which metabox field is being rendered
	 * @return void
	 */
	final public function render( $post_id = 0 ) {
		$args = array_merge( array( $post_id ), $this->_render['args'] );

		if ( ! is_callable( $this->_render['callback'] ) ) {
			throw new ErrorException( sprintf( 'Meta field render callback defined for field %s is uncallable', $this->get_id() ) );
		}

		return call_user_func_array( $this->_render['callback'], $args );
	}

	/**
	 * This method retrieves the saved data (if any) of the current field for the requested post
	 *
	 * @param int $post_id ID of the post whose field data is to be retrieved
	 * @return boolean|string Field data as saved in the database else FALSE if no data found
	 */
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

	/**
	 * This method saves the field's data of a post in post meta. This is called automatically when post is saved and must not be called directly.
	 *
	 * @param int $post_id ID of the post for which field data is to be saved
	 * @param string $data Data which is to be saved
	 * @return boolean Returns TRUE if data is saved else FALSE
	 */
	final public function save_data( $post_id, $data = '' ) {
		if ( empty( $post_id ) || intval( $post_id ) < 1 ) {
			return false;
		}

		$data = $this->sanitize_data( $data );

		/*
		 * If no valid data is passed then delete the meta key
		 */
		if ( ! is_bool( $data ) && empty( $data ) ) {
			delete_post_meta( $post_id, $this->get_id() );

			return true;
		}

		update_post_meta( $post_id, $this->get_id(), $data );

		return true;
	}

}	//end class



//EOF