<?php

/**
 * Template Name: Product Page
 * Template Post Type: page
 *
 * @package cordyceps
 * @author biogreen
 * @since 0.0.1
 */

get_header();
?>
<div class="product-page product-page--hero-landing">
	<?php
	if (have_rows('product_sections')) {
		while (have_rows('product_sections')):
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
				case 'product_section':
					$data = cordyceps_get_flexible_content_data([
						'class' => '',
						'title' => 'title',
						'description' => 'description',
						'category_items' => 'category_items',
						'category_product' => 'category_product',
					]);
					get_template_part('templates/blocks/featured-product', null, $data);
					break;
			endswitch;
		endwhile;
	}
	?>
</div>
<?php
get_footer();
