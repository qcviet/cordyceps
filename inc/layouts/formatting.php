<?php
/**
 * Formatting helper functions
 *
 * @package cordyceps
 * @author biogreen
 * @since 0.0.1
 */
if (!function_exists('cordyceps_format_css_variables')) {
	/**
	 * Keep CSS variables payload as-is for inline output.
	 *
	 * @param string $css_variables
	 * @return string
	 */
	function cordyceps_format_css_variables($css_variables)
	{
		return is_string($css_variables) ? trim($css_variables) : '';
	}
}
