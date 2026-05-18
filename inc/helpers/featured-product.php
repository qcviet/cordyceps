<?php

/**
 * Featured product block helpers.
 *
 * @package cordyceps
 * @author biogreen
 * @since 0.0.1
 */

if (!defined('ABSPATH')) {
	exit;
}

/**
 * Product category taxonomy slug used by this theme.
 *
 * @return string
 */
function cordyceps_featured_product_taxonomy()
{
	return 'product-category';
}

/**
 * Normalize ACF taxonomy field value to term IDs.
 *
 * @param mixed $value Term ID or list of term IDs from ACF.
 * @return int[]
 */
function cordyceps_normalize_featured_product_term_ids($value)
{
	if (empty($value)) {
		return [];
	}

	if (!is_array($value)) {
		$value = [$value];
	}

	$ids = array_map('absint', $value);
	$ids = array_filter($ids);

	return array_values(array_unique($ids));
}

/**
 * Get WP_Term objects for selected category IDs (preserves ACF order).
 *
 * @param mixed $term_ids Raw ACF value.
 * @return WP_Term[]
 */
function cordyceps_get_featured_product_categories($term_ids)
{
	$ids = cordyceps_normalize_featured_product_term_ids($term_ids);
	$terms = [];

	foreach ($ids as $term_id) {
		$term = get_term($term_id, cordyceps_featured_product_taxonomy());

		if ($term instanceof WP_Term && !is_wp_error($term)) {
			$terms[] = $term;
		}
	}

	return $terms;
}

/**
 * Query all featured products (optionally scoped to selected category IDs).
 *
 * @param int[] $scope_term_ids Term IDs from ACF; empty = all published products.
 * @param array $args           Optional WP_Query overrides.
 * @return WP_Query
 */
function cordyceps_query_all_featured_products($scope_term_ids = [], $args = [])
{
	$scope_term_ids = cordyceps_normalize_featured_product_term_ids($scope_term_ids);

	$defaults = [
		'post_type' => 'product',
		'post_status' => 'publish',
		'posts_per_page' => -1,
		'orderby' => 'menu_order title',
		'order' => 'ASC',
		'no_found_rows' => true,
	];

	if (!empty($scope_term_ids)) {
		$defaults['tax_query'] = [
			[
				'taxonomy' => cordyceps_featured_product_taxonomy(),
				'field' => 'term_id',
				'terms' => $scope_term_ids,
				'operator' => 'IN',
			],
		];
	}

	return new WP_Query(wp_parse_args($args, $defaults));
}

/**
 * Query WooCommerce products in a category.
 *
 * @param int   $category_id Term ID.
 * @param array $args        Optional WP_Query overrides.
 * @return WP_Query
 */
function cordyceps_query_featured_products($category_id, $args = [])
{
	$category_id = absint($category_id);

	$defaults = [
		'post_type' => 'product',
		'post_status' => 'publish',
		'posts_per_page' => -1,
		'orderby' => 'menu_order title',
		'order' => 'ASC',
		'no_found_rows' => true,
		'tax_query' => [
			[
				'taxonomy' => cordyceps_featured_product_taxonomy(),
				'field' => 'term_id',
				'terms' => $category_id,
			],
		],
	];

	return new WP_Query(wp_parse_args($args, $defaults));
}

/**
 * Render product cards HTML for a query.
 *
 * @param WP_Query|null $query Product query.
 * @return string
 */
function cordyceps_render_featured_product_cards($query)
{
	if (!$query instanceof WP_Query || !$query->have_posts()) {
		return '';
	}

	ob_start();

	while ($query->have_posts()) {
		$query->the_post();
		get_template_part('templates/core-blocks/product-card', null, [
			'post_id' => get_the_ID(),
		]);
	}

	wp_reset_postdata();

	return (string) ob_get_clean();
}
