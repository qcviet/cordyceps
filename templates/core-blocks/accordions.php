<?php

/**
 * Block: Accordions
 *
 * @package cordyceps
 * @author biogreen
 * @since 0.0.1
 */

$data = wp_parse_args($args, [
	'class' => '',
	'items' => []
]);

$_class = 'accordions';
$_class .= !empty($data['class']) ? esc_attr(' ' . $data['class']) : '';


?>

<div class="<?php echo esc_attr($_class); ?>" data-block="accordions">
	<?php foreach ($data['items'] as $index => $item):
		$is_active = $index === 0;
	?>
		<div class=" accordions__item">
			<div class="accordions__item-header">
				<button
					class="accordion__item-button w-100 bg-transparent text-black fw-bold d-flex justify-content-between px-0 py-1 text-start<?php if ($is_active): ?> is-active <?php endif; ?>"
					role="tab">
					<span class="text pe-none"><?php echo esc_html($item['title']); ?></span>
					<span class="icon pe-none"><?php echo cordyceps_get_svg_icon('plus'); ?></span>
				</button>
			</div>
			<div class="accordions__item-content pe-2<?php if ($is_active): ?> is-active <?php endif; ?>" role="tabpanel">
				<div class="accordions__item-content-inner">
					<?php echo wp_kses_post($item['description']); ?>
				</div>
			</div>
		</div>
	<?php endforeach; ?>
</div>
