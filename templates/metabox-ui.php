<?php
/**
 * Metabox UI template
 *
 * @package iG:Custom Metaboxes v1.0
 */
?>
<div id="mb-<?php echo esc_attr( $metabox_id ); ?>" class="<?php echo esc_attr( $metabox_css ); ?>">
	<?php wp_nonce_field( $nonce['action'], $nonce['name'] ); ?>

<?php
foreach ( $fields as $field ) {
?>
	<p><?php $field->render( $post_id ); ?></p>
<?php
}
?>
</div>
