<?php

/**
 * Template Name: Landing Introduce
 * Template Post Type: page
 *
 * @package pharma
 * @author biogreen
 * @since 0.0.1
 */

get_header();
?>
<div class="introduce-page introduce-page--hero-landing">
	<?php
	if (have_rows('introduce_sections')) {
		while (have_rows('introduce_sections')):
			the_row();
			$layout = get_row_layout();

			switch ($layout):
				case 'hero_banner':
					$data = cordyceps_get_flexible_content_data([
						'class' => '',
						'subtitle' => 'subtitle',
						'title' => 'title',
						'title_italic' => 'title_italic',
						'description' => 'description',
						'icon_items' => 'icon_items',
						'background_image' => 'background_image',
					]);
					get_template_part('templates/blocks/hero-banner', null, $data);
					break;
				case 'about_section':
					$data = cordyceps_get_flexible_content_data([
						'class' => '',
						'subtitle' => 'subtitle',
						'title' => 'title',
						'description' => 'description',
						'history_items' => 'history_items',
						'image' => 'image',
					]);
					get_template_part('templates/blocks/about-section', null, $data);
					break;

				case 'process_section':
					$data = cordyceps_get_flexible_content_data([
						'class' => '',
						'subtitle' => 'subtitle',
						'title' => 'title',
						'description' => 'description',
						'process_items' => 'process_items',
						'highlighted_content' => 'highlighted_content',
						'background_highlighted' => 'background_highlighted',
					]);
					get_template_part('templates/blocks/process-section', null, $data);
					break;
			endswitch;
		endwhile;
	}
	?>
</div>
<?php
get_footer();
