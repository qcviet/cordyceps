<?php

/**
 * Product single: long-form content (the_content).
 *
 * @package cordyceps
 * @author biogreen
 * @since 0.0.1
 */

$data = wp_parse_args($args, [
	'content' => '',
]);

if (empty($data['content'])) {
	return;
}
?>

<section class="product-content" aria-labelledby="product-content-title">
	<div class="product-content__inner container">
		<header class="product-content__header">
			<h2 id="product-content-title" class="product-content__title">
				<span class="product-content__title-accent" aria-hidden="true"></span>
				<span class="product-content__title-text"><?php esc_html_e('Chi tiết sản phẩm', 'cordyceps'); ?></span>
			</h2>
		</header>
		<div class="product-content__body entry-content cordyceps-rich-content">
			<?php echo $data['content']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- filtered via the_content. ?>
		</div>
	</div>
</section>
