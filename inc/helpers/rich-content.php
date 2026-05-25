<?php

/**
 * Rich content (post/product body) normalization.
 *
 * @package cordyceps
 * @author biogreen
 * @since 0.0.1
 */

if (!defined('ABSPATH')) {
	exit;
}

/**
 * Strip typography-related inline CSS declarations from a style attribute.
 *
 * @param string   $style  Inline style string.
 * @param string[] $remove Property names to remove.
 * @return string
 */
function cordyceps_strip_inline_style_properties($style, array $remove)
{
	$style = trim((string) $style);

	if ('' === $style) {
		return '';
	}

	$remove = array_map('strtolower', $remove);
	$kept = [];

	foreach (array_filter(array_map('trim', explode(';', $style))) as $declaration) {
		$colon = strpos($declaration, ':');

		if (false === $colon) {
			continue;
		}

		$property = strtolower(trim(substr($declaration, 0, $colon)));

		if (!in_array($property, $remove, true)) {
			$kept[] = $declaration;
		}
	}

	return implode('; ', $kept);
}

/**
 * Remove pasted/editor inline font rules so theme typography applies consistently.
 *
 * @param string $html Post content HTML.
 * @return string
 */
function cordyceps_normalize_rich_content_html($html)
{
	$html = trim((string) $html);

	if ('' === $html) {
		return $html;
	}

	$strip_props = ['font-family', 'font-size', 'font', 'line-height'];

	libxml_use_internal_errors(true);

	$document = new DOMDocument();
	$wrapped = '<?xml encoding="utf-8" ?><div id="cordyceps-rich-content-root">' . $html . '</div>';
	$loaded = $document->loadHTML($wrapped, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);

	libxml_clear_errors();

	if (!$loaded) {
		return $html;
	}

	$xpath = new DOMXPath($document);
	$root = $document->getElementById('cordyceps-rich-content-root');

	if (!$root instanceof DOMElement) {
		return $html;
	}

	foreach ($xpath->query('//*[@style]') as $node) {
		if (!$node instanceof DOMElement) {
			continue;
		}

		$clean_style = cordyceps_strip_inline_style_properties($node->getAttribute('style'), $strip_props);

		if ('' === $clean_style) {
			$node->removeAttribute('style');
		} else {
			$node->setAttribute('style', $clean_style);
		}
	}

	/** @var DOMElement $font_node */
	foreach (iterator_to_array($xpath->query('//font')) as $font_node) {
		$parent = $font_node->parentNode;

		if (!$parent instanceof DOMNode) {
			continue;
		}

		while ($font_node->firstChild) {
			$parent->insertBefore($font_node->firstChild, $font_node);
		}

		$parent->removeChild($font_node);
	}

	$normalized = '';

	foreach ($root->childNodes as $child) {
		$normalized .= $document->saveHTML($child);
	}

	if (str_contains($normalized, 'lwptoc')) {
		$normalized = cordyceps_strip_lwptoc_heading_prefixes($normalized);
	}

	return $normalized;
}

/**
 * Remove decimal prefixes LuckyWP adds inside headings when TOC numbering is enabled.
 *
 * @param string $html Content HTML.
 * @return string
 */
function cordyceps_strip_lwptoc_heading_prefixes($html)
{
	return (string) preg_replace(
		'/<h([1-6])(\s[^>]*)?>(\s*(?:<[^>]+>)*\s*)\d+\.\s+/iu',
		'<h$1$2>$3',
		$html
	);
}

/**
 * Final pass on singular post/product content after plugins (e.g. LuckyWP TOC).
 *
 * @param string $content Filtered content.
 * @return string
 */
function cordyceps_filter_rich_content($content)
{
	if (!is_singular(['post', 'product']) || is_admin()) {
		return $content;
	}

	if ('' === trim((string) $content)) {
		return $content;
	}

	// PDP loads content via apply_filters( 'the_content' ) outside the main loop.
	if (!in_the_loop() && !is_main_query() && !is_singular('product')) {
		return $content;
	}

	return cordyceps_normalize_rich_content_html($content);
}

add_filter('the_content', 'cordyceps_filter_rich_content', 99);

/**
 * Block editor preview typography for post & product bodies.
 */
function cordyceps_enqueue_rich_content_editor_styles()
{
	$screen = function_exists('get_current_screen') ? get_current_screen() : null;

	if (!$screen || !in_array($screen->post_type, ['post', 'product'], true)) {
		return;
	}

	$theme_version = wp_get_theme()->get('Version');
	$relative_min  = 'assets/css/editor-product-content.min.css';
	$relative_dev  = 'assets/css/editor-product-content.css';
	$relative_path = file_exists(get_theme_file_path($relative_min)) ? $relative_min : $relative_dev;

	if (!file_exists(get_theme_file_path($relative_path))) {
		return;
	}

	wp_enqueue_style(
		'cordyceps-rich-content-editor',
		get_theme_file_uri($relative_path),
		[],
		$theme_version
	);
}

add_action('enqueue_block_editor_assets', 'cordyceps_enqueue_rich_content_editor_styles');
