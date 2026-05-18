<?php

/**
 * Block: Trust badges
 *
 * @package cordyceps
 * @author biogreen
 * @since 0.0.1
 */

if (!isset($args) || !is_array($args)) {
	$args = [];
}

$data = wp_parse_args($args, [
	'class' => '',
	'three_items' => [],
	'four_items' => [],
]);
$_class = 'trust-badges';
$_class .= !empty($data['class']) ? esc_attr(' ' . $data['class']) : '';

?>

<section class="trust-badges-section">
	<div class="<?php echo esc_attr($_class); ?>">
		<div class="trust-badges__inner container">
			<div class="trust-badges__content">
				<?php if (!empty($data['three_items'])) : ?>
					<div class="trust-badges__three-items">
						<?php foreach ($data['three_items'] as $three_item) : ?>
							<div class="trust-badges__three-item">
								<span class="trust-badges__three-item-corner trust-badges__three-item-corner--tl" aria-hidden="true">
									<?php echo cordyceps_get_svg_icon('corner-ornament'); ?>
								</span>
								<span class="trust-badges__three-item-corner trust-badges__three-item-corner--br" aria-hidden="true">
									<?php echo cordyceps_get_svg_icon('corner-ornament'); ?>
								</span>
								<div class="trust-badges__three-item-body">
									<?php if (!empty($three_item['icon'])) : ?>
										<div class="trust-badges__three-item-icon" aria-hidden="true">
											<?php echo cordyceps_get_svg_icon($three_item['icon']); ?>
										</div>
									<?php endif; ?>
									<div class="trust-badges__three-item-copy">
										<?php if (!empty($three_item['title'])) : ?>
											<h2 class="trust-badges__three-item-title"><?php echo esc_html($three_item['title']); ?></h2>
										<?php endif; ?>
										<?php if (!empty($three_item['description'])) : ?>
											<p class="trust-badges__three-item-description"><?php echo wp_kses_post($three_item['description']); ?></p>
										<?php endif; ?>
									</div>
								</div>
							</div>
						<?php endforeach; ?>
					</div>
				<?php endif; ?>

				<?php if (!empty($data['four_items'])) : ?>
					<div class="trust-badges__four-items">
						<?php foreach ($data['four_items'] as $four_item) : ?>
							<div class="trust-badges__four-item">
								<div class="trust-badges__four-item-body">
									<?php if (!empty($four_item['icon'])) : ?>
										<div class="trust-badges__four-item-icon" aria-hidden="true">
											<?php echo cordyceps_get_svg_icon($four_item['icon']); ?>
										</div>
									<?php endif; ?>
									<div class="trust-badges__four-item-copy">
										<?php if (!empty($four_item['title'])) : ?>
											<h2 class="trust-badges__four-item-title"><?php echo esc_html($four_item['title']); ?></h2>
										<?php endif; ?>
										<?php if (!empty($four_item['text'])) : ?>
											<p class="trust-badges__four-item-text"><?php echo esc_html($four_item['text']); ?></p>
										<?php endif; ?>
									</div>
								</div>
							</div>
						<?php endforeach; ?>
					</div>
				<?php endif; ?>
			</div>
		</div>
	</div>
</section>
