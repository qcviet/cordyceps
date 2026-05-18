<?php

/**
 * News page helpers (archive query + pagination).
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
function cordyceps_news_page_posts_per_page()
{
	$default = 9;
	$limit = (int) apply_filters('cordyceps_news_page_posts_per_page', $default);

	return max(1, $limit);
}

/**
 * @return int
 */
function cordyceps_news_page_max_posts_per_page()
{
	$max = (int) apply_filters('cordyceps_news_page_max_posts_per_page', 24);

	return max(cordyceps_news_page_posts_per_page(), $max);
}

/**
 * Current paged value for the news page archive.
 *
 * @return int
 */
function cordyceps_news_page_get_paged()
{
	$paged = get_query_var('paged');

	if (!$paged) {
		$paged = get_query_var('page');
	}

	return max(1, (int) $paged);
}

/**
 * @param array $overrides Optional WP_Query overrides.
 * @return array
 */
function cordyceps_prepare_news_page_query_args(array $overrides = [])
{
	$per_page = cordyceps_news_page_posts_per_page();
	$paged = cordyceps_news_page_get_paged();

	$args = wp_parse_args($overrides, [
		'post_type' => 'post',
		'post_status' => 'publish',
		'posts_per_page' => $per_page,
		'paged' => $paged,
		'orderby' => 'date',
		'order' => 'DESC',
		'ignore_sticky_posts' => true,
		'no_found_rows' => false,
		'fields' => 'ids',
		'update_post_meta_cache' => true,
		'update_post_term_cache' => false,
	]);

	$args['posts_per_page'] = cordyceps_clamp_posts_per_page(
		isset($args['posts_per_page']) ? $args['posts_per_page'] : $per_page,
		$per_page,
		cordyceps_news_page_max_posts_per_page()
	);
	$args['paged'] = max(1, (int) $args['paged']);
	$args['fields'] = 'ids';

	return $args;
}

/**
 * @param array $args Optional WP_Query overrides.
 * @return WP_Query
 */
function cordyceps_query_news_page_posts($args = [])
{
	return new WP_Query(cordyceps_prepare_news_page_query_args($args));
}

/**
 * Hero field data for the news page template.
 *
 * @param int $post_id Page ID.
 * @return array<string, mixed>
 */
function cordyceps_get_news_page_hero_data($post_id = 0)
{
	$post_id = absint($post_id);

	if ($post_id < 1) {
		$post_id = (int) get_queried_object_id();
	}

	if ($post_id < 1 || !function_exists('get_field')) {
		return [
			'class' => '',
			'hero_title' => '',
			'hero_description' => '',
			'hero_background_image' => '',
		];
	}

	return [
		'class' => '',
		'hero_title' => (string) get_field('hero_title', $post_id),
		'hero_description' => (string) get_field('hero_description', $post_id),
		'hero_background_image' => (int) get_field('hero_background_image', $post_id),
	];
}

/**
 * Render circular pagination for a paginated WP_Query.
 *
 * @param WP_Query|null $query Query instance.
 * @return void
 */
function cordyceps_render_news_page_pagination($query)
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
		'class' => 'news-page-pagination',
		'links' => $links,
	]);
}
