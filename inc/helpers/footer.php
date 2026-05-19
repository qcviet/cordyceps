<?php

/**
 * Footer helpers (ACF Options + fallbacks).
 *
 * @package cordyceps
 * @author biogreen
 * @since 0.0.1
 */

if (!defined('ABSPATH')) {
	exit;
}

/**
 * ACF options page slug for footer fields.
 */
function cordyceps_footer_options_id()
{
	return 'option';
}

/**
 * Read a footer option field from ACF.
 *
 * @param string $name    Field name.
 * @param mixed  $default Default when empty.
 * @return mixed
 */
function cordyceps_get_footer_option($name, $default = '')
{
	if (!function_exists('get_field')) {
		return $default;
	}

	$value = get_field($name, cordyceps_footer_options_id());

	if (null === $value || false === $value || '' === $value) {
		return $default;
	}

	return $value;
}

/**
 * Footer data for template (filterable).
 *
 * @return array<string, mixed>
 */
function cordyceps_get_footer_data()
{
	$company_name = cordyceps_get_footer_option('footer_company_name', '');
	if ('' === $company_name) {
		$company_name = get_bloginfo('name');
	}

	$description = cordyceps_get_footer_option('footer_description', '');
	if ('' === $description) {
		$description = __(
			'Biogreen cam kết mang đến những sản phẩm đông trùng hạ thảo chất lượng cao vì sức khỏe cộng đồng.',
			'cordyceps'
		);
	}

	$data = [
		'logo_id' => (int) cordyceps_get_footer_option('footer_logo', 0),
		'company_name' => (string) $company_name,
		'description' => (string) $description,
		'social_links' => cordyceps_get_footer_social_links(),
		'pages_heading' => (string) cordyceps_get_footer_option('footer_pages_heading', __('TRANG', 'cordyceps')),
		'products_heading' => (string) cordyceps_get_footer_option('footer_products_heading', __('SẢN PHẨM', 'cordyceps')),
		'addresses_heading' => (string) cordyceps_get_footer_option('footer_addresses_heading', __('ĐỊA CHỈ', 'cordyceps')),
		'channels_heading' => (string) cordyceps_get_footer_option('footer_channels_heading', __('LIÊN HỆ', 'cordyceps')),
		'addresses' => cordyceps_get_footer_address_items(),
		'channels' => cordyceps_get_footer_channel_items(),
		'copyright' => cordyceps_get_footer_copyright_text(),
	];

	return apply_filters('cordyceps_footer_data', $data);
}

/**
 * Social profile URLs from ACF Options.
 *
 * @return array<string, string>
 */
function cordyceps_get_footer_social_links()
{
	$map = [
		'facebook' => 'footer_social_facebook',
		'zalo' => 'footer_social_zalo',
		'pinterest' => 'footer_social_pinterest',
		'twitter' => 'footer_social_twitter',
		'linkedin' => 'footer_social_linkedin',
	];

	$links = [];
	foreach ($map as $network => $field_name) {
		$url = (string) cordyceps_get_footer_option($field_name, '');
		$links[$network] = '' !== $url ? esc_url_raw($url) : '';
	}

	return apply_filters('cordyceps_footer_social_links', $links);
}

/**
 * Default address blocks (col 4).
 *
 * @return array<int, array<string, string>>
 */
function cordyceps_get_footer_address_defaults()
{
	return [
		[
			'icon' => 'building-factory',
			'label' => __('Nhà máy sản xuất', 'cordyceps'),
			'value' => __('Tổ dân phố Chẽ, Thị trấn Phồn Xương, Huyện Yên Thế, Tỉnh Bắc Giang, Việt Nam', 'cordyceps'),
		],
		[
			'icon' => 'building',
			'label' => __('Văn phòng đại diện', 'cordyceps'),
			'value' => __('H01-L23 An Phú Villa, Dương Nội, Hà Đông, Hà Nội', 'cordyceps'),
		],
		[
			'icon' => 'certificate',
			'label' => __('ĐKKD', 'cordyceps'),
			'value' => __('Số 210C Đội Cấn, Phường Đội Cấn, Quận Ba Đình, Thành phố Hà Nội, Việt Nam', 'cordyceps'),
		],
	];
}

/**
 * Address items for column 4.
 *
 * @return array<int, array<string, mixed>>
 */
