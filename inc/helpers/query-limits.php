<?php

/**
 * Shared query limits and safe helpers for block sections.
 *
 * @package cordyceps
 * @author biogreen
 * @since 0.0.1
 */

if (!defined('ABSPATH')) {
	exit;
}

/**
 * Clamp posts_per_page to a safe range.
 *
 * @param int $requested Requested count (invalid values fall back to default).
 * @param int $default   Default when requested is invalid.
 * @param int $max       Hard maximum.
 * @return int
 */
function cordyceps_clamp_posts_per_page($requested, $default, $max)
{
	$requested = (int) $requested;
	$default = max(1, (int) $default);
	$max = max($default, (int) $max);

	if ($requested < 1 || -1 === $requested) {
		$requested = $default;
	}

	return min($requested, $max);
}

/**
 * Normalize list query args: capped page size, IDs only, lean caches.
 *
 * @param array $overrides Query overrides.
 * @param int   $default   Default posts_per_page.
 * @param int   $max       Maximum posts_per_page.
 * @return array
 */
function cordyceps_prepare_list_query_args(array $overrides, $default, $max)
{
	$defaults = [
		'post_status' => 'publish',
		'posts_per_page' => $default,
		'no_found_rows' => true,
		'fields' => 'ids',
		'update_post_meta_cache' => true,
		'update_post_term_cache' => false,
	];

	$args = wp_parse_args($overrides, $defaults);
	$args['posts_per_page'] = cordyceps_clamp_posts_per_page(
		isset($args['posts_per_page']) ? $args['posts_per_page'] : $default,
		$default,
		$max
	);
	$args['fields'] = 'ids';
	$args['no_found_rows'] = true;

	return $args;
}

/**
 * Extract post IDs from a WP_Query result.
 *
 * @param WP_Query|null $query Query instance.
 * @return int[]
 */
function cordyceps_get_query_post_ids($query)
{
	if (!$query instanceof WP_Query || empty($query->posts) || !is_array($query->posts)) {
		return [];
	}

	$ids = [];

	foreach ($query->posts as $post) {
		if ($post instanceof WP_Post) {
			$ids[] = (int) $post->ID;
			continue;
		}

		$ids[] = (int) $post;
	}

	$ids = array_filter($ids);

	return array_values(array_unique($ids));
}

/**
 * Plain-text excerpt without running the_content (prevents flexible re-entry loops).
 *
 * @param int $post_id    Post ID.
 * @param int $word_count Max words.
 * @return string
 */
function cordyceps_get_post_excerpt_plain($post_id, $word_count = 22)
{
	$post_id = absint($post_id);

	if ($post_id < 1) {
		return '';
	}

	$excerpt = get_post_field('post_excerpt', $post_id, 'raw');

	if (is_string($excerpt) && '' !== trim($excerpt)) {
		return wp_trim_words(wp_strip_all_tags($excerpt), $word_count, '…');
	}

	$content = get_post_field('post_content', $post_id, 'raw');

	return wp_trim_words(wp_strip_all_tags((string) $content), $word_count, '…');
}
