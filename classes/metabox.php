<?php
/**
 * The custom metabox class
 * Create a new object of this class to create a metabox.
 *
 * @author Amit Gupta <http://amitgupta.in/>
 */

namespace iG\Metabox;

use iG\Metabox\Helper as iG_Metabox_Helper;
use iG\Metabox\Field as iG_Metabox_Field;

class Metabox {

	/**
	 * @var array An array of post types for which the metabox is to be created
	 */
	protected $_post_types = array();

	/**
	 * @var array Metabox properties
	 */
	protected $_metabox = array(
		'title'    => 'iG Metabox',
		'context'  => 'normal',
		'priority' => 'default',
	);

	/**
	 * @var array An array containing objects of fields attached to the metabox
	 */
	protected $_fields = array();

	/**
	 * @var array Nonce stuff
	 */
	protected $_nonce = array(
		'action' => '',
		'name'   => '',
	);


	/**
	 * Constructor
	 *
	 * @param string $id Unique ID of the metabox
	 * @return void
	 */
	public function __construct( $id ) {
		if ( empty( $id ) || ! is_string( $id ) ) {
			throw new \ErrorException( 'Metabox ID is required and must be a string' );
		}

		$this->_metabox['id'] = sanitize_title_with_dashes( $id );

		$this->_nonce['action'] = sprintf( '%s_action', $this->_metabox['id'] );
		$this->_nonce['name'] = sprintf( '%s_nonce', $this->_metabox['id'] );
	}

	/**
	 * Setup default values of certain vars
	 *
	 * @return void
	 */
	protected function _maybe_setup_defaults() {
		$this->_post_types = array_filter( array_unique( array_map( 'trim', $this->_post_types ) ) );

		/*
		 * If there is no post type set for this metabox,
		 * then & only then set 'post' as default
		 */
		if ( empty( $this->_post_types ) ) {
			$this->_post_types = array( 'post' );
		}
	}

	/**
	 * Validate & check all necessary vars/properties are set
	 *
	 * @return boolean Returns TRUE if everything checks out else throws an exception
	 */
	protected function _validate() {
		$this->_maybe_setup_defaults();

		if ( empty( $this->_fields ) ) {
			throw new \ErrorException( 'Metabox cannot be created without any data input fields' );
		}

		return true;
	}

	/**
	 * This function runs the checks to verify and validate a save request
	 *
	 * @param int $post_id ID of the post for which data is to be saved
	 * @return boolean Returns TRUE if all ok else FALSE
	 */
	protected function _is_pre_save_check_ok( $post_id ) {
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return false;
		}

		if ( empty( $_POST[ $this->_nonce['name'] ] ) || ! wp_verify_nonce( $_POST[ $this->_nonce['name'] ], $this->_nonce['action'] ) ) {
			return false;
		}

		$user_permission_hook = ( get_post_type( $post_id ) == 'page' ) ? 'edit_page' : 'edit_post';

		if ( ! current_user_can( $user_permission_hook, $post_id ) ) {
			return false;
		}

		unset( $user_permission_hook );

		if ( ! in_array( get_post_type( $post_id ), $this->_post_types ) ) {
			//Post is not of a type for which metabox was created, bail out
			return false;
		}

