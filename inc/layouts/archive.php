<?php

/**
 * GeneratePres Archive Layout Hooks
 *
 * @package cordyceps
 * @author biogreen
 * @since 0.0.1
 */

namespace Cordyceps;

class Archive_Layout
{
	public function __construct()
	{
		add_action('wp', [$this, 'remove_default_hooks'], 5);
		add_action('init', [$this, 'remove_sidebar_widgets'], 5);
	}

	public function remove_default_hooks()
	{
		remove_action('generate_sidebars', 'generate_construct_sidebars');
		remove_action('generate_after_primary_content_area', 'generate_construct_sidebars');
		add_filter('generate_show_right_sidebar', '__return_false');
		add_filter('generate_show_left_sidebar', '__return_false');
		add_filter('generate_sidebar_widgets', '__return_false');
	}

	public function remove_sidebar_widgets()
	{
		unregister_sidebar('sidebar-1');
		unregister_sidebar('sidebar-2');
	}
}

new Archive_Layout();
