<?php

/**
 * Core Block: Post card (image, date, title, excerpt).
 *
 * @package cordyceps
 * @author biogreen
 * @since 0.0.1
 */

$data = wp_parse_args($args, [
	'post_id' => 0,
	'class' => '',
	'show_cta' => false,
	'cta_text' => '',
]);

$post_id = absint($data['post_id']);

if ($post_id < 1) {
	return;
}

$permalink = get_permalink($post_id);
$title = get_the_title($post_id);
$thumbnail_id = (int) get_post_thumbnail_id($post_id);
if (!$permalink || !$title) {
	return;
}

$excerpt = cordyceps_get_post_excerpt_plain($post_id, 16);

$date_label = date_i18n('m/Y', (int) get_post_time('U', true, $post_id));

$cta_text = !empty($data['cta_text'])
	? $data['cta_text']
	: __('Xem thêm', 'cordyceps');

$_class = 'news-section__card';
$_class .= !empty($data['class']) ? ' ' . esc_attr($data['class']) : '';
?>

<article class="<?php echo esc_attr($_class); ?>">
	<a class="news-section__card-link" href="<?php echo esc_url($permalink); ?>">
		<div class="news-section__card-media">
			<time class="news-section__card-date" datetime="<?php echo esc_attr(get_the_date('c', $post_id)); ?>">
				<?php echo esc_html($date_label); ?>
			</time>
			<?php if ($thumbnail_id) : ?>
				<?php
				get_template_part('templates/core-blocks/image', null, [
					'image_id' => $thumbnail_id,
					'image_size' => 'large',
					'lazyload' => true,
					'class' => 'news-section__card-image-figure image--cover',
					'image_class' => 'news-section__card-image-img',
				]);
				?>
			<?php else : ?>
				<span class="news-section__card-image-placeholder" aria-hidden="true"></span>
			<?php endif; ?>
		</div>
		<div class="news-section__card-body">
			<h3 class="news-section__card-title"><?php echo esc_html($title); ?></h3>
			<?php if ('' !== trim($excerpt)) : ?>
				<p class="news-section__card-excerpt"><?php echo esc_html($excerpt); ?></p>
			<?php endif; ?>
			<?php if (!empty($data['show_cta'])) : ?>
				<span class="news-page-card__cta">
					<span class="news-page-card__cta-text"><?php echo esc_html($cta_text); ?></span>
					<span class="news-page-card__cta-icon" aria-hidden="true"><?php echo cordyceps_get_svg_icon('chevron-right'); ?></span>
				</span>
			<?php endif; ?>
		</div>
	</a>
</article>
