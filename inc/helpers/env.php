<?php

/**
 * Setup environments
 *
 * @package cordyceps
 * @author biogreen
 * @since 0.0.1
 */

namespace Cordyceps;

function cordyceps_is_localhost()
{
	$proxy_env = $_SERVER['HTTP_X_CORDYCEPS_THEME_ENV'] ?? null;

	if (!empty($proxy_env) && $proxy_env === 'development') {
		return true;
	}

	if (function_exists('wp_get_environment_type') && wp_get_environment_type() === 'local') {
		return true;
	}

	$host = isset($_SERVER['HTTP_HOST']) ? strtolower((string) $_SERVER['HTTP_HOST']) : '';

	if ('' === $host) {
		return false;
	}

	$host = preg_replace('/:\d+$/', '', $host);

	$local_suffixes = ['.test', '.local'];

	foreach ($local_suffixes as $suffix) {
		$suffix_length = strlen($suffix);

		if ($suffix_length > 0 && strlen($host) > $suffix_length && substr($host, -$suffix_length) === $suffix) {
			return true;
		}
	}

	return false;
}
