<?php

/**
 * Block: News page hero (full-width background).
 *
 * @package cordyceps
 * @author biogreen
 * @since 0.0.1
 */

$data = wp_parse_args($args, [
	'class' => '',
	'hero_title' => '',
	'hero_description' => '',
	'hero_background_image' => '',
]);

$_class = 'news-page-hero';
$_class .= !empty($data['class']) ? ' ' . esc_attr($data['class']) : '';

$has_content = !empty($data['hero_title'])
	|| !empty($data['hero_description'])
	|| !empty($data['hero_background_image']);

if (!$has_content) {
	return;
}
?>

<section class="news-page-hero-section" aria-labelledby="news-page-hero-title">
	<div class="<?php echo esc_attr($_class); ?>">
		<?php if (!empty($data['hero_background_image'])) : ?>
			<div class="news-page-hero__bg" aria-hidden="true">
				<?php
				get_template_part('templates/core-blocks/image', null, [
					'image_id' => $data['hero_background_image'],
					'image_size' => 'full',
					'lazyload' => false,
					'class' => 'news-page-hero__bg-figure',
					'image_class' => 'news-page-hero__bg-img',
				]);
				?>
			</div>
		<?php endif; ?>

		<div class="news-page-hero__scrim" aria-hidden="true"></div>

		<div class="news-page-hero__inner">
			<?php if (!empty($data['hero_title'])) : ?>
				<h1 id="news-page-hero-title" class="news-page-hero__title">
					<?php echo esc_html($data['hero_title']); ?>
				</h1>
			<?php endif; ?>

			<?php if (!empty($data['hero_description'])) : ?>
				<div class="news-page-hero__description">
					<?php echo wp_kses_post($data['hero_description']); ?>
				</div>
			<?php endif; ?>
		</div>
	</div>
</section>
