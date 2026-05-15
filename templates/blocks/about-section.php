<?php

/**
 * Block: About section
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
	'history_items' => [],
	'image' => '',
]);
$_class = 'about-section';
$_class .= !empty($data['class']) ? esc_attr(' ' . $data['class']) : '';
?>

<section class="about-section">
	<div class="<?php echo esc_attr($_class); ?> py-3 py-md-2">
		<div class="row g-3 g-md-4 align-items-stretch">
			<div class="col-12 col-md-6 d-flex flex-column">
				<?php
				get_template_part('templates/core-blocks/image', null, [
					'image_id' => $data['image'],
					'image_size' => 'full',
					'lazyload' => true,
					'class' => 'about-section__image',
				]);
				?>
			</div>

			<div class="col-12 col-md-6 d-flex">
				<div class="about-section__content align-self-center">
					<?php if (!empty($data['subtitle'])): ?>
						<h4 class="about-section__subtitle text-uppercase"><?php echo esc_html($data['subtitle']); ?></h4>
					<?php endif; ?>
					<?php if (!empty($data['title'])): ?>
						<h1 class="about-section__title text-uppercase">
							<span class="about-section__title-stack">
								<span class="about-section__title-text"><?php echo esc_html($data['title']); ?></span>
								<span class="about-section__title-accent" aria-hidden="true">
									<span class="about-section__title-accent-bar"></span>
									<span class="about-section__title-accent-dot"></span>
									<span class="about-section__title-accent-line"></span>
								</span>
							</span>
						</h1>
					<?php endif; ?>
					<?php if (!empty($data['description'])): ?>
						<h5 class="about-section__description mt-2"><?php echo wp_kses_post($data['description']); ?></h5>
					<?php endif; ?>
				</div>
			</div>
		</div>

		<div class="row">
			<div class="about-section__history">
				<div class="about-section__history-border py-1">
					<?php foreach ($data['history_items'] as $item): ?>
						<div class="about-section__history-item text-center">
							<div class="about-section__history-item-title">
								<h4><?php echo esc_html($item['title']); ?></h4>
							</div>
							<div class="about-section__history-item-description">
								<h6><?php echo wp_kses_post($item['description']); ?></h6>
							</div>
						</div>
					<?php endforeach; ?>
				</div>
			</div>
		</div>

	</div>
</section>
