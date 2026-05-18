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
 * Hard upper bound — prevents accidental/unfiltered queries from loading the full catalog.
 *
 * @return int
 */
function cordyceps_featured_product_max_posts_per_page()
{
	$max = (int) apply_filters('cordyceps_featured_product_max_posts_per_page', 24);

	return max(1, $max);
}

/**
 * Products per featured-product grid / AJAX response (clamped to max).
 *
 * @return int
 */
function cordyceps_featured_product_posts_per_page()
{
	$default = 12;
	$limit = (int) apply_filters('cordyceps_featured_product_posts_per_page', $default);

	return cordyceps_clamp_posts_per_page($limit, $default, cordyceps_featured_product_max_posts_per_page());
}

/**
 * Normalize and enforce safe WP_Query args for featured products.
 *
 * @param array $overrides Optional query overrides (tax_query, etc.).
 * @return array
 */
function cordyceps_prepare_featured_product_query_args(array $overrides = [])
{
	return cordyceps_prepare_list_query_args(
		wp_parse_args($overrides, [
			'post_type' => 'product',
			'orderby' => 'menu_order title',
			'order' => 'ASC',
		]),
		cordyceps_featured_product_posts_per_page(),
		cordyceps_featured_product_max_posts_per_page()
	);
}

/**
 * Extract product IDs from a featured-product query result.
 *
 * @param WP_Query|null $query Product query.
 * @return int[]
 */
function cordyceps_get_featured_product_ids_from_query($query)
{
	return cordyceps_get_query_post_ids($query);
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
 * Query featured products (optionally scoped to selected category IDs).
 *
 * @param int[] $scope_term_ids Term IDs from ACF; empty = all published products.
 * @param array $args           Optional WP_Query overrides (cannot use -1 for posts_per_page).
 * @return WP_Query
 */
function cordyceps_query_all_featured_products($scope_term_ids = [], $args = [])
{
	$scope_term_ids = cordyceps_normalize_featured_product_term_ids($scope_term_ids);

	$base = [];

	if (!empty($scope_term_ids)) {
		$base['tax_query'] = [
			[
				'taxonomy' => cordyceps_featured_product_taxonomy(),
				'field' => 'term_id',
				'terms' => $scope_term_ids,
				'operator' => 'IN',
			],
		];
	}

	return new WP_Query(cordyceps_prepare_featured_product_query_args(wp_parse_args($args, $base)));
}

/**
 * Query featured products in a single category.
 *
 * @param int   $category_id Term ID.
 * @param array $args        Optional WP_Query overrides (cannot use -1 for posts_per_page).
 * @return WP_Query
 */
function cordyceps_query_featured_products($category_id, $args = [])
{
	$category_id = absint($category_id);

	$base = [
		'tax_query' => [
			[
				'taxonomy' => cordyceps_featured_product_taxonomy(),
				'field' => 'term_id',
				'terms' => $category_id,
			],
		],
	];

	return new WP_Query(cordyceps_prepare_featured_product_query_args(wp_parse_args($args, $base)));
}

/**
 * Output product cards for a featured-product query (no output buffering).
 *
 * @param WP_Query|null $query Product query.
 * @return void
 */
function cordyceps_loop_featured_product_cards($query)
{
	$product_ids = cordyceps_get_featured_product_ids_from_query($query);

	if (empty($product_ids)) {
		return;
	}

	foreach ($product_ids as $post_id) {
		get_template_part('templates/core-blocks/product-card', null, [
			'post_id' => $post_id,
		]);
	}
}

/**
 * Return product cards HTML for AJAX (bounded query; small buffer only).
 *
 * @param WP_Query|null $query Product query.
 * @return string
 */
function cordyceps_get_featured_product_cards_html($query)
{
	if (empty(cordyceps_get_featured_product_ids_from_query($query))) {
		return '';
	}

	ob_start();
	cordyceps_loop_featured_product_cards($query);

	return (string) ob_get_clean();
}

/**
 * @deprecated 0.0.2 Use cordyceps_get_featured_product_cards_html().
 *
 * @param WP_Query|null $query Product query.
 * @return string
 */
function cordyceps_render_featured_product_cards($query)
{
	return cordyceps_get_featured_product_cards_html($query);
}
