<?php

/**
 * Block: Hero Slider
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
	'slider_items' => [],
]);

$slider_id = uniqid('hero-slider-', false);
$_class = 'hero-slider' . (!empty($data['class']) ? ' ' . esc_attr($data['class']) : '');
?>

<section class="hero-slider-section" aria-roledescription="carousel" aria-label="<?php esc_attr_e('Banner chính', 'cordyceps'); ?>">
	<div id="<?php echo esc_attr($slider_id); ?>" class="<?php echo esc_attr($_class); ?> position-relative" data-block="hero-slider">
		<div class="hero-slider__main swiper">
			<div class="hero-slider__wrapper swiper-wrapper">
				<?php
				if (!empty($data['slider_items'])) :
					foreach ($data['slider_items'] as $slide_index => $item) :
						$title_tag = (0 === (int) $slide_index) ? 'h1' : 'h2';
						?>
				<div class="hero-slider__slide swiper-slide">
					<div class="hero-slider__item">
						<div class="hero-slider__item-content">
							<?php if (!empty($item['subtitle'])) : ?>
								<p class="hero-slider__item-subtitle"><?php echo esc_html($item['subtitle']); ?></p>
							<?php endif; ?>

							<?php if (!empty($item['title'])) : ?>
								<<?php echo esc_attr($title_tag); ?> class="hero-slider__item-title-top"><?php echo esc_html($item['title']); ?></<?php echo esc_attr($title_tag); ?>>
							<?php endif; ?>

							<?php if (!empty($item['title_italic'])) : ?>
								<p class="hero-slider__item-title hero-slider__item-title--accent"><?php echo esc_html($item['title_italic']); ?></p>
							<?php endif; ?>

							<?php if (!empty($item['description'])) : ?>
								<div class="hero-slider__item-description"><?php echo wp_kses_post($item['description']); ?></div>
							<?php endif; ?>

							<div class="hero-slider__item-highlights" role="list">
								<div class="hero-slider__item-highlight" role="listitem">
									<span class="hero-slider__item-highlight-icon" aria-hidden="true"><?php echo cordyceps_get_svg_icon('leaf'); ?></span>
									<div class="hero-slider__item-highlight-text">
										<span class="hero-slider__item-highlight-title"><?php esc_html_e('100% Tự nhiên', 'cordyceps'); ?></span>
										<span class="hero-slider__item-highlight-desc"><?php esc_html_e('Không chất bảo quản', 'cordyceps'); ?></span>
									</div>
								</div>
								<div class="hero-slider__item-highlight" role="listitem">
									<span class="hero-slider__item-highlight-icon" aria-hidden="true"><?php echo cordyceps_get_svg_icon('chemistry'); ?></span>
									<div class="hero-slider__item-highlight-text">
										<span class="hero-slider__item-highlight-title"><?php esc_html_e('Công nghệ nuôi cấy', 'cordyceps'); ?></span>
										<span class="hero-slider__item-highlight-desc"><?php esc_html_e('Hiện đại', 'cordyceps'); ?></span>
									</div>
								</div>
								<div class="hero-slider__item-highlight" role="listitem">
									<span class="hero-slider__item-highlight-icon" aria-hidden="true"><?php echo cordyceps_get_svg_icon('shield'); ?></span>
									<div class="hero-slider__item-highlight-text">
										<span class="hero-slider__item-highlight-title"><?php esc_html_e('Kiểm định chất lượng', 'cordyceps'); ?></span>
										<span class="hero-slider__item-highlight-desc"><?php esc_html_e('Đảm bảo an toàn', 'cordyceps'); ?></span>
									</div>
								</div>
							</div>

							<?php if (!empty($item['button_url'])) : ?>
								<a class="hero-slider__item-link" href="<?php echo esc_url($item['button_url']); ?>">
									<span class="hero-slider__item-link-text"><?php esc_html_e('Khám phá ngay', 'cordyceps'); ?></span>
									<span class="hero-slider__item-link-icon" aria-hidden="true"><?php echo cordyceps_get_svg_icon('chevron-right'); ?></span>
								</a>
							<?php endif; ?>
						</div>

						<div class="hero-slider__item-media">
							<?php
							cordyceps_load_template_with_args(
								'templates/core-blocks/image',
								[
									'image_id' => $item['image'] ?? '',
									'image_size' => 'full',
									'lazyload' => true,
									'class' => 'hero-slider__item-media-image',
								]
							);
							?>
						</div>
					</div>
				</div>
						<?php
					endforeach;
				endif;
				?>
			</div>
			<button type="button" class="hero-slider__nav hero-slider__nav--prev swiper-button-prev" aria-label="<?php esc_attr_e('Slide trước', 'cordyceps'); ?>"></button>
			<button type="button" class="hero-slider__nav hero-slider__nav--next swiper-button-next" aria-label="<?php esc_attr_e('Slide sau', 'cordyceps'); ?>"></button>
		</div>
	</div>
</section>
