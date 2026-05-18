<?php

/**
 * Featured product: products grid wrapper.
 *
 * @package cordyceps
 * @author biogreen
 * @since 0.0.1
 *
 * @var array $args {
 *     @type WP_Query|null $query
 * }
 */

$args = wp_parse_args($args, [
	'query' => null,
]);

$query = $args['query'] instanceof WP_Query ? $args['query'] : null;
$has_products = $query instanceof WP_Query && $query->have_posts();
?>

<div class="fp__grid-wrapper" data-fp-grid-wrapper>
	<div class="fp__loading" data-fp-loading hidden aria-hidden="true">
		<span class="fp__loading-spinner" aria-hidden="true"></span>
		<span class="screen-reader-text"><?php esc_html_e('Đang tải sản phẩm…', 'cordyceps'); ?></span>
	</div>

	<div class="fp__grid" data-fp-grid role="list">
		<?php if ($has_products) : ?>
			<?php echo cordyceps_render_featured_product_cards($query);?>
		<?php else : ?>
			<p class="fp__empty"><?php esc_html_e('Chưa có sản phẩm trong danh mục này.', 'cordyceps'); ?></p>
		<?php endif; ?>
	</div>
</div>
