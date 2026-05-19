<?php

/**
 * Search results template.
 *
 * @package cordyceps
 * @author biogreen
 * @since 0.0.1
 */

get_header();

$results = cordyceps_get_search_results_data();
?>
<div class="search-page search-page--hero-landing">
	<?php
	get_template_part('templates/blocks/search-hero', null, $results);
	get_template_part('templates/blocks/search-results', null, $results);
	?>
</div>
<?php
if ($results['products_query'] instanceof WP_Query) {
	wp_reset_postdata();
}

if ($results['posts_query'] instanceof WP_Query) {
	wp_reset_postdata();
}

get_footer();
