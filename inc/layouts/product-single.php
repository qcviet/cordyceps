<?php

/**
 * Product single layout hooks (GeneratePress).
 *
 * @package cordyceps
 * @author biogreen
 * @since 0.0.1
 */

namespace Cordyceps;

class Product_Single_Layout
{
	public function __construct()
	{
		add_action('wp', [$this, 'register_hooks']);
	}

	public function register_hooks()
	{
		if (!is_singular('product')) {
			return;
		}

		add_filter('generate_show_title', '__return_false');
		add_filter('generate_show_post_thumbnail', '__return_false');
		add_filter('generate_show_entry_header', '__return_false');
	}

}

new Product_Single_Layout();
