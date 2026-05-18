<?php

/**
 * Block: News page archive grid + pagination.
 *
 * @package cordyceps
 * @author biogreen
 * @since 0.0.1
 */

$data = wp_parse_args($args, [
	'class' => '',
	'query' => null,
]);

$query = $data['query'] instanceof WP_Query ? $data['query'] : cordyceps_query_news_page_posts();
$post_ids = cordyceps_get_query_post_ids($query);

$_class = 'news-page-archive';
$_class .= !empty($data['class']) ? ' ' . esc_attr($data['class']) : '';
?>

<section class="news-page-archive-section" aria-label="<?php esc_attr_e('Danh sách tin tức', 'cordyceps'); ?>">
	<div class="<?php echo esc_attr($_class); ?>">
		<div class="news-page-archive__inner container">
			<?php if (!empty($post_ids)) : ?>
				<div class="news-page-archive__grid">
					<?php foreach ($post_ids as $post_id) : ?>
						<?php
						get_template_part('templates/core-blocks/post-card', null, [
							'post_id' => $post_id,
							'class' => 'news-page-card',
							'show_cta' => true,
						]);
						?>
					<?php endforeach; ?>
				</div>

				<?php cordyceps_render_news_page_pagination($query); ?>
			<?php else : ?>
				<p class="news-page-archive__empty text-center">
					<?php esc_html_e('Chưa có bài viết nào.', 'cordyceps'); ?>
				</p>
			<?php endif; ?>
		</div>
	</div>
</section>
