<?php

/**
 * Contact page helpers.
 *
 * @package cordyceps
 * @author biogreen
 * @since 0.0.1
 */

if (!defined('ABSPATH')) {
	exit;
}

/**
 * Allowed HTML for Google Maps iframe embed.
 *
 * @return array<string, array<string, bool>>
 */
function cordyceps_contact_map_allowed_html()
{
	return [
		'iframe' => [
			'src' => true,
			'width' => true,
			'height' => true,
			'style' => true,
			'frameborder' => true,
			'allowfullscreen' => true,
			'loading' => true,
			'referrerpolicy' => true,
			'title' => true,
			'aria-hidden' => true,
			'tabindex' => true,
			'allow' => true,
		],
	];
}

/**
 * Collect ACF contact page fields for the current page.
 *
 * @param int $post_id Page ID.
 * @return array<string, mixed>
 */
function cordyceps_get_contact_page_data($post_id = 0)
{
	$post_id = $post_id > 0 ? $post_id : (int) get_queried_object_id();

	if ($post_id <= 0) {
		return [];
	}

	$brochure = get_field('contact_brochure_file', $post_id);
	$brochure_url = '';
	$brochure_filename = '';

	if (is_array($brochure) && !empty($brochure['url'])) {
		$brochure_url = (string) $brochure['url'];
		$brochure_filename = !empty($brochure['filename'])
			? (string) $brochure['filename']
			: basename($brochure_url);
	}

	$map_iframe = get_field('contact_map_iframe', $post_id);
	$map_iframe = is_string($map_iframe) && '' !== trim($map_iframe)
		? wp_kses($map_iframe, cordyceps_contact_map_allowed_html())
		: '';

	$form_id = get_field('contact_form_id', $post_id);
	$form_id = is_scalar($form_id) ? trim((string) $form_id) : '';

	return [
		'class' => '',
		'contact_title' => (string) get_field('contact_title', $post_id),
		'contact_address' => (string) get_field('contact_address', $post_id),
		'contact_phone' => (string) get_field('contact_phone', $post_id),
		'contact_email' => (string) get_field('contact_email', $post_id),
		'contact_facebook' => (string) get_field('contact_facebook', $post_id),
		'contact_working_time' => (string) get_field('contact_working_time', $post_id),
		'contact_brochure_url' => $brochure_url,
		'contact_brochure_filename' => $brochure_filename,
		'contact_map_iframe' => $map_iframe,
		'contact_form_id' => $form_id,
	];
}

/**
 * Whether the contact section has any renderable content.
 *
 * @param array<string, mixed> $data Contact page data.
 * @return bool
 */
function cordyceps_contact_page_has_content(array $data)
{
	$info_fields = [
		'contact_title',
		'contact_address',
		'contact_phone',
		'contact_email',
		'contact_facebook',
		'contact_working_time',
		'contact_brochure_url',
	];

	foreach ($info_fields as $field) {
		if (!empty($data[$field])) {
			return true;
		}
	}

	return !empty($data['contact_map_iframe']) || !empty($data['contact_form_id']);
}

/**
 * Disable CF7 default styles on contact page template.
 */
function cordyceps_contact_page_disable_cf7_css()
{
	if (!is_page_template('templates/contact-page.php')) {
		return;
	}

	add_filter('wpcf7_load_css', '__return_false');
}

add_action('wp', 'cordyceps_contact_page_disable_cf7_css');
