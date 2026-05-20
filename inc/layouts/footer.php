<?php

/**
 * Footer layout: ACF Options, menus, nav classes.
 *
 * @package cordyceps
 * @author biogreen
 * @since 0.0.1
 */

namespace Cordyceps;

if (!defined('ABSPATH')) {
	exit;
}

class Footer_Layout
{
	public function __construct()
	{
		add_action('after_setup_theme', [$this, 'register_menus'], 20);
		add_filter('nav_menu_link_attributes', [$this, 'footer_nav_link_attributes'], 10, 4);
		add_filter('nav_menu_css_class', [$this, 'footer_nav_item_classes'], 10, 4);
	}

	/**
	 * @param string[]  $classes CSS classes.
	 * @param \WP_Post  $item    Menu item.
	 * @param \stdClass $args    Menu args.
	 * @param int       $depth   Depth.
	 * @return string[]
	 */
	public function footer_nav_item_classes($classes, $item, $args, $depth)
	{
		if (empty($args->theme_location) || !in_array($args->theme_location, ['footer', 'footer-products'], true)) {
			return $classes;
		}

		$classes[] = 'site-footer__menu-item';

		if ($depth > 0) {
			$classes[] = 'site-footer__menu-item--child';
		}

		if (in_array('menu-item-has-children', $classes, true)) {
			$classes[] = 'site-footer__menu-item--parent';
		}

		return $classes;
	}

	public function register_menus()
	{
		register_nav_menus(
			[
				'footer' => __('Footer — Trang', 'cordyceps'),
				'footer-products' => __('Footer — Sản phẩm', 'cordyceps'),
			]
		);
	}

	/**
	 * Add BEM classes to footer menu links.
	 *
	 * @param array<string, string> $atts Link attributes.
	 * @param \WP_Post              $item Menu item.
	 * @param \stdClass             $args Menu args.
	 * @param int                   $depth Depth.
	 * @return array<string, string>
	 */
	public function footer_nav_link_attributes($atts, $item, $args, $depth)
	{
		if (empty($args->theme_location) || !in_array($args->theme_location, ['footer', 'footer-products'], true)) {
			return $atts;
		}

		$existing = isset($atts['class']) ? $atts['class'] . ' ' : '';
		$atts['class'] = trim($existing . 'site-footer__menu-link');

		return $atts;
	}
}

new Footer_Layout();
