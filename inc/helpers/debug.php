<?php

/**
 * Debug Helper Functions
 *
 * @package cordyceps
 * @author biogreen
 * @since 0.0.1
 */

namespace Cordyceps\Helpers;

if (!defined('ABSPATH')) {
	exit;
}

function debug_template_loading($template)
{
	if (defined('WP_DEBUG') && \WP_DEBUG) {
		error_log('Template being loaded: ' . $template);
		error_log('Page template slug: ' . \get_page_template_slug(\get_the_ID()));
		error_log('Post type: ' . \get_post_type());
	}
	return $template;
}
\add_action('template_include', __NAMESPACE__ . '\\debug_template_loading');

function debug_acf_field_groups()
{
	if (!defined('WP_DEBUG') || !\WP_DEBUG) {
		return;
	}

	$field_groups = \acf_get_field_groups();
	error_log('All field groups: ' . print_r($field_groups, true));

	$field_group = \acf_get_field_group('group_page_builder');
	error_log('Field Group Location: ' . print_r($field_group['location'], true));

	$current_template = \get_page_template_slug();
	error_log('Current Template: ' . $current_template);

	$match = false;
	if ($field_group && $current_template) {
		foreach ($field_group['location'] as $rules) {
			foreach ($rules as $rule) {
				if (
					$rule['param'] === 'page_template' &&
					$rule['value'] === $current_template
				) {
					$match = true;
					break 2;
				}
			}
		}
	}
	error_log('Template Matches Field Group: ' . ($match ? 'yes' : 'no'));
}
\add_action('acf/init', __NAMESPACE__ . '\\debug_acf_field_groups');

function debug_template_paths()
{
	if (!defined('WP_DEBUG') || !\WP_DEBUG) {
		return;
	}

	error_log('Current template: ' . \get_page_template_slug());
	error_log('Current page ID: ' . \get_the_ID());

	error_log('Template Directory: ' . \get_template_directory());
	error_log('Stylesheet Directory: ' . \get_stylesheet_directory());
	error_log('Template URL: ' . \get_template_directory_uri());

	$template_paths = [
		'content/flexible.php',
		'templates/content/flexible.php'
	];

	foreach ($template_paths as $path) {
		$full_path = \locate_template($path);
		error_log("Template {$path}: " . ($full_path ? 'exists' : 'not found'));
	}
}
\add_action('init', __NAMESPACE__ . '\\debug_template_paths');