function cordyceps_get_footer_address_items()
{
	$rows = cordyceps_get_footer_option('footer_addresses', []);
	$items = [];

	if (is_array($rows)) {
		foreach ($rows as $row) {
			if (!is_array($row)) {
				continue;
			}

			$label = isset($row['label']) ? trim((string) $row['label']) : '';
			$text = isset($row['text']) ? trim((string) $row['text']) : '';

			if ('' === $label && '' === $text) {
				continue;
			}

			$icon = isset($row['icon']) ? sanitize_key((string) $row['icon']) : 'map-pin';
			if ('' === $icon) {
				$icon = 'map-pin';
			}

			$items[] = [
				'icon' => $icon,
				'label' => $label,
				'value' => $text,
				'url' => '',
			];
		}
	}

	if (empty($items)) {
		$items = cordyceps_get_footer_address_defaults();
	}

	return apply_filters('cordyceps_footer_address_items', $items);
}

/**
 * Default channel blocks (col 5).
 *
 * @return array<int, array<string, mixed>>
 */
function cordyceps_get_footer_channel_defaults()
{
	return [
		[
			'icon' => 'phone',
			'label' => __('Phone - Tel', 'cordyceps'),
			'value' => '',
			'url' => '',
			'phones' => [
				[
					'label' => '0972.867.686',
					'href' => 'tel:+84972867686',
				],
				[
					'label' => '0246.660.9850',
					'href' => 'tel:+842466609850',
				],
			],
		],
		[
			'icon' => 'mail',
			'label' => __('Email', 'cordyceps'),
			'value' => 'kinhdoanh.biogreen@gmail.com',
			'url' => 'mailto:kinhdoanh.biogreen@gmail.com',
		],
		[
			'icon' => 'world',
			'label' => __('Website', 'cordyceps'),
			'value' => 'dongtrunghathaobg.vn',
			'url' => 'https://dongtrunghathaobg.vn',
		],
	];
}

/**
 * Channel items for column 5.
 *
 * @return array<int, array<string, mixed>>
 */
function cordyceps_get_footer_channel_items()
{
	$rows = cordyceps_get_footer_option('footer_channels', []);
	$items = [];

	if (is_array($rows)) {
		foreach ($rows as $row) {
			if (!is_array($row)) {
				continue;
			}

			$item = cordyceps_normalize_footer_channel_row($row);
			if (null !== $item) {
				$items[] = $item;
			}
		}
	}

	if (empty($items)) {
		$items = cordyceps_get_footer_channel_defaults();
	}

	return apply_filters('cordyceps_footer_channel_items', $items);
}

/**
 * Sanitize footer link (http, mailto, tel).
 *
 * @param string $link Raw link.
 */
function cordyceps_sanitize_footer_link($link)
{
	$link = trim((string) $link);

	if ('' === $link) {
		return '';
	}

	if (preg_match('#^(https?://|mailto:|tel:)#i', $link)) {
		return esc_url($link);
	}

	return esc_url('https://' . ltrim($link, '/'));
}

/**
 * Normalize one ACF channel repeater row.
 *
 * @param array<string, mixed> $row Repeater row.
 * @return array<string, mixed>|null
 */
function cordyceps_normalize_footer_channel_row(array $row)
{
	$label = isset($row['label']) ? trim((string) $row['label']) : '';
	$value = isset($row['value']) ? trim((string) $row['value']) : '';
	$value_2 = isset($row['value_2']) ? trim((string) $row['value_2']) : '';
	$link = isset($row['link']) ? trim((string) $row['link']) : '';
	$link_2 = isset($row['link_2']) ? trim((string) $row['link_2']) : '';

	if ('' === $label && '' === $value && '' === $value_2) {
		return null;
	}

	$icon = isset($row['icon']) ? sanitize_key((string) $row['icon']) : 'link';
	if ('' === $icon) {
		$icon = 'link';
	}

	$item = [
		'icon' => $icon,
		'label' => $label,
		'value' => $value,
		'url' => cordyceps_sanitize_footer_link($link),
	];

	if ('' !== $value_2) {
		$phones = [];
		if ('' !== $value) {
			$phones[] = [
				'label' => $value,
				'href' => cordyceps_sanitize_footer_link($link),
			];
		}
		$phones[] = [
			'label' => $value_2,
			'href' => cordyceps_sanitize_footer_link($link_2),
		];
		$item['phones'] = array_values(array_filter($phones, static function ($phone) {
			return !empty($phone['label']);
		}));
		$item['value'] = '';
		$item['url'] = '';
	}

	return $item;
}

/**
 * ACF repeater links for a footer menu column.
 *
 * @param string $field_name ACF field name.
 * @return array<int, array<string, string>>
 */
