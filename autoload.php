<?php
/**
 * Class and Trait autoloader for this plugin
 *
 * @author Amit Gupta <http://amitgupta.in/>
 */

/*
 * Register resource autoloader
 */
spl_autoload_register( 'ig_custom_metaboxes_autoloader' );

/**
 * The function that makes on-demand autoloading of files for this plugin
 * possible. It is registered with spl_autoload_register() and must not be
 * called directly.
 *
 * @param string $resource Fully qualified name of the resource that is to be loaded
 * @return void
 */
function ig_custom_metaboxes_autoloader( $resource = '' ) {
	$namespace_root = 'iG\Metabox';

	$resource = trim( $resource, '\\' );

	if ( empty( $resource ) || strpos( $resource, '\\' ) === false || strpos( $resource, $namespace_root ) !== 0 ) {
		//not our namespace, bail out
		return;
	}

	$path = explode( '\\', $resource );

	array_shift( $path );
	array_shift( $path );

	$path = strtolower( str_replace( '_', '-', implode( '/', $path ) ) );

	if ( strpos( $path, 'trait' ) !== 0 ) {
		$path = 'classes/' . $path;
	}

	$path = sprintf( '%s/%s.php', untrailingslashit( IG_CUSTOM_METABOXES_ROOT ), $path );

	if ( file_exists( $path ) ) {
		require_once $path;
	}
}


//EOF