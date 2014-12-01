/*
 * Admin UI javascript
 *
 * @author Amit Gupta <http://amitgupta.in/>
 * @package iG:Custom Metaboxes v1.0
 */

var ig_cmf = {
	update_color_code: function( $field ) {
		if ( ! $field ) {
			return;
		}

		var $color = $field.val();

		if ( $color ) {
			$field.parent().find( '.ig-cmf-input-color-code' ).text( $color.toUpperCase() );
		}
	}
};

jQuery( document ).ready( function( $ ) {

	$( '.ig-cmf-input-color' ).on( 'change', function() {
		ig_cmf.update_color_code( $( this ) );
	} );

} );


//EOF