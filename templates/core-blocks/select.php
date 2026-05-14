<?php
/**
 * Core Block: Select
 *
 * @package cordyceps
 * @author biogreen
 * @since 2.0.0
 */

$data = wp_parse_args($args, [
	'class' => '',
	'id' => '',
	'label_class' => '',
	'items' => [],
	'placeholder' => ''
]);

$_class = 'select-block';
$_class .= !empty( $data['class'] ) ? esc_attr(' ' . $data['class'] ) : '';

$_label_class = 'select-block__label';
$_label_class .= !empty( $data['label_class'] ) ? esc_attr(' ' . $data['label_class'] ) : '';

?>

<div class="<?php echo esc_attr( $_class ); ?>">
	<label class="<?php echo esc_attr( $_label_class ); ?>" for="<?php echo esc_attr( $data['id'] ); ?>"><?php echo esc_html( $data['label'] ?? '' ); ?></label>
	<select class="select-block__select" id="<?php echo esc_attr( $data['id'] ); ?>">
		<?php if ( !empty($data['placeholder'] ) ) : ?>
			<option value=""><?php echo esc_html( $data['placeholder'] ); ?></option>
		<?php endif; ?>
		<?php foreach( $data['items'] as $item ) : ?>
			<option value="<?php echo esc_attr( $item['value'] ); ?>"><?php echo esc_html( $item['name'] ); ?></option>
		<?php endforeach; ?>
	</select>
	<span class="pe-none d-inline-flex justify-content-center align-items-center select-block__icon">
		<?php echo cordyceps_get_svg_icon('arrow-down'); ?>
	</span>
</div>
