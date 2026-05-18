<?php

/**
 * Flexible Content
 *
 * @package cordyceps
 * @author biogreen
 * @since 0.0.1
 */
if (have_rows('sections')) {
	while (have_rows('sections')):
		the_row();
		$layout = get_row_layout();

		switch ($layout):
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
		endswitch;
	endwhile;
}
