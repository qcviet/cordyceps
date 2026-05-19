<?php

/**
 * Product CPT rewrite: single at root (/ten-san-pham/), archive at /san-pham/.
 *
 * @package cordyceps
 * @author biogreen
 * @since 0.0.1
 */

if (!defined('ABSPATH')) {
	exit;
}

/**
 * Option key storing last applied rewrite config version.
 */
function cordyceps_product_rewrite_version()
{
	return '6';
}

/**
 * Archive slug for product post type.
 */
function cordyceps_product_archive_slug()
{
	return 'san-pham';
}

/**
 * Get published product by post_name slug.
 *
 * @param string $slug Post slug.
 * @return WP_Post|null
 */
function cordyceps_get_product_by_slug($slug)
{
	$slug = sanitize_title($slug);

	if ('' === $slug) {
		return null;
	}

	$post = get_page_by_path($slug, OBJECT, 'product');

	if (!$post instanceof WP_Post || 'publish' !== $post->post_status) {
		return null;
	}

	return $post;
}

/**
 * Path segments that must not resolve as product singles.
 *
 * @param string $path Request path (no slashes).
 * @return bool
 */
function cordyceps_is_reserved_product_path($path)
{
	$reserved = [
		'wp-admin',
		'wp-content',
		'wp-includes',
		'wp-json',
		'feed',
		'page',
		'category',
		'tag',
		'author',
		'search',
		'attachment',
		cordyceps_product_archive_slug(),
	];

	$reserved[] = 'tin-tuc';

	return in_array($path, $reserved, true);
}

/**
 * @param string $path Request path.
 * @return bool
 */
function cordyceps_path_is_product_single_candidate($path)
{
	$path = trim($path, '/');

	return '' !== $path && false === strpos($path, '/');
}

/**
 * Ensure product CPT singles use root permalinks: domain/ten-san-pham/
 *
 * @param array<string, mixed> $args       Post type registration args.
 * @param string               $post_type Post type name.
 * @return array<string, mixed>
 */
function cordyceps_filter_product_post_type_args($args, $post_type)
{
	if ('product' !== $post_type) {
		return $args;
	}

	$args['has_archive'] = cordyceps_product_archive_slug();
	$args['publicly_queryable'] = true;
	$args['public'] = true;
	$args['query_var'] = 'product';

	$args['rewrite'] = [
		'slug' => '',
		'with_front' => false,
		'pages' => false,
		'feeds' => false,
	];

	return $args;
}

add_filter('register_post_type_args', 'cordyceps_filter_product_post_type_args', 20, 2);

/**
 * Register fallback rewrite tag for root product URLs.
 */
function cordyceps_register_product_root_rewrite_tag()
{
	add_rewrite_tag('%cordyceps_product_slug%', '([^/]+)');
}

add_action('init', 'cordyceps_register_product_root_rewrite_tag', 5);

/**
 * Fallback rule: single segment → product lookup (low priority, paired with parse_request).
 */
function cordyceps_register_product_root_rewrite_rule()
{
	add_rewrite_rule(
		'^([^/]+)/?$',
		'index.php?cordyceps_product_slug=$matches[1]',
		'bottom'
	);
}

add_action('init', 'cordyceps_register_product_root_rewrite_rule', 20);

/**
 * Map cordyceps_product_slug query var to product query.
 *
 * @param array<string, mixed> $query_vars Query vars.
 * @return array<string, mixed>
 */
function cordyceps_map_product_root_query_var($query_vars)
{
	if (empty($query_vars['cordyceps_product_slug'])) {
		return $query_vars;
	}

	$slug = sanitize_title((string) $query_vars['cordyceps_product_slug']);

	unset($query_vars['cordyceps_product_slug']);

	if (cordyceps_is_reserved_product_path($slug)) {
		return $query_vars;
	}

	$page = get_page_by_path($slug, OBJECT, 'page');

	if ($page instanceof WP_Post && 'publish' === $page->post_status) {
		return $query_vars;
	}

	$product = cordyceps_get_product_by_slug($slug);

	if (!$product) {
		return $query_vars;
	}

	$query_vars['post_type'] = 'product';
	$query_vars['name'] = $product->post_name;
	$query_vars['product'] = $product->post_name;

	unset($query_vars['pagename'], $query_vars['page'], $query_vars['error']);

	return $query_vars;
}

add_filter('request', 'cordyceps_map_product_root_query_var', 5);

/**
 * Early request fix when WordPress matched a page name but slug is a product.
 *
 * @param WP $wp Current WordPress environment instance.
 */
