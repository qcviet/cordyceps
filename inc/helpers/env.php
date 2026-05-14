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

	$env =
		($_SERVER['HTTP_X_CORDYCEPS_THEME_ENV'] ?? null) ??
		($_SERVER['HTTP_X_CORDYCEPS_THEME_ENV'] ?? null);

	return !empty($env) && $env === 'development';
}
