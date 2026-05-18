<?php

/**
 * Queries Helper Functions
 *
 * @package cordyceps
 * @author biogreen
 * @since 0.0.1
 */

if (!defined('ABSPATH')) {
	exit;
}

/**
 * @return int
 */
function cordyceps_news_section_max_posts_per_page()
{
	$max = (int) apply_filters('cordyceps_news_section_max_posts_per_page', 12);

	return max(1, $max);
}

/**
 * @return int
 */
function cordyceps_news_section_posts_per_page()
{
	$default = 6;
	$limit = (int) apply_filters('cordyceps_news_section_posts_per_page', $default);

	return cordyceps_clamp_posts_per_page($limit, $default, cordyceps_news_section_max_posts_per_page());
}

/**
 * Safe query args for news section cards.
 *
 * @param array $overrides Optional WP_Query overrides.
 * @return array
 */
function cordyceps_prepare_news_query_args(array $overrides = [])
{
	return cordyceps_prepare_list_query_args(
		wp_parse_args($overrides, [
			'post_type' => 'post',
			'orderby' => 'date',
			'order' => 'DESC',
			'ignore_sticky_posts' => true,
		]),
		cordyceps_news_section_posts_per_page(),
		cordyceps_news_section_max_posts_per_page()
	);
}

/**
 * Query latest published posts for the news section.
 *
 * @param array $args Optional WP_Query overrides (posts_per_page -1 is rejected).
 * @return WP_Query
 */
function cordyceps_query_latest_posts($args = [])
{
	return new WP_Query(cordyceps_prepare_news_query_args($args));
}

/**
 * Output news post cards for a query (no output buffering).
 *
 * @param WP_Query|null $query Post query.
 * @return void
 */
function cordyceps_loop_news_section_cards($query)
{
	$post_ids = cordyceps_get_query_post_ids($query);

	if (empty($post_ids)) {
		return;
	}

	foreach ($post_ids as $post_id) {
		get_template_part('templates/core-blocks/post-card', null, [
			'post_id' => $post_id,
		]);
	}
}

/**
 * @deprecated 0.0.2 Use cordyceps_loop_news_section_cards().
 *
 * @param WP_Query|null $query Post query.
 * @return string
 */
function cordyceps_render_news_section_cards($query)
{
	if (empty(cordyceps_get_query_post_ids($query))) {
		return '';
	}

	ob_start();
	cordyceps_loop_news_section_cards($query);

	return (string) ob_get_clean();
}
