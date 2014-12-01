<?php
/**
 * Class containing collection of helper methods.
 *
 * @author Amit Gupta <http://amitgupta.in/>
 */

namespace iG\Metabox;

class Helper {

	const PLUGIN_ID = 'ig-custom-metaboxes';

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
			throw new \ErrorException( 'Template file path not defined, this code is not psychic!' );
		}

		if ( ! file_exists( $template ) ) {
			throw new \ErrorException( 'Template ' . basename( $template ) . ' does not exist' );
		}

		if ( ! empty( $vars ) && ! static::is_associative_array( $vars ) ) {
			throw new \ErrorException( 'Variables for the template must be passed as an associative array' );
		}

		extract( $vars, EXTR_SKIP );

		ob_start();
		require $template;
		return ob_get_clean();
	}

	/**
	 * This method returns the URL of an asset if relative path to asset is passed else
	 * the URL to assets folder.
	 *
	 * @param string $asset_path Optional asset path relative from assets folder
	 * @return string URL to asset or asset folder
	 */
	public static function get_asset_url( $asset_path = '' ) {
		return plugins_url( sprintf( '/assets/%s', ltrim( $asset_path, '/' ) ), dirname( __FILE__ ) );
	}

	/**
	 * This method sets up WordPress hooks for the plugin
	 *
	 * @return void
	 */
	public static function setup_hooks() {
		if ( ! is_admin() ) {
			return;
		}

		/*
		 * Actions
		 */
		add_action( 'admin_enqueue_scripts', array( __CLASS__, 'enqueue_stuff' ) );
	}

	/**
	 * Enqueue style(s) and/or javascript(s) for the plugin
	 *
	 * @return void
	 */
	public static function enqueue_stuff( $hook = '' ) {
		if ( ! in_array( $hook, array( 'post.php', 'post-new.php' ) ) ) {
			return;
		}

		wp_enqueue_script( sprintf( '%s-admin-js', self::PLUGIN_ID ), static::get_asset_url( 'js/ig-custom-metaboxes-admin.js' ), array( 'jquery' ), IG_CUSTOM_METABOXES_VERSION, true );
	}

}	//end of class



//EOF