<?php


class iG_Metabox {

	protected $_post_types = array();

	protected $_metabox = array(
		'title'    => 'iG Metabox',
		'context'  => 'normal',
		'priority' => 'default',
	);

	protected $_fields = array();

	protected $_nonce = array(
		'action' => '',
		'name'   => '',
	);

	public function __construct( $id ) {
		if ( empty( $id ) || ! is_string( $id ) ) {
			throw new ErrorException( 'Metabox ID is required and must be a string' );
		}

		$this->_metabox['id'] = sanitize_title_with_dashes( $id );

		$this->_nonce['action'] = sprintf( '%s_action', $this->_metabox['id'] );
		$this->_nonce['name'] = sprintf( '%s_nonce', $this->_metabox['id'] );
	}

	protected function _maybe_setup_defaults() {
		$this->_post_types = array_filter( array_unique( array_map( 'trim', $this->_post_types ) ) );

		if ( empty( $this->_post_types ) ) {
			$this->_post_types = array( 'post' );
		}
	}

	protected function _validate() {
		$this->_maybe_setup_defaults();

		if ( empty( $this->_fields ) ) {
			throw new ErrorException( 'Metabox cannot be created without any data input fields' );
		}

		return true;
	}

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
			return false;
		}

		return true;
	}

	public function set_title( $title ) {
		if ( empty( $title ) || ! is_string( $title ) ) {
			throw new ErrorException( 'Metabox title needs to be defined' );
		}

		$this->_metabox['title'] = $title;

		return $this;
	}

	public function set_post_type( $post_type ) {
		if ( empty( $post_type ) || ! is_string( $post_type ) ) {
			throw new ErrorException( 'Post type needs to be defined for which metabox is to be created' );
		}

		$this->set_post_types( array( $post_type ) );

		return $this;
	}

	public function set_post_types( array $post_types ) {
		if ( empty( $post_types ) ) {
			throw new ErrorException( 'Post types need to be defined for which metabox is to be created' );
		}

		$this->_post_types = array_filter( array_unique( array_merge( $this->_post_types, array_map( 'trim', $post_types ) ) ) );

		return $this;
	}

	public function set_context( $context ) {
		if ( empty( $context ) || ! is_string( $context ) ) {
			throw new ErrorException( 'Metabox context needs to be defined' );
		}

		$this->_metabox['context'] = $context;

		return $this;
	}

	public function set_priority( $priority ) {
		if ( empty( $priority ) || ! is_string( $priority ) ) {
			throw new ErrorException( 'Metabox priority needs to be defined' );
		}

		$this->_metabox['priority'] = $priority;

		return $this;
	}

	public function set_css_class( $class ) {
		if ( empty( $class ) || ! is_string( $class ) ) {
			throw new ErrorException( 'Metabox CSS class needs to be a string' );
		}

		$this->_metabox['class'] = $class;

		return $this;
	}

	public function add_field( iG_Metabox_Field $field ) {
		$this->_fields[ $field->get_id() ] = $field;

		return $this;
	}

	//sets up the metabox render & the save hook
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

	public function render( $post ) {
		//load up the template
		//pass it:
		//	- metabox ID
		//	- fields array
		echo iG_Metabox_Helper::render_template( IG_CUSTOM_METABOXES_ROOT . '/templates/metabox-ui.php', array(
			'metabox_id'  => $this->_metabox['id'],
			'metabox_css' => $this->_metabox['class'],
			'nonce'       => $this->_nonce,
			'fields'      => $this->_fields,
			'post_id'     => $post->ID,
		) );
	}

	public function save_meta_data( $post_id ) {
		//check nonce & validate it
		//loop over fields array
		//sanitize field data and save it in post meta
		//if a field's data doesn't exist then delete that post meta

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