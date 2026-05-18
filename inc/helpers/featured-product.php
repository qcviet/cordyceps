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
 * Build a tax_query clause for product-category (includes child terms).
 *
 * @param int|int[] $term_ids  Term ID or list of IDs.
 * @param string    $operator  Tax query operator (IN, AND, …).
 * @return array
 */
function cordyceps_featured_product_tax_clause($term_ids, $operator = 'IN')
{
	$term_ids = cordyceps_normalize_featured_product_term_ids($term_ids);

	return [
		'taxonomy' => cordyceps_featured_product_taxonomy(),
		'field' => 'term_id',
		'terms' => $term_ids,
		'operator' => $operator,
		'include_children' => true,
	];
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
	if (empty($value) && !is_numeric($value)) {
		return [];
	}

	if (is_string($value)) {
		$value = trim($value);

		if ('' === $value) {
			return [];
		}

		if (false !== strpos($value, ',')) {
			$value = array_map('trim', explode(',', $value));
		} else {
			$value = [$value];
		}
	}

	if (!is_array($value)) {
		$value = [$value];
	}

	$ids = [];

	foreach ($value as $item) {
		if ($item instanceof WP_Term) {
			$ids[] = (int) $item->term_id;
			continue;
		}

		if (is_object($item) && isset($item->term_id)) {
			$ids[] = absint($item->term_id);
			continue;
		}

		if (is_numeric($item)) {
			$id = absint($item);

			if ($id > 0) {
				$ids[] = $id;
			}
			continue;
		}

		if (is_string($item) && is_numeric(trim($item))) {
			$id = absint($item);

			if ($id > 0) {
				$ids[] = $id;
			}
		}
	}

	$ids = array_filter($ids);

	return array_values(array_unique($ids));
}

/**
 * Merge category field values from flexible block data (current + legacy keys).
 *
 * @param array $data Block data from flexible layout.
 * @return int[]
 */
function cordyceps_resolve_featured_product_scope_ids(array $data)
{
	$sources = [];

	if (!empty($data['category_items'])) {
		$sources[] = $data['category_items'];
	}

	if (!empty($data['category_product'])) {
		$sources[] = $data['category_product'];
	}

	$ids = [];

	foreach ($sources as $source) {
		$ids = array_merge($ids, cordyceps_normalize_featured_product_term_ids($source));
	}

	$ids = array_values(array_unique(array_filter($ids)));

	return apply_filters('cordyceps_featured_product_scope_ids', $ids, $data);
}

/**
 * Get all product-category terms that have published products.
 *
 * @return WP_Term[]
 */
function cordyceps_get_all_featured_product_categories()
{
	$terms = get_terms(
		[
			'taxonomy' => cordyceps_featured_product_taxonomy(),
			'hide_empty' => true,
			'orderby' => 'name',
			'order' => 'ASC',
		]
	);

	if (is_wp_error($terms) || empty($terms)) {
		return [];
	}

	return array_values(
		array_filter(
			$terms,
			static function ($term) {
				return $term instanceof WP_Term;
			}
		)
	);
}

/**
 * Get WP_Term objects for tab display (ACF selection or all non-empty terms).
 *
 * @param int[] $selected_ids Term IDs chosen in ACF (may be empty).
 * @return WP_Term[]
 */
function cordyceps_get_featured_product_tab_categories(array $selected_ids)
{
	$selected_ids = cordyceps_normalize_featured_product_term_ids($selected_ids);

	if (!empty($selected_ids)) {
		return cordyceps_get_featured_product_categories($selected_ids);
	}

	return cordyceps_get_all_featured_product_categories();
}

/**
 * Get WP_Term objects for selected category IDs (preserves selection order).
 *
 * @param mixed $term_ids Raw ACF value or list of IDs.
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
 * Scope IDs used for queries and AJAX (selected terms, or all tab term IDs).
 *
 * @param WP_Term[] $tab_categories Categories rendered as tabs.
 * @param int[]     $selected_ids   Raw ACF selection.
 * @return int[]
 */
function cordyceps_get_featured_product_scope_ids_for_query(array $tab_categories, array $selected_ids)
{
	$selected_ids = cordyceps_normalize_featured_product_term_ids($selected_ids);

	if (!empty($selected_ids)) {
		return $selected_ids;
	}

	if (empty($tab_categories)) {
		return [];
	}

	$scope_ids = [];

	foreach ($tab_categories as $term) {
		if ($term instanceof WP_Term) {
			$scope_ids[] = (int) $term->term_id;
		}
	}

	return array_values(array_unique($scope_ids));
}

/**
 * Whether a category tab is allowed for the current block scope.
 *
 * @param int   $category_id  Requested term ID (0 = all).
 * @param int[] $scope_ids    Allowed scope; empty means no restriction.
 * @return bool
 */
function cordyceps_featured_product_category_is_allowed($category_id, array $scope_ids)
{
	$category_id = absint($category_id);

	if ($category_id < 1) {
		return true;
	}

	$scope_ids = cordyceps_normalize_featured_product_term_ids($scope_ids);

	if (empty($scope_ids)) {
		return true;
	}

	if (in_array($category_id, $scope_ids, true)) {
		return true;
	}

	$taxonomy = cordyceps_featured_product_taxonomy();

	foreach ($scope_ids as $scope_id) {
		if (term_is_ancestor_of($scope_id, $category_id, $taxonomy)) {
			return true;
		}

		if (term_is_ancestor_of($category_id, $scope_id, $taxonomy)) {
			return true;
		}
	}

	return false;
}

/**
 * Query featured products (optionally scoped to category term IDs).
 *
 * @param int[] $scope_term_ids Term IDs; empty = all published products.
 * @param array $args           Optional WP_Query overrides.
 * @return WP_Query
 */
function cordyceps_query_all_featured_products($scope_term_ids = [], $args = [])
{
	$scope_term_ids = cordyceps_normalize_featured_product_term_ids($scope_term_ids);

	$base = [];

	if (!empty($scope_term_ids)) {
		$base['tax_query'] = [cordyceps_featured_product_tax_clause($scope_term_ids, 'IN')];
	}

	return new WP_Query(cordyceps_prepare_featured_product_query_args(wp_parse_args($args, $base)));
}

/**
 * Query featured products in a single category (includes child terms).
 *
 * @param int   $category_id Term ID.
 * @param array $args        Optional WP_Query overrides.
 * @return WP_Query
 */
function cordyceps_query_featured_products($category_id, $args = [])
{
	$category_id = absint($category_id);

	$base = [
		'tax_query' => [cordyceps_featured_product_tax_clause($category_id, 'IN')],
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
