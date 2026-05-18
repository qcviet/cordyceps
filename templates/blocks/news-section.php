<?php

/**
 * Block: News section
 *
 * @package cordyceps
 * @author biogreen
 * @since 0.0.1
 */

$data = wp_parse_args($args, [
	'class' => '',
	'title' => '',
]);

$posts_query = cordyceps_query_latest_posts();
$has_posts = !empty(cordyceps_get_query_post_ids($posts_query));

$_class = 'news-section';
$_class .= !empty($data['class']) ? ' ' . esc_attr($data['class']) : '';
?>

<section class="news-section-section" data-block="news-section" aria-label="<?php esc_attr_e('Tin tức mới nhất', 'cordyceps'); ?>">
	<div class="<?php echo esc_attr($_class); ?>">
		<div class="news-section__inner container">
			<?php if (!empty($data['title'])) : ?>
				<header class="news-section__header text-center">
					<h2 class="news-section__title"><?php echo esc_html($data['title']); ?></h2>
					<div class="news-section__title-ornament d-flex align-items-center justify-content-center" aria-hidden="true">
						<span class="news-section__title-line news-section__title-line--left"></span>
						<span class="news-section__title-line-icon"><?php echo cordyceps_get_svg_icon('plant'); ?></span>
						<span class="news-section__title-line news-section__title-line--right"></span>
					</div>
				</header>
			<?php endif; ?>

			<?php if ($has_posts) : ?>
				<div class="news-section__slider-wrap">
					<div class="news-section__slider swiper js-news-section-swiper">
						<div class="swiper-wrapper">
							<?php foreach (cordyceps_get_query_post_ids($posts_query) as $post_id) : ?>
								<div class="swiper-slide news-section__slide">
									<?php
									get_template_part('templates/core-blocks/post-card', null, [
										'post_id' => $post_id,
									]);
									?>
								</div>
							<?php endforeach; ?>
						</div>
					</div>
					<button
						type="button"
						class="news-section__nav news-section__nav--prev js-news-section-prev swiper-button-prev"
						aria-label="<?php esc_attr_e('Bài viết trước', 'cordyceps'); ?>"
					></button>
					<button
						type="button"
						class="news-section__nav news-section__nav--next js-news-section-next swiper-button-next"
						aria-label="<?php esc_attr_e('Bài viết sau', 'cordyceps'); ?>"
					></button>
				</div>
			<?php else : ?>
				<p class="news-section__empty text-center"><?php esc_html_e('Chưa có bài viết nào.', 'cordyceps'); ?></p>
			<?php endif; ?>
		</div>
	</div>
</section>
