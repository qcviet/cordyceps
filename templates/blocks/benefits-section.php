<?php

/**
 * Block: Benefits section
 *
 * @package cordyceps
 * @author biogreen
 * @since 0.0.1
 */

$data = wp_parse_args($args, [
	'class' => '',
	'title' => '',
	'subtitle' => '',
	'description' => '',
	'icon_items' => [],
	'image' => '',
]);
$_class = 'benefits-section';
$_class .= !empty($data['class']) ? esc_attr(' ' . $data['class']) : '';
?>

<section class="benefits-section">
	<div class="<?php echo esc_attr($_class); ?> position-relative ">
		<div class="benefits-section__inner">
			<div class="benefits-section__content text-start">
				<?php if (!empty($data['subtitle'])): ?>
					<h3 class="benefits-section__subtitle"><?php echo esc_html($data['subtitle']); ?></h3>
				<?php endif; ?>
				<?php if (!empty($data['title'])): ?>
					<h1 class="benefits-section__title"><?php echo esc_html($data['title']); ?></h1>
					<?php endif; ?>

					<?php if (!empty($data['icon_items'])): ?>
						<div class="benefits-section__icon-items d-flex flex-row flex-nowrap justify-content-between align-items-start w-100">
							<?php foreach ($data['icon_items'] as $item): ?>
								<div class="benefits-section__icon-cell d-flex flex-column align-items-center flex-grow-1 min-w-0">
									<div class="benefits-section__icon-ring d-flex align-items-center justify-content-center" aria-hidden="true">
										<?php echo cordyceps_get_svg_icon($item['icon']); ?>
									</div>
									<?php if (!empty($item['title'])): ?>
										<h4 class="benefits-section__icon-item-title"><?php echo esc_html($item['title']); ?></h4>
									<?php endif; ?>
								</div>
							<?php endforeach; ?>
						</div>
					<?php endif; ?>

					<?php if (!empty($data['description'])): ?>
						<div class="benefits-section__description"><?php echo wp_kses_post($data['description']); ?></div>
					<?php endif; ?>
			</div>
		</div>

		<?php if (!empty($data['image'])): ?>
			<?php get_template_part('templates/core-blocks/image', null, [
				'image_id' => $data['image'],
				'image_size' => 'full',
				'lazyload' => true,
				'class' => 'benefits-section__image',
			]);
			?>
		<?php endif; ?>

	</div>
</section>
