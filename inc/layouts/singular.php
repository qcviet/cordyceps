<?php
/**
 * GeneratePres Singular Layout Hooks
 *
 * @package cordyceps
 * @author biogreen
 * @since 0.0.1
 */

namespace Cordyceps;

class Singular_Layout
{
	public function __construct()
	{
		add_filter('the_content', [$this, 'render_flexible_content'], 20);
	}

	public function render_flexible_content($content)
	{
		if (!is_singular() || !in_the_loop() || !is_main_query()) {
			return $content;
		}

		$post_id = get_queried_object_id();
		if (empty($post_id)) {
			$post_id = get_the_ID();
		}

		if (!function_exists('have_rows') || empty($post_id) || !have_rows('sections', $post_id)) {
			return $content;
		}

		ob_start();
		get_template_part('templates/content/flexible', null, [
			'post_id' => $post_id,
		]);
		$flexible_content = trim((string) ob_get_clean());

		if (empty($flexible_content)) {
			return $content;
		}

		return $flexible_content;
	}
}

new Singular_Layout();
