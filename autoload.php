<?php
/**
 * Class and Trait autoloader for this plugin
 */


function __autoload( $classname = '' ) {
	$namespace_root = 'iG\Metabox';

	$classname = trim( $classname, '\\' );

	if ( empty( $classname ) || strpos( $classname, '\\' ) === false || strpos( $classname, $namespace_root ) !== 0 ) {
		//not our namespace, bail out
		return;
	}

	$path = explode( '\\', $classname );

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