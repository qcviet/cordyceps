<?php

/**
 * Search results layout (full width, hide GP archive chrome).
 *
 * @package cordyceps
 * @author biogreen
 * @since 0.0.1
 */

namespace Cordyceps;

if (!defined('ABSPATH')) {
	exit;
}

class Search_Layout
{
	public function __construct()
	{
		add_action('wp', [$this, 'register_hooks']);
		add_filter('generate_sidebar_layout', [$this, 'sidebar_layout']);
	}

	public function register_hooks()
	{
		if (!is_search()) {
			return;
		}

		add_filter('generate_show_title', '__return_false');
		add_filter('generate_show_entry_header', '__return_false');
		add_filter('generate_show_archive_description', '__return_false');
	}

	/**
	 * @param string $layout Current GP sidebar layout.
	 * @return string
	 */
	public function sidebar_layout($layout)
	{
		if (is_search()) {
			return 'no-sidebar';
		}

		return $layout;
	}
}

new Search_Layout();
