<?php

/**
 * Blog post single helpers.
 *
 * @package cordyceps
 * @author biogreen
 * @since 0.0.1
 */

if (!defined('ABSPATH')) {
	exit;
}

/**
 * Collect single post data (ACF + core fields).
 *
 * @param int $post_id Post ID.
 * @return array<string, mixed>
 */
function cordyceps_get_post_single_data($post_id = 0)
{
	$post_id = $post_id > 0 ? $post_id : (int) get_queried_object_id();

	if ($post_id < 1) {
		return [];
	}

	$featured_image_id = 0;

	if (function_exists('get_field')) {
		$acf_image = get_field('featured_image', $post_id);

		if (is_array($acf_image) && !empty($acf_image['ID'])) {
			$featured_image_id = (int) $acf_image['ID'];
		} elseif (is_numeric($acf_image)) {
			$featured_image_id = (int) $acf_image;
		}
	}

	if ($featured_image_id < 1) {
		$featured_image_id = (int) get_post_thumbnail_id($post_id);
	}

	$share_enable = true;

	if (function_exists('get_field')) {
		$share_field = get_field('share_enable', $post_id);

		if (false === $share_field || 0 === $share_field || '0' === $share_field) {
			$share_enable = false;
		}
	}

	$categories = get_the_category($post_id);
	$category_links = [];

	if (!empty($categories) && !is_wp_error($categories)) {
		foreach ($categories as $category) {
			if (!$category instanceof WP_Term) {
				continue;
			}

			$category_links[] = [
				'name' => $category->name,
				'url' => get_category_link($category->term_id),
			];
		}
	}

	return [
		'post_id' => $post_id,
		'title' => get_the_title($post_id),
		'permalink' => get_permalink($post_id),
		'featured_image_id' => $featured_image_id,
		'short_desc' => function_exists('get_field') ? (string) get_field('short_desc', $post_id) : '',
		'share_enable' => $share_enable,
		'author_name' => function_exists('get_field') ? (string) get_field('author_name', $post_id) : '',
		'reading_time' => function_exists('get_field') ? (string) get_field('reading_time', $post_id) : '',
		'date_iso' => get_the_date('c', $post_id),
		'date_label' => get_the_date('d/m/Y', $post_id),
		'category_links' => $category_links,
	];
}

/**
 * Build social share URL for a blog post.
 *
 * @param string $network  facebook|twitter.
 * @param string $page_url Canonical post URL.
 * @param string $title    Post title.
 * @return string
 */
function cordyceps_get_post_share_url($network, $page_url, $title = '')
{
	$page_url = esc_url($page_url);
	$encoded_url = rawurlencode($page_url);
	$encoded_title = rawurlencode(wp_strip_all_tags($title));

	switch ($network) {
		case 'facebook':
			return 'https://www.facebook.com/sharer/sharer.php?u=' . $encoded_url;
		case 'twitter':
			return 'https://twitter.com/intent/tweet?url=' . $encoded_url . '&text=' . $encoded_title;
		default:
			return $page_url;
	}
}

/**
 * Format reading time label from ACF value.
 *
 * @param string $reading_time Raw field value.
 * @return string
 */
function cordyceps_format_post_reading_time($reading_time)
{
	$reading_time = trim((string) $reading_time);

	if ('' === $reading_time) {
		return '';
	}

	if (false !== stripos($reading_time, 'phút') || false !== stripos($reading_time, 'min')) {
		return $reading_time;
	}

	if (is_numeric($reading_time)) {
		/* translators: %s: number of minutes */
		return sprintf(_n('%s phút đọc', '%s phút đọc', (int) $reading_time, 'cordyceps'), $reading_time);
	}

	return $reading_time;
}

/**
 * Recent posts for the single sidebar.
 *
 * @param int $exclude_id Current post ID.
 * @param int $limit      Max posts.
 * @return WP_Query
 */
function cordyceps_query_post_single_sidebar($exclude_id = 0, $limit = 8)
{
	$exclude_id = absint($exclude_id);
	$limit = max(1, min(8, (int) $limit));

	$not_in = [];

	if ($exclude_id > 0) {
		$not_in[] = $exclude_id;
	}

	return new WP_Query([
		'post_type' => 'post',
		'post_status' => 'publish',
		'posts_per_page' => $limit,
		'post__not_in' => $not_in,
		'orderby' => 'date',
		'order' => 'DESC',
		'ignore_sticky_posts' => true,
		'no_found_rows' => true,
		'update_post_meta_cache' => false,
		'update_post_term_cache' => false,
	]);
}