function cordyceps_get_footer_acf_menu_links($field_name)
{
	$rows = cordyceps_get_footer_option($field_name, []);
	$links = [];

	if (!is_array($rows)) {
		return $links;
	}

	foreach ($rows as $row) {
		if (!is_array($row)) {
			continue;
		}

		$label = isset($row['label']) ? trim((string) $row['label']) : '';
		$url = isset($row['url']) ? trim((string) $row['url']) : '';

		if ('' === $label || '' === $url) {
			continue;
		}

		$links[] = [
			'label' => $label,
			'url' => esc_url_raw($url),
		];
	}

	return $links;
}

/**
 * Render footer link list from array.
 *
 * @param array<int, array<string, string>> $links Menu links.
 * @param string                            $class Menu UL class.
 */
function cordyceps_render_footer_link_list(array $links, $class = 'site-footer__menu')
{
	if (empty($links)) {
		return;
	}

	echo '<ul class="' . esc_attr($class) . '">';
	foreach ($links as $link) {
		printf(
			'<li class="site-footer__menu-item"><a class="site-footer__menu-link" href="%1$s">%2$s</a></li>',
			esc_url($link['url']),
			esc_html($link['label'])
		);
	}
	echo '</ul>';
}

/**
 * Footer nav column: WP menu → ACF repeater → PHP fallback.
 *
 * @param string   $theme_location Theme location slug.
 * @param string   $acf_field      ACF repeater field name.
 * @param callable $fallback_cb    Fallback callback.
 */
function cordyceps_render_footer_nav_column($theme_location, $acf_field, $fallback_cb)
{
	if (has_nav_menu($theme_location)) {
		wp_nav_menu(
			[
				'theme_location' => $theme_location,
				'menu_class' => 'site-footer__menu',
				'container' => false,
				'fallback_cb' => false,
				'depth' => 1,
				'item_spacing' => 'discard',
			]
		);
		return;
	}

	$acf_links = cordyceps_get_footer_acf_menu_links($acf_field);
	if (!empty($acf_links)) {
		cordyceps_render_footer_link_list($acf_links);
		return;
	}

	if (is_callable($fallback_cb)) {
		$fallback_cb(
			[
				'menu_class' => 'site-footer__menu',
				'cordyceps_footer_products' => 'footer-products' === $theme_location,
			]
		);
	}
}

/**
 * Render footer logo from ACF or Customizer.
 *
 * @param int $logo_id Attachment ID from ACF.
 */
function cordyceps_render_footer_logo($logo_id = 0)
{
	if ($logo_id > 0) {
		$image = wp_get_attachment_image(
			$logo_id,
			'medium',
			false,
			[
				'class' => 'custom-logo',
				'alt' => esc_attr(get_bloginfo('name')),
				'decoding' => 'async',
			]
		);

		if ($image) {
			printf(
				'<a href="%1$s" class="custom-logo-link" rel="home">%2$s</a>',
				esc_url(home_url('/')),
				$image
			);
			return;
		}
	}

	if (has_custom_logo()) {
		the_custom_logo();
		return;
	}

	printf(
		'<a href="%1$s" class="site-footer__logo-link" rel="home"><span class="site-footer__logo-text">%2$s</span></a>',
		esc_url(home_url('/')),
		esc_html(get_bloginfo('name'))
	);
}

/**
 * Render a single footer contact block.
 *
 * @param array<string, mixed> $item Contact item.
 */
function cordyceps_render_footer_contact_item(array $item)
{
	?>
	<div class="site-footer__contact-item">
		<span class="site-footer__contact-icon" aria-hidden="true">
			<?php echo cordyceps_get_svg_icon($item['icon']); ?>
		</span>
		<div class="site-footer__contact-body">
			<?php if (!empty($item['label'])) : ?>
				<p class="site-footer__contact-label"><?php echo esc_html($item['label']); ?></p>
			<?php endif; ?>
			<?php if (!empty($item['phones']) && is_array($item['phones'])) : ?>
				<p class="site-footer__contact-value">
					<?php
					$phone_links = [];
					foreach ($item['phones'] as $phone) {
						if (empty($phone['label'])) {
							continue;
						}
						if (!empty($phone['href'])) {
							$phone_links[] = sprintf(
								'<a class="site-footer__contact-link" href="%1$s">%2$s</a>',
								esc_url($phone['href']),
								esc_html($phone['label'])
							);
						} else {
							$phone_links[] = esc_html($phone['label']);
						}
					}
					echo wp_kses(
						implode('<span class="site-footer__contact-sep"> - </span>', $phone_links),
						[
							'a' => [
								'class' => true,
								'href' => true,
							],
							'span' => [
								'class' => true,
							],
						]
					);
					?>
				</p>
			<?php elseif (!empty($item['url'])) : ?>
				<p class="site-footer__contact-value">
					<a class="site-footer__contact-link" href="<?php echo esc_url($item['url']); ?>">
						<?php echo esc_html($item['value']); ?>
					</a>
				</p>
			<?php elseif (!empty($item['value'])) : ?>
				<p class="site-footer__contact-value"><?php echo esc_html($item['value']); ?></p>
			<?php endif; ?>
		</div>
	</div>
	<?php
}

