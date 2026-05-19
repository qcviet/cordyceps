<?php

/**
 * Single template: Product (PDP).
 *
 * @package cordyceps
 * @author biogreen
 * @since 0.0.1
 */

get_header();

while (have_posts()) :
	the_post();

	$data = cordyceps_get_product_single_data(get_the_ID());
	$related_ids = cordyceps_get_product_related_ids($data, 4);
	?>
<main
	id="primary"
	class="product-single"
	data-block="product-single"
	data-share-url="<?php echo esc_url($data['permalink'] ?? ''); ?>"
	data-share-title="<?php echo esc_attr($data['title'] ?? ''); ?>"
	data-share-text="<?php echo esc_attr(!empty($data['short_description']) ? wp_strip_all_tags($data['short_description']) : ($data['title'] ?? '')); ?>"
	data-share-copied-text="<?php echo esc_attr__('Đã sao chép liên kết!', 'cordyceps'); ?>"
	data-share-unsupported-text="<?php echo esc_attr__('Chức năng này cần mở trên điện thoại (Chrome/Safari) qua HTTPS.', 'cordyceps'); ?>"
>
	<?php
	get_template_part('templates/product/product-hero', null, $data);
	get_template_part('templates/product/product-content', null, $data);

	if (!empty($related_ids)) {
		get_template_part(
			'templates/product/product-related',
			null,
			[
				'related_ids' => $related_ids,
			]
		);
	}
	?>
</main>
	<?php
endwhile;

get_footer();
