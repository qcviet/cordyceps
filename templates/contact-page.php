<?php

/**
 * Template Name: Contact Page
 * Template Post Type: page
 *
 * @package cordyceps
 * @author biogreen
 * @since 0.0.1
 */

get_header();

$contact_data = cordyceps_get_contact_page_data(get_queried_object_id());
?>
<div class="contact-page">
	<?php
	get_template_part('templates/blocks/contact-page-section', null, $contact_data);
	?>
</div>
<?php
get_footer();
