<?php
/**
 * Input Radio group UI template
 *
 * @package iG:Custom Metaboxes v1.0
 */

$field_id = 'ig-cmf-' . $attributes['id'];
?>
	<label><strong><?php echo wp_kses_post( $label ); ?></strong></label>
	<br>
<?php
$counter = 0;

$input_attributes_html = '';
unset( $attributes['id'], $attributes['value'] );

foreach ( $attributes as $attribute => $value ) {
	$input_attributes_html .= sprintf( ' %s="%s"', $attribute, esc_attr( $value ) );
}

foreach ( $values as $radio_value => $radio_label ) {
	$counter++;
	$radio_id = sprintf( '%s-%d', $field_id, $counter );
?>
	<input id="<?php echo esc_attr( $radio_id ); ?>" value="<?php echo esc_attr( $radio_value ); ?>"
<?php
	echo $input_attributes_html;
	checked( $selected_value, $radio_value );
	echo esc_attr( $status );
?>
	>
	<label for="<?php echo esc_attr( $radio_id ); ?>"><?php echo wp_kses_post( $radio_label ); ?></label>
	&nbsp;
<?php
	unset( $radio_id );
}

if ( ! empty( $description ) ) {
?>
	<br>
	<span class="description"><?php echo wp_kses_post( $description ); ?></span>
<?php
}

//EOF