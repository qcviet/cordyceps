<?php

/**
 * Block: Home about
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
	'title' => '',
	'subtitle' => '',
	'description' => '',
	'background_image' => '',
	'button_url' => '',
]);
$_class = 'home-about';
$_class .= !empty($data['class']) ? esc_attr(' ' . $data['class']) : '';

?>

<section class="home-about-section">
	<div class="<?php echo esc_attr($_class); ?> position-relative">
		<?php if (!empty($data['background_image'])) : ?>
			<?php get_template_part('templates/core-blocks/image', null, [
				'image_id' => $data['background_image'],
				'image_size' => 'full',
				'lazyload' => true,
				'class' => 'home-about__media',
			]); ?>
		<?php endif; ?>

		<div class="home-about__inner container">
			<div class="home-about__content position-relative">
				<?php if (!empty($data['subtitle'])) : ?>
					<h4 class="home-about__subtitle">
						<?php echo esc_html($data['subtitle']); ?>
					</h4>
				<?php endif; ?>

				<?php if (!empty($data['title'])) : ?>
					<h1 class="home-about__title"><?php echo esc_html($data['title']); ?></h1>
				<?php endif; ?>

				<?php if (!empty($data['description'])) : ?>
					<div class="home-about__description">
						<?php echo wp_kses_post($data['description']); ?>
					</div>
				<?php endif; ?>

				<?php if (!empty($data['button_url'])) : ?>
					<div class="home-about__button">
						<a href="<?php echo esc_url($data['button_url']); ?>" class="home-about__button-link">
							<span class="home-about__button-text"><?php esc_html_e('Tìm hiểu thêm', 'cordyceps'); ?></span>
							<span class="home-about__button-icon" aria-hidden="true"><?php echo cordyceps_get_svg_icon('chevron-right'); ?></span>
						</a>
					</div>
				<?php endif; ?>
			</div>

		</div>
	</div>
</section>
