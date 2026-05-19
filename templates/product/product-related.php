<?php

/**
 * Product single: related products grid.
 *
 * @package cordyceps
 * @author biogreen
 * @since 0.0.1
 */

$data = wp_parse_args($args, [
	'related_ids' => [],
]);

$related_ids = cordyceps_normalize_product_ids($data['related_ids']);

if (empty($related_ids)) {
	return;
}

$related_count = min(count($related_ids), 4);
?>

<section class="product-related" aria-labelledby="product-related-title">
	<div class="product-related__inner container">
		<header class="product-related__header">
			<h2 id="product-related-title" class="product-related__title">
				<span class="product-related__title-accent" aria-hidden="true"></span>
				<span class="product-related__title-text"><?php esc_html_e('Sản phẩm liên quan', 'cordyceps'); ?></span>
			</h2>
		</header>
		<div class="product-related__grid product-related__grid--count-<?php echo esc_attr((string) $related_count); ?>">
			<?php foreach ($related_ids as $product_id) : ?>
				<?php
				get_template_part('templates/core-blocks/product-card', null, [
					'post_id' => $product_id,
					'class' => 'product-related__card',
				]);
				?>
			<?php endforeach; ?>
		</div>
	</div>
</section>
