<?php

/**
 * GeneratePress Singular Layout Hooks
 *
 * @package cordyceps
 * @author biogreen
 * @since 0.0.1
 */

namespace Cordyceps;

class Singular_Layout
{
	/**
	 * Prevent recursive the_content → flexible → get_the_excerpt → the_content loops.
	 *
	 * @var bool
	 */
	private static $rendering_flexible = false;

	public function __construct()
	{
		add_filter('the_content', [$this, 'render_flexible_content'], 20);
	}

	public function render_flexible_content($content)
	{
		if (self::$rendering_flexible) {
			return $content;
		}

		if (!is_singular() || !in_the_loop() || !is_main_query()) {
			return $content;
		}

		$post_id = (int) get_queried_object_id();

		if ($post_id < 1) {
			$post_id = (int) get_the_ID();
		}

		if (in_array(get_post_type($post_id), ['product', 'post'], true)) {
			return $content;
		}

		if (!function_exists('have_rows') || $post_id < 1 || !have_rows('sections', $post_id)) {
			return $content;
		}

		self::$rendering_flexible = true;
		remove_filter('the_content', [$this, 'render_flexible_content'], 20);

		ob_start();
		get_template_part('templates/content/flexible', null, [
			'post_id' => $post_id,
		]);
		$flexible_content = trim((string) ob_get_clean());

		add_filter('the_content', [$this, 'render_flexible_content'], 20);
		self::$rendering_flexible = false;

		if ('' === $flexible_content) {
			return $content;
		}

		return $flexible_content;
	}
}

new Singular_Layout();
