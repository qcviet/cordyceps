<?php

/**
 * Template Name: News Page
 * Template Post Type: page
 *
 * @package cordyceps
 * @author biogreen
 * @since 0.0.1
 */

get_header();

$paged_query = cordyceps_query_news_page_posts();
$hero_data = cordyceps_get_news_page_hero_data(get_queried_object_id());
?>
<div class="news-page news-page--hero-landing">
	<?php
	get_template_part('templates/blocks/news-page-hero', null, $hero_data);

	get_template_part('templates/blocks/news-page-archive', null, [
		'query' => $paged_query,
	]);
	?>
</div>
<?php
wp_reset_postdata();
get_footer();
