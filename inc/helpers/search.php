<?php

/**
 * Site search helpers (products first, posts with pagination).
 *
 * @package cordyceps
 * @author biogreen
 * @since 0.0.1
 */

if (!defined('ABSPATH')) {
	exit;
}

/**
 * Sanitized search keyword from the request.
 *
 * @return string
 */
function cordyceps_search_get_term()
{
	$term = get_search_query(false);

	if (!is_string($term)) {
		return '';
	}

	return trim(sanitize_text_field(wp_unslash($term)));
}

/**
 * Current paged value for search post results.
 *
 * @return int
 */
function cordyceps_search_get_paged()
{
	$paged = get_query_var('paged');

	if (!$paged) {
		$paged = get_query_var('page');
	}

	return max(1, (int) $paged);
}

/**
 * @return int
 */
function cordyceps_search_products_per_page()
{
	$default = 12;
	$limit = (int) apply_filters('cordyceps_search_products_per_page', $default);
	$max = (int) apply_filters('cordyceps_search_products_max_per_page', 24);

	return cordyceps_clamp_posts_per_page($limit, $default, max($default, $max));
}

/**
 * @return int
 */
function cordyceps_search_posts_per_page()
{
	$default = 9;
	$limit = (int) apply_filters('cordyceps_search_posts_per_page', $default);
	$max = (int) apply_filters('cordyceps_search_posts_max_per_page', 24);

	return cordyceps_clamp_posts_per_page($limit, $default, max($default, $max));
}

/**
 * @param string $search_term Search keyword.
 * @param array  $overrides   Optional WP_Query overrides.
 * @return array
 */
function cordyceps_prepare_search_product_query_args($search_term, array $overrides = [])
{
	$search_term = trim((string) $search_term);

	$args = wp_parse_args($overrides, [
		'post_type' => 'product',
		'post_status' => 'publish',
		's' => $search_term,
		'posts_per_page' => cordyceps_search_products_per_page(),
		'orderby' => 'relevance',
		'order' => 'DESC',
		'ignore_sticky_posts' => true,
		'no_found_rows' => true,
		'fields' => 'ids',
		'update_post_meta_cache' => true,
		'update_post_term_cache' => false,
	]);

	$args['fields'] = 'ids';
	$args['no_found_rows'] = true;

	return $args;
}

/**
 * @param string $search_term Search keyword.
 * @param int    $paged       Page number.
 * @param array  $overrides   Optional WP_Query overrides.
 * @return array
 */
function cordyceps_prepare_search_post_query_args($search_term, $paged = 1, array $overrides = [])
{
	$search_term = trim((string) $search_term);
	$per_page = cordyceps_search_posts_per_page();

	$args = wp_parse_args($overrides, [
		'post_type' => 'post',
		'post_status' => 'publish',
		's' => $search_term,
		'posts_per_page' => $per_page,
		'paged' => max(1, (int) $paged),
		'orderby' => 'relevance',
		'order' => 'DESC',
		'ignore_sticky_posts' => true,
		'no_found_rows' => false,
		'fields' => 'ids',
		'update_post_meta_cache' => true,
		'update_post_term_cache' => false,
	]);

	$max_posts = (int) apply_filters('cordyceps_search_posts_max_per_page', 24);
	$max_posts = max($per_page, $max_posts);

	$args['posts_per_page'] = cordyceps_clamp_posts_per_page(
		isset($args['posts_per_page']) ? $args['posts_per_page'] : $per_page,
		$per_page,
		$max_posts
	);
	$args['paged'] = max(1, (int) $args['paged']);
	$args['fields'] = 'ids';
	$args['no_found_rows'] = false;

	return $args;
}

/**
 * @param string $search_term Search keyword.
 * @param array  $args        Optional WP_Query overrides.
 * @return WP_Query
 */
function cordyceps_query_search_products($search_term, $args = [])
{
	return new WP_Query(cordyceps_prepare_search_product_query_args($search_term, $args));
}

/**
 * @param string   $search_term Search keyword.
 * @param int|null $paged       Page number (defaults to current).
 * @param array    $args        Optional WP_Query overrides.
 * @return WP_Query
 */
function cordyceps_query_search_posts($search_term, $paged = null, $args = [])
{
	if (null === $paged) {
		$paged = cordyceps_search_get_paged();
	}

	return new WP_Query(
		cordyceps_prepare_search_post_query_args($search_term, $paged, $args)
	);
}

/**
 * Collect product and post queries for the search results template.
 *
 * Products render on page 1 only; posts are paginated by relevance.
 *
 * @param string $search_term Search keyword.
 * @return array<string, mixed>
 */
function cordyceps_get_search_results_data($search_term = '')
{
	$search_term = '' !== $search_term ? trim((string) $search_term) : cordyceps_search_get_term();
	$paged = cordyceps_search_get_paged();
	$show_products = $paged < 2;

	$products_query = null;
	$product_ids = [];

	if ($show_products && '' !== $search_term) {
		$products_query = cordyceps_query_search_products($search_term);
		$product_ids = cordyceps_get_query_post_ids($products_query);
	}

	$posts_query = null;
	$post_ids = [];

	if ('' !== $search_term) {
		$posts_query = cordyceps_query_search_posts($search_term, $paged);
		$post_ids = cordyceps_get_query_post_ids($posts_query);
	}

	$product_count = count($product_ids);
	$post_count = $posts_query instanceof WP_Query ? (int) $posts_query->found_posts : 0;
	$has_results = $product_count > 0 || $post_count > 0;

	return [
		'search_term' => $search_term,
		'paged' => $paged,
		'show_products' => $show_products,
		'products_query' => $products_query,
		'product_ids' => $product_ids,
		'product_count' => $product_count,
		'posts_query' => $posts_query,
		'post_ids' => $post_ids,
		'post_count' => $post_count,
		'has_results' => $has_results,
		'has_products' => $product_count > 0,
		'has_posts' => $post_count > 0,
	];
}

/**
 * Render pagination for search post results.
 *
 * @param WP_Query|null $query Post query.
 * @return void
 */
function cordyceps_render_search_pagination($query)
{
	if (!$query instanceof WP_Query || $query->max_num_pages < 2) {
		return;
	}

	$links = paginate_links([
		'total' => (int) $query->max_num_pages,
		'current' => max(1, (int) $query->get('paged')),
		'type' => 'array',
		'prev_next' => true,
		'prev_text' => '&lsaquo;',
		'next_text' => '&rsaquo;',
		'end_size' => 1,
		'mid_size' => 1,
	]);

	if (empty($links) || !is_array($links)) {
		return;
	}

	get_template_part('templates/core-blocks/pagination', null, [
		'class' => 'search-results-pagination',
		'links' => $links,
	]);
}

/**
 * Limit main search query to theme post types (template uses custom queries).
 *
 * @param WP_Query $query Main query.
 * @return void
 */
function cordyceps_search_pre_get_posts($query)
{
	if (is_admin() || !$query->is_main_query() || !$query->is_search()) {
		return;
	}

	$query->set('post_type', ['product', 'post']);
	$query->set('post_status', 'publish');
	$query->set('orderby', 'relevance');
	$query->set('posts_per_page', 1);
	$query->set('no_found_rows', true);
}

add_action('pre_get_posts', 'cordyceps_search_pre_get_posts');
