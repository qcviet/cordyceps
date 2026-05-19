<?php

/**
 * Block: Featured product
 *
 * @package cordyceps
 * @author biogreen
 * @since 0.0.1
 */

$data = wp_parse_args($args, [
	'class' => '',
	'title' => '',
	'description' => '',
	'category_items' => [],
	'category_product' => [],
	'active_category_id' => 0,
]);

$selected_ids = cordyceps_resolve_featured_product_scope_ids($data);
$tab_categories = cordyceps_get_featured_product_tab_categories($selected_ids);
$scope_ids = cordyceps_get_featured_product_scope_ids_for_query($tab_categories, $selected_ids);
$active_id = !empty($data['active_category_id']) ? absint($data['active_category_id']) : 0;
$initial_query = $active_id > 0
	? cordyceps_query_featured_products($active_id)
	: cordyceps_query_all_featured_products($scope_ids);
$scope_attr = !empty($scope_ids) ? implode(',', $scope_ids) : '';

$_class = 'fp';
$_class .= !empty($data['class']) ? ' ' . esc_attr($data['class']) : '';
?>

<section
	class="fp-section"
	data-block="featured-product"
	data-fp-scope="<?php echo esc_attr($scope_attr); ?>"
>
	<div class="<?php echo esc_attr($_class); ?> py-2">
		<div class="fp__inner container">
			<?php if (!empty($data['title'])) : ?>
				<header class="fp__header text-center">
					<h2 class="fp__title"><?php echo esc_html($data['title']); ?></h2>
					<div class="fp__title-ornament d-flex align-items-center justify-content-center" aria-hidden="true">
						<span class="fp__title-line fp__title-line--left"></span>
						<span class="fp__title-line-icon"><?php echo cordyceps_get_svg_icon('plant'); ?></span>
						<span class="fp__title-line fp__title-line--right"></span>
					</div>
					<?php if (!empty($data['description'])) : ?>
						<div class="fp__description"><?php echo wp_kses_post($data['description']); ?></div>
					<?php endif; ?>
				</header>
			<?php endif; ?>

			<?php
			get_template_part(
				'templates/blocks/featured-product/category-tabs',
				null,
				[
					'categories' => $tab_categories,
					'active_id' => $active_id,
				]
			);

			get_template_part(
				'templates/blocks/featured-product/products-grid',
				null,
				[
					'query' => $initial_query,
				]
			);
			?>
		</div>
	</div>
</section>
