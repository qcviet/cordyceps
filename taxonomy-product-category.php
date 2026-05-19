<?php

/**
 * Product category archive — same layout as Product Page, filtered by term.
 *
 * @package cordyceps
 * @author biogreen
 * @since 0.0.1
 */

get_header();

$term = get_queried_object();
$active_category_id = $term instanceof WP_Term ? (int) $term->term_id : 0;

cordyceps_render_product_landing_page(
	[
		'active_category_id' => $active_category_id,
	]
);

get_footer();
