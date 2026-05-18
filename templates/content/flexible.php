<?php

/**
 * Flexible Content
 *
 * @package cordyceps
 * @author biogreen
 * @since 0.0.1
 */

if (!isset($args) || !is_array($args)) {
	$args = [];
}

$post_id = isset($args['post_id']) ? absint($args['post_id']) : (int) get_the_ID();

if ($post_id < 1 || !function_exists('have_rows') || !have_rows('sections', $post_id)) {
	return;
}

while (have_rows('sections', $post_id)) :
	the_row();
	$layout = get_row_layout();

	switch ($layout) :
		case 'hero_slider':
			$data = cordyceps_get_flexible_content_data([
				'class' => '',
				'slider_items' => 'slider_items',
			]);
			get_template_part('templates/blocks/hero-slider', null, $data);
			break;

		case 'home_about':
			$data = cordyceps_get_flexible_content_data([
				'class' => '',
				'title' => 'title',
				'subtitle' => 'subtitle',
				'description' => 'description',
				'background_image' => 'background_image',
				'button_url' => 'button_url',
			]);
			get_template_part('templates/blocks/home-about', null, $data);
			break;

		case 'trust_badges':
			$data = cordyceps_get_flexible_content_data([
				'class' => '',
				'three_items' => 'three_items',
				'four_items' => 'four_items',
			]);
			get_template_part('templates/blocks/trust-badges', null, $data);
			break;

		case 'featured_product':
			$data = cordyceps_get_flexible_content_data([
				'class' => '',
				'title' => 'title',
				'description' => 'description',
				'category_items' => 'category_items',
				'category_product' => 'category_product',
			]);
			get_template_part('templates/blocks/featured-product', null, $data);
			break;

		case 'news_section':
			$data = cordyceps_get_flexible_content_data([
				'class' => '',
				'title' => 'title',
			]);
			get_template_part('templates/blocks/news-section', null, $data);
			break;
	endswitch;
endwhile;
