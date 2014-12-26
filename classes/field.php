<?php
/**
 * The custom metabox field abstract class. All field classes must extend this class.
 *
 * @author Amit Gupta <http://amitgupta.in/>
 */

namespace iG\Metabox;

abstract class Field {

	/**
	 * @var array Field properties
	 */
	protected $_field = array(
		'type'     => 'text',
		'value'    => '',
		'class'    => array(),
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
	 * Method to allow set up of hooks
	 *
	 * @return void
	 */
	protected function _setup_hooks() {}

	/**
	 * Field class constructor. This is a final method as child classes should have no need to override this.
	 *
	 * @param string $id Unique ID of the field
	 * @param string $label Label to display for the field
	 * @return void
	 */
	final public function __construct( $id, $label ) {
		if ( empty( $id ) || ! is_string( $id ) ) {
			throw new \ErrorException( 'Field ID is required and must be a string' );
		}

		if ( empty( $label ) || ! is_string( $label ) ) {
			throw new \ErrorException( 'Field label is required and must be a string' );
		}

		$this->_field['id'] = $id;
		$this->_field['name'] = $id;
		$this->_field['label'] = $label;

		/*
		 * Run child class initialization stuff
		 */
		$this->_sub_init();

		/*
		 * Set up hooks
		 */
		$this->_setup_hooks();
	}

	/**
	 * Factory method to instantiate a field class.
	 *
	 * @param string $id Unique ID of the field
	 * @param string $label Label to display for the field
	 * @return iG\Metabox\Field Object of the specific field class
	 */
	final public static function create( $id, $label ) {
		$class = get_called_class();

		return new $class( $id, $label );
	}

	/**
	 * Pre-render data preparation etc.
	 *
	 * @return void
	 */
	private function _pre_flight() {
		$this->_field['class'] = implode( ' ', $this->_field['class'] );
	}

	/**
	 * Returns the short name of current class
	 *
	 * @param boolean $for_hook Set this to TRUE if class name is needed for use in hook name
	 * @return string Short name of current class
	 */
	final protected function _get_class_name( $for_hook = false ) {
		$class_name = explode( '\\', get_called_class() );
		$class_name = array_pop( $class_name );

		if ( $for_hook === true ) {
			$class_name = str_replace( '_', '-', strtolower( $class_name ) );
		}

		return $class_name;
	}

	/**
	 * Set description of the field
	 *
	 * @param string $description
	 * @return iG\Metabox\Field
	 */
	public function set_description( $description ) {
		if ( empty( $description ) || ! is_string( $description ) ) {
			throw new \ErrorException( 'Metabox field description needs to be a string' );
		}

		$this->_field['description'] = $description;

		return $this;
	}

	/**
	 * Set default value of the field
	 *
	 * @param string $value
	 * @return iG\Metabox\Field
	 */
	public function set_default_value( $value ) {
		if ( empty( $value ) || ! is_string( $value ) ) {
			throw new \ErrorException( 'Metabox field value needs to be a string' );
		}

		$this->_default_value = $value;

		return $this;
	}

	/**
	 * Set CSS class for the field. Multiple classes can be set by passing them in a string with class names separated by single space.
	 *
	 * @param string $class
	 * @return iG\Metabox\Field
	 */
	public function set_css_class( $class ) {
		if ( empty( $class ) || ! is_string( $class ) ) {
			throw new \ErrorException( 'Metabox field CSS class needs to be a string' );
		}

		$this->set_css_classes( explode( ' ', $class ) );

		return $this;
	}

	/**
	 * Set CSS classes for the field. Multiple classes can be set by passing them in an array.
	 *
	 * @param array $classes
	 * @return iG\Metabox\Field
	 */
	public function set_css_classes( array $classes ) {
		if ( empty( $classes ) || ! is_array( $classes ) ) {
			throw new \ErrorException( 'Multiple metabox field CSS classes must be passed as an array' );
		}

		$this->_field['class'] = array_filter( array_unique( array_merge( $this->_field['class'], $classes ) ) );

		return $this;
	}

	/**
	 * Set callback which is used to sanitize field data before it is saved.
	 *
	 * @param string|array $callback
	 * @param array $args Optional parameters which are passed to the callback
	 * @return iG\Metabox\Field
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
	 * @return iG\Metabox\Field
	 */
	public function set_render_callback( $callback, array $args = array() ) {
		$this->_render['callback'] = $callback;
		$this->_render['args'] = $args;

		return $this;
	}

	/**
	 * Set the field as required
	 *
	 * @return iG\Metabox\Field
	 */
	public function is_required() {
		if ( $this->_field['readonly'] === true || $this->_field['disabled'] === true ) {
			throw new \ErrorException( 'Cannot require input on a read-only or disabled field' );
		}

		$this->_field['required'] = true;

		return $this;
	}

	/**
	 * Set the field as read only
	 *
	 * @return iG\Metabox\Field
	 */
	public function is_readonly() {
		if ( $this->_field['required'] === true ) {
			throw new \ErrorException( 'A required field cannot be made read only' );
		}

		$this->_field['readonly'] = true;

		return $this;
	}

	/**
	 * Set the field as disabled
	 *
	 * @return iG\Metabox\Field
	 */
	public function is_disabled() {
		if ( $this->_field['required'] === true ) {
			throw new \ErrorException( 'A required field cannot be disabled' );
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
			throw new \ErrorException( sprintf( 'Data sanitization callback defined for field %s is uncallable', $this->get_id() ) );
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
			throw new \ErrorException( sprintf( 'Meta field render callback defined for field %s is uncallable', $this->get_id() ) );
		}

		$this->_pre_flight();

		echo call_user_func_array( $this->_render['callback'], $args );
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