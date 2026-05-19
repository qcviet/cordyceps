<?php

/**
 * Product single (PDP) helpers.
 *
 * @package cordyceps
 * @author biogreen
 * @since 0.0.1
 */

if (!defined('ABSPATH')) {
	exit;
}

/**
 * Collect PDP data for a product post.
 *
 * @param int $post_id Product post ID.
 * @return array<string, mixed>
 */
function cordyceps_get_product_single_data($post_id = 0)
{
	$post_id = $post_id > 0 ? $post_id : (int) get_queried_object_id();

	if ($post_id < 1) {
		return [];
	}

	$social_raw = get_field('social_links', $post_id);
	$social = [
		'facebook' => '',
		'zalo' => '',
		'messenger' => '',
		'telegram' => '',
	];

	if (is_array($social_raw)) {
		foreach (array_keys($social) as $key) {
			$social[$key] = !empty($social_raw[$key]) ? esc_url_raw((string) $social_raw[$key]) : '';
		}
	}

	$related = get_field('related_products', $post_id);
	$related_ids = cordyceps_normalize_product_ids($related);

	return [
		'post_id' => $post_id,
		'title' => get_the_title($post_id),
		'permalink' => get_permalink($post_id),
		'thumbnail_id' => (int) get_post_thumbnail_id($post_id),
		'short_description' => (string) get_field('short_description', $post_id),
		'social_links' => $social,
		'related_ids' => $related_ids,
		'content' => apply_filters('the_content', get_post_field('post_content', $post_id)),
	];
}

/**
 * Normalize post IDs from ACF relationship or mixed values.
 *
 * @param mixed $value ACF value.
 * @return int[]
 */
function cordyceps_normalize_product_ids($value)
{
	$ids = [];

	if (!is_array($value)) {
		return $ids;
	}

	foreach ($value as $item) {
		if (is_numeric($item)) {
			$ids[] = (int) $item;
		} elseif ($item instanceof WP_Post) {
			$ids[] = (int) $item->ID;
		} elseif (is_array($item) && !empty($item['ID'])) {
			$ids[] = (int) $item['ID'];
		}
	}

	$ids = array_values(array_unique(array_filter(array_map('absint', $ids))));

	return $ids;
}

/**
 * Resolve related product IDs (ACF relationship or same taxonomy fallback).
 *
 * @param array<string, mixed> $data Product single data.
 * @param int                $limit Max products.
 * @return int[]
 */
function cordyceps_get_product_related_ids(array $data, $limit = 4)
{
	$limit = max(1, min(8, (int) $limit));
	$post_id = !empty($data['post_id']) ? (int) $data['post_id'] : 0;
	$ids = !empty($data['related_ids']) ? cordyceps_normalize_product_ids($data['related_ids']) : [];

	if (!empty($ids)) {
		return array_slice($ids, 0, $limit);
	}

	if ($post_id < 1) {
		return [];
	}

	$taxonomy = function_exists('cordyceps_featured_product_taxonomy')
		? cordyceps_featured_product_taxonomy()
		: 'product-category';

	$term_ids = wp_get_post_terms($post_id, $taxonomy, ['fields' => 'ids']);

	if (is_wp_error($term_ids) || empty($term_ids)) {
		return cordyceps_get_latest_product_ids($post_id, $limit);
	}

	$query = new WP_Query([
		'post_type' => 'product',
		'post_status' => 'publish',
		'posts_per_page' => $limit,
		'post__not_in' => [$post_id],
		'orderby' => 'date',
		'order' => 'DESC',
		'ignore_sticky_posts' => true,
		'no_found_rows' => true,
		'tax_query' => [
			[
				'taxonomy' => $taxonomy,
				'field' => 'term_id',
				'terms' => array_map('absint', $term_ids),
			],
		],
	]);

	$ids = [];

	if ($query->have_posts()) {
		foreach ($query->posts as $post) {
			$ids[] = (int) $post->ID;
		}
	}

	wp_reset_postdata();

	if (empty($ids)) {
		return cordyceps_get_latest_product_ids($post_id, $limit);
	}

	return $ids;
}

/**
 * Latest published products excluding current.
 *
 * @param int $exclude_id Post ID to exclude.
 * @param int $limit      Max items.
 * @return int[]
 */
function cordyceps_get_latest_product_ids($exclude_id, $limit = 4)
{
	$query = new WP_Query([
		'post_type' => 'product',
		'post_status' => 'publish',
		'posts_per_page' => $limit,
		'post__not_in' => [$exclude_id],
		'orderby' => 'date',
		'order' => 'DESC',
		'ignore_sticky_posts' => true,
		'no_found_rows' => true,
	]);

	$ids = [];

	if ($query->have_posts()) {
		foreach ($query->posts as $post) {
			$ids[] = (int) $post->ID;
		}
	}

	wp_reset_postdata();

	return $ids;
}

/**
 * Build share URL for a social network.
 *
 * @param string $network   facebook|zalo|messenger|telegram.
 * @param string $page_url  Current product URL.
 * @param string $custom_url Optional override from ACF.
 * @return string
 */
function cordyceps_get_product_share_url($network, $page_url, $custom_url = '')
{
	$custom_url = trim((string) $custom_url);

	if ('' !== $custom_url) {
		return esc_url($custom_url);
	}

	$page_url = esc_url($page_url);
	$encoded = rawurlencode($page_url);
	$title = rawurlencode(wp_strip_all_tags(get_the_title()));

	switch ($network) {
		case 'facebook':
			return 'https://www.facebook.com/sharer/sharer.php?u=' . $encoded;
		case 'messenger':
			return 'https://www.facebook.com/dialog/send?link=' . $encoded . '&app_id=87741124305&redirect_uri=' . $encoded;
		case 'telegram':
			return 'https://t.me/share/url?url=' . $encoded . '&text=' . $title;
		case 'zalo':
			return 'https://zalo.me/share?url=' . $encoded;
		default:
			return $page_url;
	}
}
