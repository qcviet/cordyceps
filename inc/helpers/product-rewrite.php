<?php

/**
 * Product CPT rewrite: single at root (/ten-san-pham/). Listing uses WP page (Product Page template).
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
	return '9';
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
/**
 * Product category taxonomy slug.
 */
function cordyceps_product_category_taxonomy()
{
	return function_exists('cordyceps_featured_product_taxonomy')
		? cordyceps_featured_product_taxonomy()
		: 'product-category';
}

/**
 * Get a product-category term by URL slug.
 *
 * @param string $slug Term slug.
 * @return WP_Term|null
 */
function cordyceps_get_product_category_by_slug($slug)
{
	$slug = sanitize_title($slug);

	if ('' === $slug) {
		return null;
	}

	$term = get_term_by('slug', $slug, cordyceps_product_category_taxonomy());

	if (!$term instanceof WP_Term || is_wp_error($term)) {
		return null;
	}

	return $term;
}

/**
 * Permalink for a product category: domain/{term-slug}/.
 *
 * @param int|WP_Term $term Term ID or object.
 * @return string
 */
function cordyceps_get_product_category_link($term)
{
	if (is_numeric($term)) {
		$term = get_term((int) $term, cordyceps_product_category_taxonomy());
	}

	if (!$term instanceof WP_Term || is_wp_error($term)) {
		return '';
	}

	return home_url(user_trailingslashit($term->slug));
}

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

	// Listing lives on a Page (templates/product-page.php), not a CPT archive.
	$args['has_archive'] = false;
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
 * Root URLs for product categories; resolution handled in request filters.
 *
 * @param array<string, mixed> $args     Taxonomy registration args.
 * @param string               $taxonomy Taxonomy name.
 * @return array<string, mixed>
 */
function cordyceps_filter_product_category_taxonomy_args($args, $taxonomy)
{
	if (cordyceps_product_category_taxonomy() !== $taxonomy) {
		return $args;
	}

	$args['publicly_queryable'] = true;
	$args['rewrite'] = false;
	$args['query_var'] = cordyceps_product_category_taxonomy();

	return $args;
}

add_filter('register_taxonomy_args', 'cordyceps_filter_product_category_taxonomy_args', 20, 2);

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
 * Root slug from rewrite (cordyceps_product_slug or %postname% "name" var).
 *
 * @param array<string, mixed> $query_vars Query vars.
 * @return string
 */
function cordyceps_get_product_root_request_slug(array $query_vars)
{
	if (!empty($query_vars['cordyceps_product_slug'])) {
		return sanitize_title((string) $query_vars['cordyceps_product_slug']);
	}

	if (
		!empty($query_vars['name'])
		&& empty($query_vars['post_type'])
		&& empty($query_vars['pagename'])
		&& empty($query_vars['attachment'])
	) {
		return sanitize_title((string) $query_vars['name']);
	}

	return '';
}

/**
 * Resolve root URLs before main query (fixes %postname% vs product-category conflict).
 *
 * @param array<string, mixed> $query_vars Query vars.
 * @return array<string, mixed>
 */
