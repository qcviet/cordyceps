<?php

/**
 * Theme Init Class
 *
 * @package cordyceps
 * @author biogreen
 * @since 0.0.1
 */

namespace Cordyceps;

include_once get_theme_file_path('inc/helpers/env.php');
include_once get_theme_file_path('inc/helpers/cdn.php');
include_once get_theme_file_path('inc/helpers/query-limits.php');
include_once get_theme_file_path('inc/helpers/queries.php');
include_once get_theme_file_path('inc/helpers/formatting.php');
include_once get_theme_file_path('inc/helpers/template-tags.php');
include_once get_theme_file_path('inc/helpers/featured-product.php');
include_once get_theme_file_path('inc/helpers/news-page.php');
include_once get_theme_file_path('inc/helpers/contact-page.php');
include_once get_theme_file_path('inc/helpers/product-single.php');
include_once get_theme_file_path('inc/helpers/product-rewrite.php');
include_once get_theme_file_path('inc/helpers/post-single.php');
include_once get_theme_file_path('inc/helpers/post-rewrite.php');
include_once get_theme_file_path('inc/helpers/footer.php');
include_once get_theme_file_path('inc/helpers/debug.php');

require_once get_theme_file_path('inc/ajax/featured-product-ajax.php');

class Theme_Init
{
	var $theme_version;

	public function __construct()
	{
		$this->theme_version = WP_DEBUG ? time() : wp_get_theme()->Get('Version');

		add_filter('gform_disable_css', '__return_true');

		add_action('wp_head', [$this, 'preconnect_google_fonts'], 1);

		add_action('wp_enqueue_scripts', [$this, 'critical_frontend_assets'], 1);
		add_action('wp_enqueue_scripts', [$this, 'register_frontend_assets'], 60);
		add_action('wp_enqueue_scripts', [$this, 'localize_featured_product_script'], 70);

		$this->register_layout_hooks();

		add_action('after_setup_theme', [$this, 'theme_supports']);

		add_filter('body_class', [$this, 'body_class_hero_landing']);
	}

	/**
	 * Body class for page templates that use hero-banner full-bleed layout.
	 *
	 * @param string[] $classes
	 * @return string[]
	 */
	public function body_class_hero_landing($classes)
	{
		if (is_page_template('templates/introduce-page.php')) {
			$classes[] = 'cordyceps-introduce-landing';
		}

		if (is_page_template('templates/product-page.php')) {
			$classes[] = 'cordyceps-product-landing';
		}

		if (is_page_template('templates/news-page.php')) {
			$classes[] = 'cordyceps-news-landing';
		}

		if (is_page_template('templates/contact-page.php')) {
			$classes[] = 'cordyceps-contact-landing';
		}

		if (is_singular('product')) {
			$classes[] = 'cordyceps-product-single';
		}

		if (is_singular('post')) {
			$classes[] = 'cordyceps-post-single';
		}

		return $classes;
	}

	function preconnect_google_fonts()
	{
?>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<?php
	}

	/**
	 * Register critical frontend assets
	 */
	function critical_frontend_assets()
	{
		$variables_css_context = file_get_contents(get_theme_file_path('variables.css'));

		if (!empty($variables_css_context)) {
			wp_register_style('cordyceps-variables', false);
			wp_enqueue_style('cordyceps-variables', false);
			wp_add_inline_style('cordyceps-variables', \cordyceps_format_css_variables($variables_css_context));
		}
	}

	function register_frontend_assets()
	{
		wp_enqueue_style('cordyceps-bootstrap', $this->resolve_asset_uri('css', 'bootstrap'), [], $this->theme_version);
		wp_enqueue_style('cordyceps-frontend', $this->resolve_asset_uri('css', 'frontend'), ['cordyceps-bootstrap'], $this->theme_version);

		wp_enqueue_script('cordyceps-bootstrap', $this->resolve_asset_uri('js', 'bootstrap'), [], $this->theme_version, true);
		wp_enqueue_script('cordyceps-frontend', $this->resolve_asset_uri('js', 'frontend'), ['cordyceps-bootstrap'], $this->theme_version, true);

		wp_enqueue_style('cordyceps-fonts', get_stylesheet_directory_uri() . '/static-assets/fonts/font.css', [], $this->theme_version);
		wp_enqueue_style('font-awesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css', [], '5.15.4');

		wp_enqueue_style('cordyceps-custom-theme', get_stylesheet_uri(), [], $this->theme_version);
	}

	/**
	 * Pass AJAX config to featured product block script.
	 */
	function localize_featured_product_script()
	{
		wp_localize_script(
			'cordyceps-frontend',
			'cordycepsFeaturedProduct',
			[
				'ajaxUrl' => admin_url('admin-ajax.php'),
				'nonce' => wp_create_nonce('cordyceps_featured_product'),
				'action' => 'cordyceps_filter_featured_products',
				'emptyText' => esc_html__('Chưa có sản phẩm trong danh mục này.', 'cordyceps'),
				'errorText' => esc_html__('Không thể tải sản phẩm. Vui lòng thử lại.', 'cordyceps'),
			]
		);
	}

	/**
	 * Prefer development assets locally, but always fallback to existing files.
	 */
	function resolve_asset_uri($type, $name)
	{
		$localhost_callback = __NAMESPACE__ . '\\cordyceps_is_localhost';
		$is_localhost = function_exists($localhost_callback) ? cordyceps_is_localhost() : false;
		$preferred_suffix = $is_localhost ? '' : '.min';
		$fallback_suffix = $preferred_suffix === '.min' ? '' : '.min';

		$preferred_relative_path = "assets/{$type}/{$name}{$preferred_suffix}.{$type}";
		$fallback_relative_path = "assets/{$type}/{$name}{$fallback_suffix}.{$type}";

		if (file_exists(get_theme_file_path($preferred_relative_path))) {
			return get_stylesheet_directory_uri() . '/' . $preferred_relative_path;
		}

		if (file_exists(get_theme_file_path($fallback_relative_path))) {
			return get_stylesheet_directory_uri() . '/' . $fallback_relative_path;
		}

		return get_stylesheet_directory_uri() . '/' . $preferred_relative_path;
	}

	function register_layout_hooks()
	{
		require_once get_theme_file_path('inc/layouts/container.php');
		require_once get_theme_file_path('inc/layouts/archive.php');
		require_once get_theme_file_path('inc/layouts/singular.php');
		require_once get_theme_file_path('inc/layouts/product-single.php');
		require_once get_theme_file_path('inc/layouts/post-single.php');
		require_once get_theme_file_path('inc/layouts/global.php');
		require_once get_theme_file_path('inc/layouts/footer.php');
	}

	function theme_supports()
	{
		load_theme_textdomain('cordyceps', get_theme_file_path('languages'));
	}
}

new Theme_Init();
