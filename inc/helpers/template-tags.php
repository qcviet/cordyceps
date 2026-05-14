<?php

/**
 * Template Tags
 *
 * @package cordyceps
 * @author biogreen
 * @since 0.0.1
 */

function cordyceps_get_svg_icon($name)
{
	$value = '';

	switch ($name):
		case 'chevron-left':
			$value = '<svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M15 6l-6 6l6 6" /></svg>';
			break;

		case 'chevron-right':
			$value = '<svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M9 6l6 6l-6 6" /></svg>';
			break;

		case 'plus':
			$value = '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 5v14"/><path d="M5 12h14"/></svg>';
			break;

		case 'arrow-right':
			$value = '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-arrow-narrow-right"><path stroke="none" d="M0 0h24v24H0z" fill="none" /><path d="M5 12l14 0" /><path d="M15 16l4 -4" /><path d="M15 8l4 4" /></svg>';
			break;
		case 'search':
			$value = '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" focusable="false" aria-hidden="true"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M10 10m-7 0a7 7 0 1 0 14 0a7 7 0 1 0 -14 0" /><path d="M21 21l-6 -6" /></svg>';
			break;
		case 'leaf':
			$value = '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-leaf"><path stroke="none" d="M0 0h24v24H0z" fill="none" /><path d="M5 21c.5 -4.5 2.5 -8 7 -10" /><path d="M9 18c6.218 0 10.5 -3.288 11 -12v-2h-4.014c-9 0 -11.986 4 -12 9c0 1 0 3 2 5h3l.014 0" /></svg>';
			break;
		case 'plant':
			$value = '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-plant-2"><path stroke="none" d="M0 0h24v24H0z" fill="none" /><path d="M2 9a10 10 0 1 0 20 0" /><path d="M12 19a10 10 0 0 1 10 -10" /><path d="M2 9a10 10 0 0 1 10 10" /><path d="M12 4a9.7 9.7 0 0 1 2.99 7.5" /><path d="M9.01 11.5a9.7 9.7 0 0 1 2.99 -7.5" /></svg>';
			break;
		case 'chemistry':
			$value = '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-flask"><path stroke="none" d="M0 0h24v24H0z" fill="none" /><path d="M9 3l6 0" /><path d="M10 9l4 0" /><path d="M10 3v6l-4 11a.7 .7 0 0 0 .5 1h11a.7 .7 0 0 0 .5 -1l-4 -11v-6" /></svg>';
			break;
		case 'shield':
			$value = '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-shield-check"><path stroke="none" d="M0 0h24v24H0z" fill="none" /><path d="M11.46 20.846a12 12 0 0 1 -7.96 -14.846a12 12 0 0 0 8.5 -3a12 12 0 0 0 8.5 3a12 12 0 0 1 -.09 7.06" /><path d="M15 19l2 2l4 -4" /></svg>';
			break;
		case 'phone':
			$value = '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-phone"><path stroke="none" d="M0 0h24v24H0z" fill="none" /><path d="M5 4h4l2 5l-2.5 1.5a11 11 0 0 0 5 5l1.5 -2.5l5 2v4a2 2 0 0 1 -2 2a16 16 0 0 1 -15 -15a2 2 0 0 1 2 -2" /></svg>';
			break;
		case 'cooperation':
			$value = '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-heart-handshake"><path stroke="none" d="M0 0h24v24H0z" fill="none" /><path d="M19.5 12.572l-7.5 7.428l-7.5 -7.428a5 5 0 1 1 7.5 -6.566a5 5 0 1 1 7.5 6.572" /><path d="M12 6l-3.293 3.293a1 1 0 0 0 0 1.414l.543 .543c.69 .69 1.81 .69 2.5 0l1 -1a3.182 3.182 0 0 1 4.5 0l2.25 2.25" /><path d="M12.5 15.5l2 2" /><path d="M15 13l2 2" /></svg>';
			break;
		case 'handshake':
			$value = '<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" height="800px" width="800px" version="1.1" id="_x32_" viewBox="0 0 512 512" xml:space="preserve">
<style type="text/css">
	.st0{fill:#000000;}
</style>
<g>
	<path class="st0" d="M324.708,174.596c-12.583-2.092-41.546-2.322-65.932,2.992c-1.562-1.032-3.128-2.099-4.672-3.048   c-4.714-2.887-9.337-5.328-13.929-7.168c-4.452-1.771-8.891-2.991-13.535-3.236v-0.02c-6.956-0.565-13.709-0.816-19.918-0.816   c-9.453,0.014-17.517,0.537-23.493,1.534h0.024c-0.826,0.126-1.732,0.203-2.716,0.203c-5.164,0.062-12.312-2.392-18.797-5.935   c-4.372-2.343-9.79-1.102-12.705,2.915L77.86,260.171c-2.424,3.34-2.511,7.866-0.227,11.311c1.806,2.706,3.316,5.515,4.313,8.326   c1.433,3.968,3.508,7.573,5.903,11.081c2.398,3.493,5.136,6.882,7.994,10.118l1.12,1.073c0,0,5.125,4.226,13.291,10.948   c-2.131,3.055-3.302,6.681-3.344,10.502c-0.052,5.035,1.855,9.805,5.384,13.41c3.584,3.661,8.375,5.676,13.497,5.676   c3.588,0,7.018-1.032,10.006-2.922c-1.997,3.012-3.106,6.52-3.148,10.209c-0.056,5.035,1.858,9.798,5.383,13.396   c3.577,3.668,8.372,5.683,13.497,5.683c4.026,0,7.837-1.311,11.052-3.654c-5.85,7.336-5.533,18.039,1.192,24.922   c3.578,3.654,8.368,5.669,13.486,5.669h0.004c4.965,0,9.658-1.917,13.228-5.411l3.49-3.466c-1.461,2.678-2.333,5.648-2.368,8.78   c-0.052,5.041,1.855,9.804,5.383,13.409c3.581,3.654,8.375,5.669,13.497,5.669c4.961,0,9.65-1.91,13.252-5.425l2.124-2.155   c1.063,0.837,2.033,1.59,2.824,2.204c0.648,0.488,1.2,0.907,1.705,1.27l0.75,0.53l1.063,0.656c3.992,2.189,7.81,3.138,10.846,3.807   c1.513,0.314,2.81,0.53,3.772,0.662l1.172,0.154l0.366,0.035l0.139,0.014l0.087,0.014l0.094,0.007l0.126,0.007   c0.115,0,0.059,0.014,0.665,0.028l0.715-0.028c6.65-0.488,12.946-2.776,18.114-6.722c2.056-1.576,3.856-3.48,5.467-5.564   c3.183,1.032,6.554,1.645,10.062,1.645c14.228-0.006,26.202-9.079,30.839-21.7c0.516-0.035,1.029-0.056,1.537-0.188   c1.806,0.328,3.637,0.565,5.533,0.565c14.118-0.014,26.066-8.911,30.78-21.366c0.032-0.028,0.063-0.041,0.094-0.063l1.108,0.105   c18.238,0,33.001-14.777,33.008-33.004c0.011-5.063-0.69-10.662-2.618-16.485c12.517-11.059,30.86-28.144,34.176-37.418   c1.597-4.463,10.575-15.166,13.183-18.814l-70.621-97.394C351.848,165.377,334.369,176.214,324.708,174.596z M366.923,330.384   c-0.01,7.287-5.906,13.186-13.194,13.2c-1.806,0-3.493-0.362-5.065-1.018c-0.157-0.07-0.321-0.084-0.478-0.133l-12.238-14.888   c-3.096-3.584-8.518-3.988-12.106-0.893c-3.591,3.096-3.988,8.522-0.889,12.113l11.837,14.427   c-0.757,6.562-6.272,11.701-13.044,11.708c-3.215-0.007-6.066-1.15-8.399-3.068l-11.746-14.324   c-3.1-3.584-8.522-3.981-12.109-0.885c-3.592,3.096-3.986,8.522-0.89,12.105l8.183,10.196c-0.014,0.634-0.076,1.269,0.035,1.904   c0.143,0.802,0.209,1.526,0.209,2.196c-0.01,7.294-5.909,13.186-13.193,13.2c-1.904,0-3.657-0.439-5.275-1.15l-14.316-15.048   c-3.455-3.25-8.887-3.082-12.137,0.376c-3.253,3.452-3.085,8.891,0.366,12.133l6.718,7.88c-0.673,1.444-1.569,2.748-2.932,3.807   c-1.747,1.339-4.149,2.259-6.882,2.566c-0.798-0.118-1.883-0.3-3.173-0.606c-1.6-0.362-3.288-0.955-4.163-1.416   c-0.303-0.222-1.077-0.794-2.196-1.674c-0.635-0.488-1.356-1.045-2.158-1.687c1.45-6.094-0.112-12.775-4.815-17.586   c-3.581-3.661-8.375-5.676-13.497-5.676c-2.88,0-5.641,0.711-8.18,1.946l-0.076-0.063l0.087-0.098   c3.602-3.528,5.617-8.242,5.672-13.29c0.052-5.042-1.862-9.805-5.39-13.403c-3.578-3.654-8.374-5.676-13.493-5.676   c-4.003,0-7.796,1.296-10.997,3.612c2.59-3.264,4.094-7.225,4.139-11.457c0.056-5.042-1.858-9.805-5.384-13.403   c-3.58-3.668-8.374-5.683-13.496-5.683c-4.961,0-9.651,1.911-13.221,5.411l-3.183,3.166c3.926-7.113,3.02-16.22-2.943-22.314   c-3.584-3.661-8.378-5.676-13.5-5.676c-4.961,0-9.651,1.91-13.228,5.411l-2.106,2.113c-1.496-1.235-2.964-2.441-4.261-3.508   c-4.874-4.003-8.232-6.778-9.581-7.894c-2.28-2.601-4.39-5.216-6.039-7.642c-1.771-2.566-3.026-4.944-3.591-6.555   c-0.83-2.329-1.82-4.518-2.897-6.596l62.57-86.279c6.186,2.587,12.953,4.532,20.258,4.582c1.945,0,3.936-0.14,5.954-0.474h0.025   c4.166-0.711,11.592-1.27,20.205-1.255c5.68-0.014,11.928,0.223,18.315,0.739l0.436,0.028c1.569,0.042,4.181,0.6,7.353,1.883   l0.718,0.328c-26.728,12.07-64.606,35.292-77.054,39.447c-14.215,4.735-15.404,23.695,16.579,27.244   c31.989,3.563,60.419-16.589,67.525-20.131c5.101-2.552,37.508,0.565,55.458,2.531c7.915,7.817,14.853,15.069,19.912,20.55   l39.423,48.198l0.174,0.196c5.052,5.85,7.793,10.598,9.348,14.671C366.446,322.874,366.913,326.388,366.923,330.384z"/>
	<path class="st0" d="M510.641,229.622L415.374,98.233c-2.305-3.187-6.747-3.884-9.927-1.583l-42.198,30.599   c-3.18,2.301-3.888,6.75-1.58,9.93l95.268,131.389c2.304,3.18,6.746,3.891,9.926,1.59l42.205-30.605   C512.242,237.243,512.953,232.801,510.641,229.622z M478.226,241.427c-5.293,3.835-12.705,2.65-16.548-2.643   c-3.839-5.292-2.656-12.698,2.643-16.54c5.296-3.842,12.702-2.657,16.544,2.628C484.704,230.172,483.522,237.585,478.226,241.427z"/>
	<path class="st0" d="M148.757,127.248l-42.206-30.599c-3.173-2.301-7.618-1.604-9.926,1.583L1.354,229.622   c-2.308,3.18-1.59,7.622,1.583,9.93l42.198,30.605c3.18,2.301,7.621,1.59,9.922-1.59l95.272-131.389   C152.637,133.998,151.929,129.549,148.757,127.248z M120.554,141.92c-3.839,5.293-11.248,6.478-16.544,2.636   c-5.3-3.835-6.481-11.255-2.64-16.54c3.838-5.3,11.248-6.485,16.544-2.642C123.217,129.215,124.389,136.62,120.554,141.92z"/>
</g>
</svg>';
			break;
		case 'achievements':
			$value = '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-laurel-wreath-1"><path stroke="none" d="M0 0h24v24H0z" fill="none" /><path d="M6.436 8a8.6 8.6 0 0 0 -.436 2.727c0 4.017 2.686 7.273 6 7.273s6 -3.256 6 -7.273a8.6 8.6 0 0 0 -.436 -2.727" /><path d="M14.5 21s-.682 -3 -2.5 -3s-2.5 3 -2.5 3" /><path d="M18.52 5.23c.292 1.666 -1.02 2.77 -1.02 2.77s-1.603 -.563 -1.895 -2.23c-.292 -1.666 1.02 -2.77 1.02 -2.77s1.603 .563 1.895 2.23" /><path d="M21.094 12.14c-1.281 1.266 -3.016 .76 -3.016 .76s-.454 -1.772 .828 -3.04c1.28 -1.266 3.016 -.76 3.016 -.76s.454 1.772 -.828 3.04" /><path d="M17.734 18.826c-1.5 -.575 -1.734 -2.19 -1.734 -2.19s1.267 -1.038 2.767 -.462c1.5 .575 1.733 2.19 1.733 2.19s-1.267 1.038 -2.767 .462" /><path d="M6.267 18.826c1.5 -.575 1.733 -2.19 1.733 -2.19s-1.267 -1.038 -2.767 -.462c-1.5 .575 -1.733 2.19 -1.733 2.19s1.267 1.038 2.767 .462" /><path d="M2.906 12.14c1.281 1.266 3.016 .76 3.016 .76s.454 -1.772 -.828 -3.04c-1.281 -1.265 -3.016 -.76 -3.016 -.76s-.454 1.772 .828 3.04" /><path d="M5.48 5.23c-.292 1.666 1.02 2.77 1.02 2.77s1.603 -.563 1.895 -2.23c.292 -1.666 -1.02 -2.77 -1.02 -2.77s-1.603 .563 -1.895 2.23" /><path d="M11 9l1 -1v6" /></svg>';
			break;
		case 'team':
			$value = '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-users-group"><path stroke="none" d="M0 0h24v24H0z" fill="none" /><path d="M10 13a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" /><path d="M8 21v-1a2 2 0 0 1 2 -2h4a2 2 0 0 1 2 2v1" /><path d="M15 5a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" /><path d="M17 10h2a2 2 0 0 1 2 2v1" /><path d="M5 5a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" /><path d="M3 13v-1a2 2 0 0 1 2 -2h2" /></svg>';
			break;
		case 'growth':
			$value = '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-trending-up"><path stroke="none" d="M0 0h24v24H0z" fill="none" /><path d="M3 17l6 -6l4 4l8 -8" /><path d="M14 7l7 0l0 7" /></svg>';
			break;
		case 'target':
			$value = '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-target-arrow"><path stroke="none" d="M0 0h24v24H0z" fill="none" /><path d="M11 12a1 1 0 1 0 2 0a1 1 0 1 0 -2 0" /><path d="M12 7a5 5 0 1 0 5 5" /><path d="M13 3.055a9 9 0 1 0 7.941 7.945" /><path d="M15 6v3h3l3 -3h-3v-3l-3 3" /><path d="M15 9l-3 3" /></svg>';
			break;
		case 'menu':
			$value = '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-menu-2" focusable="false">
						<path stroke="none" d="M0 0h24v24H0z" fill="none" />
						<path d="M4 6l16 0" />
						<path d="M4 12l16 0" />
						<path d="M4 18l16 0" /></svg>';
			break;
		case 'close':
			$value = '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" focusable="false" aria-hidden="true"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M18 6l-12 12"/><path d="M6 6l12 12"/></svg>';
			break;
		case 'heart':
			$value = '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-heart"><path stroke="none" d="M0 0h24v24H0z" fill="none" /><path d="M19.5 12.572l-7.5 7.428l-7.5 -7.428a5 5 0 1 1 7.5 -6.566a5 5 0 1 1 7.5 6.572" /></svg>';
			break;
	endswitch;
	return $value;
}

function cordyceps_get_header_container_classes()
{
	$classes = ['header__container'];

	if (is_singular()) {
		$post_id = (int) get_queried_object_id();
		if ($post_id > 0) {
			$content_container = get_post_meta($post_id, '_generate-full-width-content', true);
			if ('true' === $content_container) {
				$classes[] = 'container-fluid';
			} else {
				$classes[] = 'container';
			}

			return apply_filters('cordyceps_header_container_classes', implode(' ', $classes));
		}
	}

	$classes[] = 'container';

	return apply_filters('cordyceps_header_container_classes', implode(' ', $classes));
}


function cordyceps_load_template_with_args( string $slug, array $args = [] ): void
{
	$located = locate_template( [ $slug . '.php' ], false, true );
	if ( '' === $located ) {
		return;
	}
	( static function ( string $_file, array $args ): void {
		include $_file;
	} )( $located, $args );
}

function cordyceps_get_flexible_content_data( $array )
{
	$items = [];

	foreach ( $array as $key => $field_key ) {
		if ( ! is_string( $field_key ) || '' === $field_key ) {
			continue;
		}
		$items[ $key ] = get_sub_field( $field_key );
	}

	return $items;
}

/**
 * Get term meta
 *
 * @param WP_Term $term_object
 * @return array
 */
function cordyceps_get_term_meta($term_object)
{
	return [
		'url' => get_term_link($term_object),
		'name' => $term_object->name
	];
}
