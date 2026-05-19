<?php

/**
 * Blog post permalinks: domain/ten-bai-viet/ (native Post name /%postname%/).
 *
 * @package cordyceps
 * @author biogreen
 * @since 0.0.1
 */

if (!defined('ABSPATH')) {
	exit;
}

/**
 * Option key for one-time permalink migration.
 */
function cordyceps_post_permalink_migration_version()
{
	return '4';
}

/**
 * Restore Post name structure if theme previously forced /tin-tuc/%postname%/.
 */
function cordyceps_maybe_reset_post_permalink_structure()
{
	$done = (string) get_option('cordyceps_post_permalink_migration_version', '');

	if ($done === cordyceps_post_permalink_migration_version()) {
		return;
	}

	$structure = (string) get_option('permalink_structure', '');
	$plain = '/%postname%/';

	if ('' === $structure || false !== strpos($structure, '/tin-tuc/')) {
		update_option('permalink_structure', $plain);
		flush_rewrite_rules(false);
	}

	update_option('cordyceps_post_permalink_migration_version', cordyceps_post_permalink_migration_version(), false);
}

add_action('init', 'cordyceps_maybe_reset_post_permalink_structure', 5);

/**
 * 301 redirect legacy /tin-tuc/post-slug/ → /post-slug/
 */
function cordyceps_redirect_legacy_prefixed_post_urls()
{
	if (is_admin()) {
		return;
	}

	global $wp;

	$path = isset($wp->request) ? trim((string) $wp->request, '/') : '';

	if ('' === $path || 0 !== strpos($path, 'tin-tuc/')) {
		return;
	}

	$slug = sanitize_title(substr($path, strlen('tin-tuc/')));

	if ('' === $slug || false !== strpos($slug, '/')) {
		return;
	}

	$post = get_page_by_path($slug, OBJECT, 'post');

	if (!$post instanceof WP_Post || 'publish' !== $post->post_status) {
		return;
	}

	$target = get_permalink($post);

	if (!$target || is_wp_error($target)) {
		return;
	}

	wp_safe_redirect($target, 301);
	exit;
}

add_action('template_redirect', 'cordyceps_redirect_legacy_prefixed_post_urls', 1);
