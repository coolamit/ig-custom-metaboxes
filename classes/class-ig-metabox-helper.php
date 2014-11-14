<?php


class iG_Metabox_Helper {

	/**
	 * Function to check if an array is associative array or not.
	 *
	 * @param array $array_to_check Array which is to be checked
	 * @return boolean Returns TRUE if the array is associative else FALSE. Even a single numeric key would make this function return FALSE.
	 */
	public static function is_associative_array( array $array_to_check ) {
		return ! (bool) count( array_filter( array_keys( $array_to_check ), 'is_numeric' ) );
	}

	public static function render_template( $template, array $vars = array() ) {
		if ( empty( $template ) ) {
			throw new ErrorException( 'Template file path not defined, this code is not psychic!' );
		}

		if ( ! file_exists( $template ) ) {
			throw new ErrorException( 'Template ' . basename( $template ) . ' does not exist' );
		}

		if ( ! empty( $vars ) && ! static::is_associative_array( $vars ) ) {
			throw new ErrorException( 'Variables for the template must be passed as an associative array' );
		}

		extract( $vars, EXTR_SKIP );

		ob_start();
		require $template;
		return ob_get_clean();
	}

}	//end of class



//EOF