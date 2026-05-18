<?php

/**
 * Core Block: Pagination links list.
 *
 * @package cordyceps
 * @author biogreen
 * @since 0.0.1
 */

$data = wp_parse_args($args, [
	'class' => '',
	'links' => [],
]);

if (empty($data['links']) || !is_array($data['links'])) {
	return;
}

$_class = 'cordyceps-pagination';
$_class .= !empty($data['class']) ? ' ' . esc_attr($data['class']) : '';
?>

<nav class="<?php echo esc_attr($_class); ?>" aria-label="<?php esc_attr_e('Phân trang', 'cordyceps'); ?>">
	<ul class="cordyceps-pagination__list">
		<?php foreach ($data['links'] as $link) : ?>
			<?php
			$is_current = false !== strpos($link, 'current');
			$is_dots = false !== strpos($link, 'dots');
			$item_class = 'cordyceps-pagination__item';

			if ($is_current) {
				$item_class .= ' is-active';
			}

			if ($is_dots) {
				$item_class .= ' is-dots';
			}
			?>
			<li class="<?php echo esc_attr($item_class); ?>">
				<?php echo wp_kses_post($link); ?>
			</li>
		<?php endforeach; ?>
	</ul>
</nav>
