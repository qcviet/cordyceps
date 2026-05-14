<?php
/**
 * Core Block: Single Checkbox
 *
 * @package cordyceps
 * @author biogreen
 * @since 2.0.0
 */

$data = wp_parse_args($args, [
	'class' => '',
	'id' => '',
	'label' => '',
	'value' => '',
	'is_checked' => false
]);

$_class = 'single-checkbox';
$_class .= !empty( $data['class'] ) ? esc_attr(' ' . $data['class'] ) : '';

?>

<div class="<?php echo esc_attr( $_class ); ?>">
	<input class="single-checkbox__input" id="<?php echo esc_attr( $data['id']); ?>" type="checkbox" name="<?php echo esc_attr( $data['id']); ?>" value="<?php echo esc_attr( $data['value']); ?>"<?php checked($data['is_checked'], true); ?>>
	<label class="single-checkbox__label" for="<?php echo esc_attr( $data['id']); ?>"><?php echo esc_html( $data['label'] ); ?></label>
</div>
