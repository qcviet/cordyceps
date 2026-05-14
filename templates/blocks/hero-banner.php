<?php

/**
 * Block: Hero Banner
 *
 * @package cordyceps
 * @author biogreen
 * @since 0.0.1
 */

$data = wp_parse_args($args, [
	'class' => '',
	'title' => '',
	'subtitle' => '',
	'title_italic' => '',
	'description' => '',
	'icon_items' => [],
	'background_image' => '',
]);
$_class = 'hero-banner';
$_class .= !empty($data['class']) ? esc_attr(' ' . $data['class']) : '';
?>

<section class="hero-banner-section">
<div class="<?php echo esc_attr($_class); ?>">
	<div class="hero-banner__inner position-relative">
		<div class="hero-banner__content position-absolute top-50 start-50 translate-middle text-white z-3">
			<?php if (!empty($data['subtitle'])): ?>
				<div class="hero-banner__subtitle text-white"><?php echo esc_html($data['subtitle']); ?></div>
			<?php endif; ?>
			<?php if (!empty($data['title'])): ?>
				<h1 class="hero-banner__title"><?php echo esc_html($data['title']); ?></h1>
			<?php endif; ?>
			<?php if (!empty($data['title_italic'])): ?>
				<h5 class="hero-banner__title-italic"><?php echo esc_html($data['title_italic']); ?></h5>
			<?php endif; ?>
			<?php if (!empty($data['description'])): ?>
				<div class="hero-banner__description"><?php echo wp_kses_post($data['description']); ?></div>
			<?php endif; ?>
			<?php if (!empty($data['icon_items'])): ?>
				<div class="hero-banner__icon-items">
					<?php foreach ($data['icon_items'] as $icon_item): ?>
						<div class="hero-banner__icon-item d-flex align-items-center gap-2">
							<span class="icon"><?php echo cordyceps_get_svg_icon($icon_item['icon']); ?></span>
							<span class="text"><?php echo esc_html($icon_item['title']); ?></span>
						</div>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>
		</div>
		<?php
		get_template_part('templates/core-blocks/image', null, [
			'image_id' => $data['background_image'],
			'image_size' => 'full',
			'lazyload' => true,
			'class' => 'hero-banner__image',
		]);
		?>

	</div>
</div>
</section>
