<?php

/**
 * AJAX handlers for featured product category filter.
 *
 * @package cordyceps
 * @author biogreen
 * @since 0.0.1
 */

if (!defined('ABSPATH')) {
	exit;
}

/**
 * Verify AJAX nonce for featured product requests.
 *
 * @return bool
 */
function cordyceps_featured_product_verify_ajax()
{
	$nonce = isset($_POST['nonce']) ? sanitize_text_field(wp_unslash($_POST['nonce'])) : '';

	return (bool) wp_verify_nonce($nonce, 'cordyceps_featured_product');
}

/**
 * AJAX: return HTML product cards for a category.
 */
function cordyceps_ajax_filter_featured_products()
{
	if (!cordyceps_featured_product_verify_ajax()) {
		wp_send_json_error(
			[
				'message' => esc_html__('Invalid request.', 'cordyceps'),
			],
			403
		);
	}

	$category_id = isset($_POST['category_id']) ? absint($_POST['category_id']) : 0;
	$scope_raw = isset($_POST['scope_term_ids']) ? sanitize_text_field(wp_unslash($_POST['scope_term_ids'])) : '';
	$scope_term_ids = [];

	if ('' !== $scope_raw) {
		$scope_term_ids = array_filter(array_map('absint', explode(',', $scope_raw)));
	}

	if ($category_id < 1) {
		$query = cordyceps_query_all_featured_products($scope_term_ids);
		$html = cordyceps_render_featured_product_cards($query);

		wp_send_json_success(
			[
				'html' => $html,
				'category_id' => 0,
			]
		);
	}

	$term = get_term($category_id, cordyceps_featured_product_taxonomy());

	if (!$term instanceof WP_Term || is_wp_error($term)) {
		wp_send_json_error(
			[
				'message' => esc_html__('Category not found.', 'cordyceps'),
			],
			404
		);
	}

	$query = cordyceps_query_featured_products($category_id);
	$html = cordyceps_render_featured_product_cards($query);

	wp_send_json_success(
		[
			'html' => $html,
			'category_id' => $category_id,
		]
	);
}

add_action('wp_ajax_cordyceps_filter_featured_products', 'cordyceps_ajax_filter_featured_products');
add_action('wp_ajax_nopriv_cordyceps_filter_featured_products', 'cordyceps_ajax_filter_featured_products');
