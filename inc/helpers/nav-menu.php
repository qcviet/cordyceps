<?php

/**
 * Nav menu helpers (product-category URLs, admin panel).
 *
 * @package cordyceps
 * @author biogreen
 * @since 0.0.1
 */

if (!defined('ABSPATH')) {
	exit;
}

/**
 * Max depth for a theme menu location.
 *
 * @param string $theme_location Registered location slug.
 * @return int 0 = all levels.
 */
function cordyceps_get_nav_menu_depth($theme_location)
{
	switch ($theme_location) {
		case 'footer-products':
			return 0;
		case 'primary':
			return 3;
		default:
			return 1;
	}
}

/**
 * Fix product-category menu item URLs (root slug permalinks).
 *
 * @param object $menu_item Nav menu item.
 * @return object
 */
function cordyceps_setup_nav_menu_item_product_category($menu_item)
{
	if (
		!is_object($menu_item)
		|| empty($menu_item->type)
		|| 'taxonomy' !== $menu_item->type
		|| !function_exists('cordyceps_product_category_taxonomy')
		|| cordyceps_product_category_taxonomy() !== $menu_item->object
	) {
		return $menu_item;
	}

	$term = get_term((int) $menu_item->object_id, $menu_item->object);

	if (!$term instanceof WP_Term || is_wp_error($term)) {
		return $menu_item;
	}

	$url = function_exists('cordyceps_get_product_category_link')
		? cordyceps_get_product_category_link($term)
		: get_term_link($term);

	if (!is_wp_error($url) && is_string($url) && '' !== $url) {
		$menu_item->url = $url;
	}

	return $menu_item;
}

add_filter('wp_setup_nav_menu_item', 'cordyceps_setup_nav_menu_item_product_category', 20);

/**
 * Product archive menu items → Product Page permalink (not /san-pham/).
 *
 * @param object $menu_item Nav menu item.
 * @return object
 */
function cordyceps_setup_nav_menu_item_product_archive($menu_item)
{
	if (
		!is_object($menu_item)
		|| empty($menu_item->type)
		|| 'post_type_archive' !== $menu_item->type
		|| empty($menu_item->object)
		|| 'product' !== $menu_item->object
		|| !function_exists('cordyceps_get_product_page_url')
	) {
		return $menu_item;
	}

	$url = cordyceps_get_product_page_url();

	if ('' !== $url) {
		$menu_item->url = $url;
	}

	return $menu_item;
}

add_filter('wp_setup_nav_menu_item', 'cordyceps_setup_nav_menu_item_product_archive', 20);

/**
 * Custom menu links still pointing at legacy /san-pham/ archive → Product Page.
 *
 * @param object $menu_item Nav menu item.
 * @return object
 */
function cordyceps_setup_nav_menu_item_legacy_product_archive_url($menu_item)
{
	if (
		!is_object($menu_item)
		|| empty($menu_item->type)
		|| 'custom' !== $menu_item->type
		|| empty($menu_item->url)
		|| !function_exists('cordyceps_product_archive_slug')
		|| !function_exists('cordyceps_get_product_page_url')
	) {
		return $menu_item;
	}

	$path = wp_parse_url($menu_item->url, PHP_URL_PATH);

	if (!is_string($path)) {
		return $menu_item;
	}

	$path = trim($path, '/');

	if (cordyceps_product_archive_slug() !== $path) {
		return $menu_item;
	}

	$url = cordyceps_get_product_page_url();

	if ('' !== $url) {
		$menu_item->url = $url;
	}

	return $menu_item;
}

add_filter('wp_setup_nav_menu_item', 'cordyceps_setup_nav_menu_item_legacy_product_archive_url', 20);

/**
 * Show "Category" (product-category) panel on Menus screen if user hid it.
 */
function cordyceps_ensure_product_category_menu_panel()
{
	$user_id = get_current_user_id();

	if ($user_id < 1) {
		return;
	}

	$hidden = get_user_meta($user_id, 'metaboxhidden_nav-menus', true);

	if (!is_array($hidden)) {
		return;
	}

	$panel_id = 'add-' . (function_exists('cordyceps_product_category_taxonomy')
		? cordyceps_product_category_taxonomy()
		: 'product-category');

	if (!in_array($panel_id, $hidden, true)) {
		return;
	}

	$hidden = array_values(array_diff($hidden, [$panel_id]));
	update_user_meta($user_id, 'metaboxhidden_nav-menus', $hidden);
}

add_action('admin_head-nav-menus.php', 'cordyceps_ensure_product_category_menu_panel');

/**
 * Append chevron-down to top-level header items with children.
 *
 * @param string   $title Menu item title HTML.
 * @param WP_Post  $item  Menu item.
 * @param stdClass $args  wp_nav_menu() args.
 * @param int      $depth Depth.
 * @return string
 */
function cordyceps_nav_menu_item_title_chevron($title, $item, $args, $depth)
{
	if (0 !== (int) $depth) {
		return $title;
	}

	if (empty($args->theme_location) || 'primary' !== $args->theme_location) {
		return $title;
	}

	$classes = is_array($item->classes) ? $item->classes : [];

	if (!in_array('menu-item-has-children', $classes, true)) {
		return $title;
	}

	if (!function_exists('cordyceps_get_svg_icon')) {
		return $title;
	}

	$chevron = cordyceps_get_svg_icon('chevron-down');

	if ('' === $chevron) {
		return $title;
	}

	return $title . '<span class="header__nav-caret" aria-hidden="true">' . $chevron . '</span>';
}

add_filter('nav_menu_item_title', 'cordyceps_nav_menu_item_title_chevron', 10, 4);
