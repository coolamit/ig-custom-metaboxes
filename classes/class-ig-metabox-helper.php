<?php
/**
 * Class containing collection of helper methods.
 *
 * @author Amit Gupta <http://amitgupta.in/>
 */


class iG_Metabox_Helper {

	/**
	 * Method to check if an array is associative array or not.
	 *
	 * @param array $array_to_check Array which is to be checked
	 * @return boolean Returns TRUE if the array is associative else FALSE. Even a single numeric key would make this function return FALSE.
	 */
	public static function is_associative_array( array $array_to_check ) {
		return ! (bool) count( array_filter( array_keys( $array_to_check ), 'is_numeric' ) );
	}

	/**
	 * Method to render a template and return the markup
	 *
	 * @param string $template File path to the template file
	 * @param array $vars Associative array of values which are to be injected into the template. The array keys become var names and key values respective var values.
	 * @return string Template markup ready for output
	 */
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