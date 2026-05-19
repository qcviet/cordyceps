<?php

/**
 * Header
 *
 * @package cordyceps
 * @author biogreen
 * @since 0.0.1
 */

if (!defined('ABSPATH')) {
	exit;
}

$hotline_href = 'tel:+84972867686';
$hotline_label = __('Hotline: 0972 867 686', 'cordyceps');
$hotline_aria = __('Gọi hotline 0972 867 686', 'cordyceps');
$search_query = get_search_query(false);
?>

<header class="header" id="masthead" data-block="header" role="banner">
	<div class="header__overlay" data-header-overlay aria-hidden="true"></div>

	<div class="<?php echo esc_attr(cordyceps_get_header_container_classes()); ?>">
		<div class="header__inner">

			<div class="header__toolbar">
				<div class="header__logo">
					<?php if (has_custom_logo()) : ?>
						<?php the_custom_logo(); ?>
					<?php else : ?>
						<a href="<?php echo esc_url(home_url('/')); ?>" class="header__logo-link" rel="home">
							<span class="header__logo-text"><?php bloginfo('name'); ?></span>
						</a>
					<?php endif; ?>
				</div>

				<div class="header__actions">
					<div class="header__hotline">
						<a class="header__hotline-link" href="<?php echo esc_url($hotline_href); ?>" aria-label="<?php echo esc_attr($hotline_aria); ?>">
							<span class="header__hotline-icon" aria-hidden="true"><?php echo cordyceps_get_svg_icon('phone'); ?></span>
							<span class="header__hotline-label"><?php echo esc_html($hotline_label); ?></span>
						</a>
					</div>

					<button type="button" class="header__menu-toggle" aria-controls="site-header-navigation" aria-expanded="false" aria-label="<?php echo esc_attr__('Mở menu điều hướng', 'cordyceps'); ?>" data-label-open="<?php echo esc_attr__('Mở menu điều hướng', 'cordyceps'); ?>" data-label-close="<?php echo esc_attr__('Đóng menu điều hướng', 'cordyceps'); ?>" data-header-menu-toggle>
						<span class="header__menu-toggle-icons" aria-hidden="true">
							<span class="header__menu-toggle-icon header__menu-toggle-icon--open"><?php echo cordyceps_get_svg_icon('menu'); ?></span>
							<span class="header__menu-toggle-icon header__menu-toggle-icon--close"><?php echo cordyceps_get_svg_icon('close'); ?></span>
						</span>
					</button>
				</div>
			</div>

			<nav id="site-header-navigation" class="header__nav" aria-label="<?php echo esc_attr__('Primary navigation', 'cordyceps'); ?>">
				<div class="header__nav-body">
					<div class="header__search" role="search">
						<form class="header__search-form" method="get" action="<?php echo esc_url(home_url('/')); ?>">
							<label class="header__search-label" for="header-site-search"><?php esc_html_e('Tìm kiếm', 'cordyceps'); ?></label>
							<input
								type="search"
								name="s"
								id="header-site-search"
								class="header__search-input"
								value="<?php echo esc_attr($search_query); ?>"
								placeholder="<?php echo esc_attr__('Tìm kiếm', 'cordyceps'); ?>"
								autocomplete="off"
								maxlength="200"
								enterkeyhint="search"
								inputmode="search"
							/>
							<button type="submit" class="header__search-submit" aria-label="<?php echo esc_attr__('Tìm kiếm', 'cordyceps'); ?>">
								<span class="header__search-submit-icon" aria-hidden="true"><?php echo cordyceps_get_svg_icon('search'); ?></span>
							</button>
						</form>
					</div>
					<?php
					wp_nav_menu([
						'theme_location' => 'primary',
						'menu_class' => 'header__nav-list',
						'container' => false,
						'fallback_cb' => false,
						'depth' => function_exists('cordyceps_get_nav_menu_depth')
							? cordyceps_get_nav_menu_depth('primary')
							: 3,
					]);
					?>
				</div>
			</nav>

		</div>
	</div>
</header>
