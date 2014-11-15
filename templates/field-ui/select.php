<?php
/**
 * Select field UI template
 *
 * @package iG:Custom Metaboxes v1.0
 */

$css_class = ( ! empty( $field['class'] ) ) ? $field['class'] : '';
$field['id'] = 'ig-cmf-' . $field['id'];
?>
	<label for="<?php echo esc_attr( $field['id'] ); ?>">
		<strong><?php echo wp_kses_post( $field['label'] ); ?></strong>
	</label>
	<br>
	<select id="<?php echo esc_attr( $field['id'] ); ?>" name="<?php echo esc_attr( $field['name'] ); ?>" class="<?php echo esc_attr( $css_class ); ?>"<?php echo $status; ?>>
<?php
foreach ( $options as $value => $label ) {
?>
		<option value="<?php echo esc_attr( $value ); ?>"<?php selected( $field['value'], $value ); ?>><?php echo esc_html( $label ); ?></option>
<?php
}
?>
	</select>
<?php
if ( ! empty( $field['description'] ) ) {
?>
	<br>
	<span class="description"><?php echo wp_kses_post( $field['description'] ); ?></span>
<?php
}

//EOF