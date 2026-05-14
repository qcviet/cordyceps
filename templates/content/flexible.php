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

		endswitch;
	endwhile;
}
