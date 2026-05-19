<?php

/**
 * Block: Search results (products first, posts with pagination).
 *
 * @package cordyceps
 * @author biogreen
 * @since 0.0.1
 */

$data = wp_parse_args($args, [
	'class' => '',
	'search_term' => '',
	'show_products' => true,
	'product_ids' => [],
	'post_ids' => [],
	'posts_query' => null,
	'has_results' => false,
	'has_products' => false,
	'has_posts' => false,
]);

$search_term = trim((string) $data['search_term']);
$product_ids = is_array($data['product_ids']) ? $data['product_ids'] : [];
$post_ids = is_array($data['post_ids']) ? $data['post_ids'] : [];
$posts_query = $data['posts_query'] instanceof WP_Query ? $data['posts_query'] : null;
$show_products = !empty($data['show_products']) && !empty($product_ids);
$show_posts = !empty($post_ids);

$_class = 'search-results';
$_class .= !empty($data['class']) ? ' ' . esc_attr($data['class']) : '';

if ('' === $search_term) {
	return;
}
?>

<section class="search-results-section" aria-label="<?php esc_attr_e('Kết quả tìm kiếm', 'cordyceps'); ?>">
	<div class="<?php echo esc_attr($_class); ?>">
		<div class="search-results__inner container">
			<?php if (!$data['has_results']) : ?>
				<p class="search-results__empty text-center">
					<?php esc_html_e('Không tìm thấy sản phẩm hay bài viết nào. Hãy thử từ khóa ngắn hơn hoặc từ đồng nghĩa.', 'cordyceps'); ?>
				</p>
			<?php else : ?>
				<?php if ($show_products) : ?>
					<div class="search-results__group search-results__group--products">
						<header class="search-results__header">
							<h2 class="search-results__heading">
								<?php esc_html_e('Sản phẩm', 'cordyceps'); ?>
							</h2>
						</header>

						<div class="search-results__products fp__grid" role="list">
							<?php foreach ($product_ids as $product_id) : ?>
								<?php
								get_template_part('templates/core-blocks/product-card', null, [
									'post_id' => $product_id,
								]);
								?>
							<?php endforeach; ?>
						</div>
					</div>
				<?php endif; ?>

				<?php if ($show_posts) : ?>
					<div class="search-results__group search-results__group--posts<?php echo $show_products ? ' search-results__group--after-products' : ''; ?>">
						<header class="search-results__header">
							<h2 class="search-results__heading">
								<?php esc_html_e('Bài viết', 'cordyceps'); ?>
							</h2>
						</header>

						<div class="search-results__posts news-page-archive__grid">
							<?php foreach ($post_ids as $post_id) : ?>
								<?php
								get_template_part('templates/core-blocks/post-card', null, [
									'post_id' => $post_id,
									'class' => 'news-page-card search-results__post-card',
									'show_cta' => true,
								]);
								?>
							<?php endforeach; ?>
						</div>

						<?php cordyceps_render_search_pagination($posts_query); ?>
					</div>
				<?php endif; ?>
			<?php endif; ?>
		</div>
	</div>
</section>