/**
 * Copyright line.
 */
function cordyceps_get_footer_copyright_text()
{
	$text = (string) cordyceps_get_footer_option('footer_copyright', '');
	if ('' === $text) {
		$year = (int) gmdate('Y');
		/* translators: %d: current year */
		$text = sprintf(__('© %d BioGreen. All rights reserved.', 'cordyceps'), $year);
	}

	return (string) apply_filters('cordyceps_footer_copyright_text', $text);
}

/**
 * Fallback links for footer page menu.
 *
 * @return array<int, array<string, string>>
 */
function cordyceps_get_footer_pages_fallback_links()
{
	return [
		[
			'url' => home_url('/'),
			'label' => __('Trang chủ', 'cordyceps'),
		],
		[
			'url' => home_url('/gioi-thieu/'),
			'label' => __('Giới thiệu', 'cordyceps'),
		],
		[
			'url' => home_url('/san-pham/'),
			'label' => __('Sản phẩm', 'cordyceps'),
		],
		[
			'url' => home_url('/tin-tuc/'),
			'label' => __('Tin tức', 'cordyceps'),
		],
		[
			'url' => home_url('/lien-he/'),
			'label' => __('Liên hệ', 'cordyceps'),
		],
	];
}

/**
 * Fallback links for footer products menu.
 *
 * @return array<int, array<string, string>>
 */
function cordyceps_get_footer_products_fallback_links()
{
	return [
		[
			'url' => home_url('/san-pham/'),
			'label' => __('Đông trùng hạ thảo tươi', 'cordyceps'),
		],
		[
			'url' => home_url('/san-pham/'),
			'label' => __('Đông trùng hạ thảo khô', 'cordyceps'),
		],
		[
			'url' => home_url('/san-pham/'),
			'label' => __('Đông trùng hạ thảo viên', 'cordyceps'),
		],
		[
			'url' => home_url('/san-pham/'),
			'label' => __('Quà tặng sức khỏe', 'cordyceps'),
		],
	];
}

/**
 * Render fallback footer menu list.
 *
 * @param array<string, mixed> $args wp_nav_menu args subset.
 */
function cordyceps_footer_nav_fallback($args)
{
	if (is_object($args)) {
		$args = (array) $args;
	}

	$is_products = !empty($args['cordyceps_footer_products']);
	$links = $is_products
		? cordyceps_get_footer_products_fallback_links()
		: cordyceps_get_footer_pages_fallback_links();

	cordyceps_render_footer_link_list(
		$links,
		!empty($args['menu_class']) ? (string) $args['menu_class'] : 'site-footer__menu'
	);
}

/**
 * Fallback menu for footer products column.
 *
 * @param array<string, mixed>|\stdClass $args Menu args.
 */
function cordyceps_footer_products_nav_fallback($args)
{
	if (is_object($args)) {
		$args = (array) $args;
	}

	$args['cordyceps_footer_products'] = true;
	cordyceps_footer_nav_fallback($args);
}

/**
 * Accessible label for footer social link.
 *
 * @param string $network Network slug.
 */
function cordyceps_get_footer_social_label($network)
{
	$labels = [
		'facebook' => __('Facebook', 'cordyceps'),
		'zalo' => __('Zalo', 'cordyceps'),
		'pinterest' => __('Pinterest', 'cordyceps'),
		'twitter' => __('Twitter', 'cordyceps'),
		'linkedin' => __('LinkedIn', 'cordyceps'),
	];

	return $labels[$network] ?? ucfirst($network);
}

/**
 * Map social network key to SVG icon name.
 *
 * @param string $network Network slug.
 */
function cordyceps_get_footer_social_icon_name($network)
{
	$map = [
		'facebook' => 'brand-facebook',
		'zalo' => 'brand-zalo',
		'pinterest' => 'brand-pinterest',
		'twitter' => 'brand-twitter',
		'linkedin' => 'brand-linkedin',
	];

	return $map[$network] ?? 'link';
}