function cordyceps_parse_request_product_root($wp)
{
	if (is_admin() || empty($wp->request)) {
		return;
	}

	$path = trim((string) $wp->request, '/');

	if (!cordyceps_path_is_product_single_candidate($path) || cordyceps_is_reserved_product_path($path)) {
		return;
	}

	if (!empty($wp->query_vars['post_type']) && 'product' === $wp->query_vars['post_type']) {
		return;
	}

	$page = get_page_by_path($path, OBJECT, 'page');

	if ($page instanceof WP_Post && 'publish' === $page->post_status) {
		return;
	}

	$product = cordyceps_get_product_by_slug($path);

	if (!$product) {
		return;
	}

	$wp->query_vars['post_type'] = 'product';
	$wp->query_vars['name'] = $product->post_name;
	$wp->query_vars['product'] = $product->post_name;

	unset($wp->query_vars['pagename'], $wp->query_vars['page'], $wp->query_vars['error']);
}

add_action('parse_request', 'cordyceps_parse_request_product_root', 5);

/**
 * Prevent 404 when URL is a valid root-level product slug.
 *
 * @param bool      $preempt  Whether to short-circuit handling.
 * @param WP_Query  $wp_query Main query.
 * @return bool
 */
function cordyceps_pre_handle_404_for_product_root($preempt, $wp_query)
{
	if ($preempt || is_admin() || !($wp_query instanceof WP_Query)) {
		return $preempt;
	}

	global $wp;

	$path = isset($wp->request) ? trim((string) $wp->request, '/') : '';

	if (!cordyceps_path_is_product_single_candidate($path) || cordyceps_is_reserved_product_path($path)) {
		return $preempt;
	}

	$page = get_page_by_path($path, OBJECT, 'page');

	if ($page instanceof WP_Post && 'publish' === $page->post_status) {
		return $preempt;
	}

	$product = cordyceps_get_product_by_slug($path);

	if (!$product) {
		return $preempt;
	}

	$wp_query->query(
		[
			'post_type' => 'product',
			'name' => $product->post_name,
			'posts_per_page' => 1,
		]
	);

	return !empty($wp_query->posts);
}

add_filter('pre_handle_404', 'cordyceps_pre_handle_404_for_product_root', 10, 2);

/**
 * Force product permalinks to domain/{post-name}/.
 *
 * @param string  $post_link Permalink.
 * @param WP_Post $post      Post object.
 * @return string
 */
function cordyceps_product_post_type_link($post_link, $post)
{
	if (!$post instanceof WP_Post || 'product' !== $post->post_type) {
		return $post_link;
	}

	if (!in_array($post->post_status, ['publish', 'private'], true)) {
		return $post_link;
	}

	return home_url(user_trailingslashit($post->post_name));
}

add_filter('post_type_link', 'cordyceps_product_post_type_link', 10, 2);

/**
 * Flush rewrite rules once after slug/archive changes.
 */
function cordyceps_maybe_flush_product_rewrites()
{
	if (!post_type_exists('product')) {
		return;
	}

	$stored = (string) get_option('cordyceps_product_rewrite_version', '');

	if ($stored === cordyceps_product_rewrite_version()) {
		return;
	}

	flush_rewrite_rules(false);
	update_option('cordyceps_product_rewrite_version', cordyceps_product_rewrite_version(), false);
}

add_action('init', 'cordyceps_maybe_flush_product_rewrites', 99);

/**
 * Flush when an ACF post type is saved in admin.
 *
 * @param int|string $post_id Post ID.
 */
function cordyceps_flush_product_rewrites_on_acf_post_type_save($post_id)
{
	if (!function_exists('acf_get_internal_post_type')) {
		return;
	}

	$post = get_post($post_id);

	if (!$post || 'acf-post-type' !== $post->post_type) {
		return;
	}

	$acf_post_type = acf_get_internal_post_type($post_id, 'acf-post-type');

	if (empty($acf_post_type['post_type']) || 'product' !== $acf_post_type['post_type']) {
		return;
	}

	delete_option('cordyceps_product_rewrite_version');
	cordyceps_maybe_flush_product_rewrites();
}

add_action('acf/save_post', 'cordyceps_flush_product_rewrites_on_acf_post_type_save', 20);

/**
 * Redirect legacy product URLs to root permalink.
 */
function cordyceps_redirect_legacy_product_urls()
{
	if (!is_404()) {
		return;
	}

	global $wp;

	if (empty($wp->request)) {
		return;
	}

	$path = trim((string) $wp->request, '/');
	$product_slug = '';
	$archive_slug = cordyceps_product_archive_slug();

	if (preg_match('#^product/([^/]+)/?$#', $path, $matches)) {
		$product_slug = $matches[1];
	} elseif (preg_match('#^' . preg_quote($archive_slug, '#') . '/([^/]+)/?$#', $path, $matches)) {
		$product_slug = $matches[1];
	} else {
		return;
	}

	$post = cordyceps_get_product_by_slug($product_slug);

	if (!$post) {
		return;
	}

	wp_safe_redirect(get_permalink($post), 301);
	exit;
}

add_action('template_redirect', 'cordyceps_redirect_legacy_product_urls', 1);
