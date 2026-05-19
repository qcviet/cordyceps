<?php

/**
 * Blog post single layout hooks (GeneratePress).
 *
 * @package cordyceps
 * @author biogreen
 * @since 0.0.1
 */

namespace Cordyceps;

class Post_Single_Layout
{
	public function __construct()
	{
		add_action('wp', [$this, 'register_hooks']);
	}

	public function register_hooks()
	{
		if (!is_singular('post')) {
			return;
		}

		add_filter('generate_show_title', '__return_false');
		add_filter('generate_show_post_thumbnail', '__return_false');
		add_filter('generate_show_entry_header', '__return_false');
		add_filter('generate_show_right_sidebar', '__return_false');
		add_filter('generate_show_left_sidebar', '__return_false');
	}
}

new Post_Single_Layout();
