<?php

/**
 * Product category archive — match Product Page GP layout (full width, no sidebar).
 *
 * @package cordyceps
 * @author biogreen
 * @since 0.0.1
 */

namespace Cordyceps;

if (!defined('ABSPATH')) {
	exit;
}

class Product_Category_Layout
{
	public function __construct()
	{
		add_filter('generate_sidebar_layout', [$this, 'sidebar_layout']);
		add_filter('body_class', [$this, 'body_class'], 25);
	}

	/**
	 * @param string $layout Current GP sidebar layout.
	 * @return string
	 */
	public function sidebar_layout($layout)
	{
		if (function_exists('cordyceps_is_product_landing_view') && cordyceps_is_product_landing_view()) {
			return 'no-sidebar';
		}

		return $layout;
	}

	/**
	 * @param string[] $classes Body classes.
	 * @return string[]
	 */
	public function body_class($classes)
	{
		if (!function_exists('cordyceps_is_product_landing_view') || !cordyceps_is_product_landing_view()) {
			return $classes;
		}

		$remove = [
			'right-sidebar',
			'left-sidebar',
			'both-sidebars',
			'both-left',
			'both-right',
		];

		return array_values(array_diff($classes, $remove));
	}
}

new Product_Category_Layout();
