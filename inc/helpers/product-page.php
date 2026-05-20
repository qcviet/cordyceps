<?php

/**
 * Product landing page helpers (ACF flexible sections).
 *
 * @package cordyceps
 * @author biogreen
 * @since 0.0.1
 */

if (!defined('ABSPATH')) {
	exit;
}

/**
 * Product landing UI (page template or product-category archive).
 */
function cordyceps_is_product_landing_view()
{
	return is_page_template('templates/product-page.php') || is_tax('product-category');
}

/**
 * Published page ID using the Product Page template.
 */
function cordyceps_get_product_landing_page_id()
{
	static $page_id = -1;

	if ($page_id >= 0) {
		return $page_id;
	}

	$pages = get_posts(
		[
			'post_type' => 'page',
			'post_status' => 'publish',
			'posts_per_page' => 1,
			'fields' => 'ids',
			'orderby' => 'menu_order',
			'order' => 'ASC',
			'meta_key' => '_wp_page_template',
			'meta_value' => 'templates/product-page.php',
			'suppress_filters' => false,
		]
	);

	$page_id = !empty($pages) ? (int) $pages[0] : 0;

	return $page_id;
}

/**
 * Canonical URL for the product listing page (WP page with Product Page template).
 *
 * @return string Permalink or empty if no landing page is configured.
 */
function cordyceps_get_product_page_url()
{
	$page_id = cordyceps_get_product_landing_page_id();

	if ($page_id <= 0) {
		return '';
	}

	$url = get_permalink($page_id);

	if (!$url || is_wp_error($url)) {
		return '';
	}

	return $url;
}

/**
 * Product archive links should point at the landing page, not CPT /san-pham/.
 *
 * @param string $link     Archive URL.
 * @param string $post_type Post type name.
 * @return string
 */
function cordyceps_filter_product_post_type_archive_link($link, $post_type)
{
	if ('product' !== $post_type) {
		return $link;
	}

	$page_url = cordyceps_get_product_page_url();

	return '' !== $page_url ? $page_url : $link;
}

add_filter('post_type_archive_link', 'cordyceps_filter_product_post_type_archive_link', 10, 2);

/**
 * Render product landing flexible sections (hero + featured products).
 *
 * @param array<string, mixed> $context Optional: active_category_id (int).
 */
function cordyceps_render_product_landing_sections(array $context = [])
{
	$page_id = cordyceps_get_product_landing_page_id();

	if ($page_id <= 0 || !function_exists('have_rows') || !have_rows('product_sections', $page_id)) {
		return;
	}

	$active_category_id = isset($context['active_category_id']) ? absint($context['active_category_id']) : 0;

	while (have_rows('product_sections', $page_id)) {
		the_row();
		$layout = get_row_layout();

		switch ($layout) {
			case 'hero_banner':
				$data = cordyceps_get_flexible_content_data(
					[
						'class' => '',
						'subtitle' => 'subtitle',
						'title' => 'title',
						'title_italic' => 'title_italic',
						'description' => 'description',
						'icon_items' => 'icon_items',
						'background_image' => 'background_image',
					]
				);
				get_template_part('templates/blocks/hero-banner', null, $data);
				break;

			case 'product_section':
				$data = cordyceps_get_flexible_content_data(
					[
						'class' => '',
						'title' => 'title',
						'description' => 'description',
						'category_items' => 'category_items',
						'category_product' => 'category_product',
					]
				);

				if ($active_category_id > 0) {
					$term = get_term($active_category_id, cordyceps_featured_product_taxonomy());

					if ($term instanceof WP_Term && !is_wp_error($term)) {
						$data['category_product'] = [$active_category_id];
						$data['active_category_id'] = $active_category_id;
					}
				}

				get_template_part('templates/blocks/featured-product', null, $data);
				break;
		}
	}
}

/**
 * Markup wrapper shared by product page + product-category archives.
 *
 * @param array<string, mixed> $context Optional: active_category_id (int).
 */
function cordyceps_render_product_landing_page(array $context = [])
{
	echo '<div class="product-page product-page--hero-landing">';
	cordyceps_render_product_landing_sections($context);
	echo '</div>';
}
