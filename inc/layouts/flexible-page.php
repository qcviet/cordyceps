<?php

/**
 * Default pages with ACF flexible hero slider — hide GP page title chrome.
 *
 * @package cordyceps
 * @author biogreen
 * @since 0.0.1
 */

namespace Cordyceps;

if (!defined('ABSPATH')) {
	exit;
}

class Flexible_Page_Layout
{
	public function __construct()
	{
		add_action('wp', [$this, 'register_hooks']);
	}

	public function register_hooks()
	{
		if (!$this->should_hide_page_title()) {
			return;
		}

		add_filter('generate_show_title', '__return_false');
		add_filter('generate_show_entry_header', '__return_false');
	}

	private function should_hide_page_title()
	{
		if (!is_singular('page')) {
			return false;
		}

		$post_id = (int) get_queried_object_id();

		return function_exists('cordyceps_page_has_hero_slider_section')
			&& cordyceps_page_has_hero_slider_section($post_id);
	}
}

new Flexible_Page_Layout();