		return true;
	}

	/**
	 * Set the title of the metabox
	 *
	 * @param string $title
	 * @return iG_Metabox
	 */
	public function set_title( $title ) {
		if ( empty( $title ) || ! is_string( $title ) ) {
			throw new \ErrorException( 'Metabox title needs to be defined' );
		}

		$this->_metabox['title'] = $title;

		return $this;
	}

	/**
	 * Set the post type for which the metabox is to be created
	 *
	 * @param string $post_type
	 * @return iG_Metabox
	 */
	public function set_post_type( $post_type ) {
		if ( empty( $post_type ) || ! is_string( $post_type ) ) {
			throw new \ErrorException( 'Post type needs to be defined for which metabox is to be created' );
		}

		$this->set_post_types( array( $post_type ) );

		return $this;
	}

	/**
	 * Set the post types for which the metabox is to be created. Multiple post types can be set using this method.
	 *
	 * @param array $post_types An array of post types
	 * @return iG_Metabox
	 */
	public function set_post_types( array $post_types ) {
		if ( empty( $post_types ) ) {
			throw new \ErrorException( 'Post types need to be defined for which metabox is to be created' );
		}

		$this->_post_types = array_filter( array_unique( array_merge( $this->_post_types, array_map( 'trim', $post_types ) ) ) );

		return $this;
	}

	/**
	 * Set context of the metabox
	 *
	 * @param string $context
	 * @return iG_Metabox
	 */
	public function set_context( $context ) {
		if ( empty( $context ) || ! is_string( $context ) ) {
			throw new \ErrorException( 'Metabox context needs to be defined' );
		}

		$this->_metabox['context'] = $context;

		return $this;
	}

	/**
	 * Set priority of the metabox
	 *
	 * @param string $priority
	 * @return iG_Metabox
	 */
	public function set_priority( $priority ) {
		if ( empty( $priority ) || ! is_string( $priority ) ) {
			throw new \ErrorException( 'Metabox priority needs to be defined' );
		}

		$this->_metabox['priority'] = $priority;

		return $this;
	}

	/**
	 * Set CSS class for the metabox. Multiple classes can be set by passing them in a string with class names separated by single space.
	 *
	 * @param string $class
	 * @return iG_Metabox
	 */
	public function set_css_class( $class ) {
		if ( empty( $class ) || ! is_string( $class ) ) {
			throw new \ErrorException( 'Metabox CSS class needs to be a string' );
		}

		$this->_metabox['class'] = $class;

		return $this;
	}

	/**
	 * Add a data input field to the metabox.
	 *
	 * @param iG\Metabox\Field\Field $field An object of a child class of iG\Metabox\Field\Field
	 * @return iG_Metabox
	 */
	public function add_field( iG_Metabox_Field $field ) {
		$this->_fields[ $field->get_id() ] = $field;

		return $this;
	}

	/**
	 * Add the metabox and set it up for render and data save. No more methods can be called on the class object after this method is called.
	 *
	 * @return void
	 */
	public function add() {
		$this->_validate();

		sort( $this->_post_types );

		/*
		 * Queue up metaboxes
		 */
		for ( $i = 0; $i < count( $this->_post_types ); $i++ ) {
			add_action(
				sprintf( 'add_meta_boxes_%s', $this->_post_types[ $i ] ),
				array( $this, 'add_meta_box' )
			);
		}

		/*
		 * Setup listener to save metabox data
		 */
		add_action( 'save_post', array( $this, 'save_meta_data' ) );
	}

	/**
	 * This method is called back by WordPress on 'add_meta_boxes_*' hooks to add the metabox. This is not for general consumption.
	 *
	 * @param WP_Post $post Post object for which metabox is to be created
	 * @return void
	 */
	public function add_meta_box( $post ) {
		if ( empty( $post ) ) {
			return;
		}

		$post_type = get_post_type( $post );

		if ( empty( $post_type ) ) {
			return;
		}

		add_meta_box(
			$this->_metabox['id'],
			$this->_metabox['title'],
			array( $this, 'render' ),
			$post_type,
			$this->_metabox['context'],
			$this->_metabox['priority']
		);
	}

	/**
	 * This method is called back by WordPress to render the metabox. This is not for general consumption.
	 *
	 * @param WP_Post $post Post object for which metabox is to be created
	 * @return void
	 */
	public function render( $post ) {
		echo iG_Metabox_Helper::render_template( IG_CUSTOM_METABOXES_ROOT . '/templates/metabox-ui.php', array(
			'metabox_id'  => $this->_metabox['id'],
			'metabox_css' => $this->_metabox['class'],
			'nonce'       => $this->_nonce,
			'fields'      => $this->_fields,
			'post_id'     => $post->ID,
		) );
	}

	/**
	 * This method is called back by WordPress on 'save_post' hook. It saves the data from the metabox. This is not for general consumption.
	 *
	 * @param int $post_id ID of the post which is being saved
	 * @return void
	 */
	public function save_meta_data( $post_id ) {
		if ( ! $this->_is_pre_save_check_ok( $post_id ) ) {
			return;
		}

		foreach ( $this->_fields as $field_id => $field ) {
			$data = '';

			if ( isset( $_POST[ $field_id ] ) ) {
				$data = $_POST[ $field_id ];
			}

			$field->save_data( $post_id, $data );

			unset( $data );
		}
	}

}	//end class

//EOF