<?php

/**
 * Block: Contact page — info card, map, CF7 form.
 *
 * @package cordyceps
 * @author biogreen
 * @since 0.0.1
 */

$data = wp_parse_args($args, [
	'class' => '',
	'contact_title' => '',
	'contact_address' => '',
	'contact_phone' => '',
	'contact_email' => '',
	'contact_facebook' => '',
	'contact_working_time' => '',
	'contact_brochure_url' => '',
	'contact_brochure_filename' => '',
	'contact_map_iframe' => '',
	'contact_form_id' => '',
]);

if (!cordyceps_contact_page_has_content($data)) {
	return;
}

$_class = 'contact-page-section';
$_class .= !empty($data['class']) ? ' ' . esc_attr($data['class']) : '';

$info_items = [
	[
		'icon' => 'map-pin',
		'label' => esc_html__('Địa chỉ', 'cordyceps'),
		'value' => $data['contact_address'],
		'type' => 'text',
	],
	[
		'icon' => 'phone',
		'label' => esc_html__('Điện thoại', 'cordyceps'),
		'value' => $data['contact_phone'],
		'type' => 'tel',
	],
	[
		'icon' => 'mail',
		'label' => esc_html__('Email', 'cordyceps'),
		'value' => $data['contact_email'],
		'type' => 'email',
	],
	[
		'icon' => 'brand-facebook',
		'label' => esc_html__('Facebook', 'cordyceps'),
		'value' => $data['contact_facebook'],
		'type' => 'url',
	],
	[
		'icon' => 'clock',
		'label' => esc_html__('Giờ làm việc', 'cordyceps'),
		'value' => $data['contact_working_time'],
		'type' => 'text',
	],
];

$has_info = !empty($data['contact_title'])
	|| array_filter(array_column($info_items, 'value'))
	|| !empty($data['contact_brochure_url']);
?>

<section class="<?php echo esc_attr($_class); ?>" aria-label="<?php esc_attr_e('Liên hệ', 'cordyceps'); ?>">
	<div class="contact-page-section__inner container">
		<div class="contact-page-section__grid">
			<?php if ($has_info) : ?>
				<aside class="contact-page-section__info contact-info-card" aria-labelledby="contact-info-title">
					<div class="contact-info-card__inner">
						<?php if (!empty($data['contact_title'])) : ?>
							<h2 id="contact-info-title" class="contact-info-card__title">
								<?php echo esc_html($data['contact_title']); ?>
							</h2>
						<?php endif; ?>

						<ul class="contact-info-card__list" role="list">
							<?php foreach ($info_items as $item) : ?>
								<?php if (empty($item['value'])) {
									continue;
								} ?>
								<li class="contact-info-card__item">
									<span class="contact-info-card__icon" aria-hidden="true">
										<?php echo cordyceps_get_svg_icon($item['icon']); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
									</span>
									<div class="contact-info-card__content">
										<span class="contact-info-card__label"><?php echo esc_html($item['label']); ?></span>
										<?php if ('tel' === $item['type']) : ?>
											<a class="contact-info-card__value" href="<?php echo esc_url('tel:' . preg_replace('/\s+/', '', $item['value'])); ?>">
												<?php echo esc_html($item['value']); ?>
											</a>
										<?php elseif ('email' === $item['type']) : ?>
											<a class="contact-info-card__value" href="<?php echo esc_url('mailto:' . sanitize_email($item['value'])); ?>">
												<?php echo esc_html($item['value']); ?>
											</a>
										<?php elseif ('url' === $item['type']) : ?>
											<?php
											$link_label = 'brand-facebook' === $item['icon']
												? __('Facebook', 'cordyceps')
												: $item['value'];
											?>
											<a class="contact-info-card__value" href="<?php echo esc_url($item['value']); ?>" target="_blank" rel="noopener noreferrer">
												<?php echo esc_html($link_label); ?>
											</a>
										<?php else : ?>
											<div class="contact-info-card__value"><?php echo wp_kses_post(wpautop($item['value'])); ?></div>
										<?php endif; ?>
									</div>
								</li>
							<?php endforeach; ?>
						</ul>

						<?php if (!empty($data['contact_brochure_url'])) : ?>
							<div class="contact-info-card__brochure">
								<a
									class="contact-info-card__brochure-btn"
									href="<?php echo esc_url($data['contact_brochure_url']); ?>"
									download="<?php echo esc_attr($data['contact_brochure_filename']); ?>"
								>
									<span class="contact-info-card__brochure-icon" aria-hidden="true">
										<?php echo cordyceps_get_svg_icon('download'); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
									</span>
									<span class="contact-info-card__brochure-text"><?php esc_html_e('TẢI BROCHURE', 'cordyceps'); ?></span>
								</a>
							</div>
						<?php endif; ?>
					</div>
				</aside>
			<?php endif; ?>

			<?php if (!empty($data['contact_map_iframe'])) : ?>
				<div class="contact-page-section__map contact-map" aria-label="<?php esc_attr_e('Bản đồ', 'cordyceps'); ?>">
					<div class="contact-map__frame">
						<?php echo $data['contact_map_iframe']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					</div>
				</div>
			<?php endif; ?>

			<?php if (!empty($data['contact_form_id']) && function_exists('wpcf7_contact_form')) : ?>
				<div class="contact-page-section__form contact-form-panel" aria-label="<?php esc_attr_e('Form liên hệ', 'cordyceps'); ?>">
					<div class="contact-form-panel__inner">
						<?php
						echo do_shortcode(
							'[contact-form-7 id="' . esc_attr($data['contact_form_id']) . '"]'
						); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
						?>
					</div>
				</div>
			<?php endif; ?>
		</div>
	</div>
</section>