function cordyceps_filter_product_root_request($query_vars)
{
	$taxonomy = cordyceps_product_category_taxonomy();

	if (!empty($query_vars[$taxonomy])) {
		return $query_vars;
	}

	if (!empty($query_vars['post_type']) && 'product' === $query_vars['post_type']) {
		return $query_vars;
	}

	$slug = cordyceps_get_product_root_request_slug($query_vars);

	if ('' === $slug || cordyceps_is_reserved_product_path($slug)) {
		return $query_vars;
	}

	unset($query_vars['cordyceps_product_slug']);

	$page = get_page_by_path($slug, OBJECT, 'page');

	if ($page instanceof WP_Post && 'publish' === $page->post_status) {
		return $query_vars;
	}

	$post = get_page_by_path($slug, OBJECT, 'post');

	if ($post instanceof WP_Post && 'publish' === $post->post_status) {
		return $query_vars;
	}

	$term = cordyceps_get_product_category_by_slug($slug);

	if ($term) {
		$query_vars[$taxonomy] = $term->slug;

		unset(
			$query_vars['name'],
			$query_vars['pagename'],
			$query_vars['page'],
			$query_vars['attachment'],
			$query_vars['error']
		);

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

add_filter('request', 'cordyceps_filter_product_root_request', 1);

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

	$taxonomy = cordyceps_product_category_taxonomy();

	if (!empty($wp->query_vars[$taxonomy])) {
		return;
	}

	$page = get_page_by_path($path, OBJECT, 'page');

	if ($page instanceof WP_Post && 'publish' === $page->post_status) {
		return;
	}

	$term = cordyceps_get_product_category_by_slug($path);

	if ($term) {
		$wp->query_vars[$taxonomy] = $term->slug;

		unset(
			$wp->query_vars['name'],
			$wp->query_vars['cordyceps_product_slug'],
			$wp->query_vars['pagename'],
			$wp->query_vars['page'],
			$wp->query_vars['error']
		);

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
 * Correct main query flags when a product-category archive was misread as singular.
 *
 * @param WP_Query $query The WP_Query instance.
 */
function cordyceps_parse_query_product_category_archive($query)
{
	if (!$query->is_main_query() || is_admin()) {
		return;
	}

	$taxonomy = cordyceps_product_category_taxonomy();

	if (empty($query->query_vars[$taxonomy])) {
		return;
	}

	if ($query->is_tax($taxonomy)) {
		return;
	}

	$query->is_tax = true;
	$query->is_archive = true;
	$query->is_single = false;
	$query->is_singular = false;
	$query->is_page = false;
	$query->is_home = false;
}

add_action('parse_query', 'cordyceps_parse_query_product_category_archive', 99);

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
 * Product category permalinks: domain/{term-slug}/.
 *
 * @param string  $link     Term link.
 * @param WP_Term $term     Term object.
 * @param string  $taxonomy Taxonomy slug.
 * @return string
 */
function cordyceps_product_category_term_link($link, $term, $taxonomy)
{
	if (cordyceps_product_category_taxonomy() !== $taxonomy || !$term instanceof WP_Term) {
		return $link;
	}

	$url = cordyceps_get_product_category_link($term);

	return '' !== $url ? $url : $link;
}

add_filter('term_link', 'cordyceps_product_category_term_link', 10, 3);

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
 * Flush when an ACF taxonomy is saved in admin.
 *
 * @param int|string $post_id Post ID.
 */
function cordyceps_flush_product_rewrites_on_acf_taxonomy_save($post_id)
{
	if (!function_exists('acf_get_internal_post_type')) {
		return;
	}

	$post = get_post($post_id);

	if (!$post || 'acf-taxonomy' !== $post->post_type) {
		return;
	}

	$acf_taxonomy = acf_get_internal_post_type($post_id, 'acf-taxonomy');

	if (empty($acf_taxonomy['taxonomy']) || cordyceps_product_category_taxonomy() !== $acf_taxonomy['taxonomy']) {
		return;
	}

	delete_option('cordyceps_product_rewrite_version');
	cordyceps_maybe_flush_product_rewrites();
}

add_action('acf/save_post', 'cordyceps_flush_product_rewrites_on_acf_taxonomy_save', 20);

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

/**
 * Redirect legacy /product-category/{slug}/ to domain/{slug}/.
 */
function cordyceps_redirect_legacy_product_category_urls()
{
	if (is_admin()) {
		return;
	}

	global $wp;

	$path = isset($wp->request) ? trim((string) $wp->request, '/') : '';

	if ('' === $path) {
		return;
	}

	$taxonomy = cordyceps_product_category_taxonomy();

	if (!preg_match('#^' . preg_quote($taxonomy, '#') . '/([^/]+)/?$#', $path, $matches)) {
		return;
	}

	$term = cordyceps_get_product_category_by_slug($matches[1]);

	if (!$term) {
		return;
	}

	$target = cordyceps_get_product_category_link($term);

	if ('' === $target) {
		return;
	}

	wp_safe_redirect($target, 301);
	exit;
}

add_action('template_redirect', 'cordyceps_redirect_legacy_product_category_urls', 1);

/**
 * Redirect legacy CPT archive /san-pham/ to the Product Page (only when not already there).
 */
function cordyceps_redirect_product_archive_to_landing_page()
{
	if (is_admin()) {
		return;
	}

	if (is_page_template('templates/product-page.php')) {
		return;
	}

	global $wp;

	$path = isset($wp->request) ? trim((string) $wp->request, '/') : '';
	$archive_slug = cordyceps_product_archive_slug();

	if ($archive_slug !== $path) {
		return;
	}

	// A published page already uses this slug (e.g. landing at /san-pham/) — do not redirect.
	$existing_page = get_page_by_path($path, OBJECT, 'page');

	if ($existing_page instanceof WP_Post && 'publish' === $existing_page->post_status) {
		return;
	}

	if (!function_exists('cordyceps_get_product_page_url')) {
		return;
	}

	$target = cordyceps_get_product_page_url();

	if ('' === $target) {
		return;
	}

	$target_path = wp_parse_url($target, PHP_URL_PATH);

	if (is_string($target_path)) {
		$target_path = trim($target_path, '/');

		if ($target_path === $path) {
			return;
		}
	}

	wp_safe_redirect($target, 301);
	exit;
}

add_action('template_redirect', 'cordyceps_redirect_product_archive_to_landing_page', 1);
