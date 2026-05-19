<?php

/**
 * Site footer — luxury organic layout (5 columns, ACF Options).
 *
 * @package cordyceps
 * @author biogreen
 * @since 0.0.1
 */

if (!defined('ABSPATH')) {
	exit;
}

$footer = cordyceps_get_footer_data();
$social_links = $footer['social_links'];
?>

<footer class="site-footer" id="colophon" role="contentinfo">
	<div class="site-footer__glow" aria-hidden="true"></div>

	<div class="site-footer__main">
		<div class="site-footer__container">
			<div class="site-footer__grid site-footer__grid--5">

				<div class="site-footer__col site-footer__col--brand">
					<div class="site-footer__brand">
						<div class="site-footer__logo">
							<?php cordyceps_render_footer_logo((int) $footer['logo_id']); ?>
						</div>

						<?php if (!empty($footer['company_name'])) : ?>
							<p class="site-footer__company"><?php echo esc_html($footer['company_name']); ?></p>
						<?php endif; ?>

						<?php if (!empty($footer['description'])) : ?>
							<p class="site-footer__tagline"><?php echo wp_kses_post($footer['description']); ?></p>
						<?php endif; ?>

						<?php
						$has_social = array_filter($social_links);
						if (!empty($has_social)) :
							?>
							<ul class="site-footer__social" aria-label="<?php esc_attr_e('Mạng xã hội', 'cordyceps'); ?>">
								<?php foreach ($social_links as $network => $url) : ?>
									<?php if ('' === $url) {
										continue;
									} ?>
									<li class="site-footer__social-item">
										<a
											class="site-footer__social-link"
											href="<?php echo esc_url($url); ?>"
											target="_blank"
											rel="noopener noreferrer"
											aria-label="<?php echo esc_attr(cordyceps_get_footer_social_label($network)); ?>"
										>
											<span class="site-footer__social-icon" aria-hidden="true">
												<?php echo cordyceps_get_svg_icon(cordyceps_get_footer_social_icon_name($network)); ?>
											</span>
										</a>
									</li>
								<?php endforeach; ?>
							</ul>
						<?php endif; ?>
					</div>
				</div>

				<div class="site-footer__col site-footer__col--nav-cluster">
					<div class="site-footer__nav-cluster">
						<div class="site-footer__col site-footer__col--pages">
					<?php if (!empty($footer['pages_heading'])) : ?>
						<h2 class="site-footer__heading"><?php echo esc_html($footer['pages_heading']); ?></h2>
					<?php endif; ?>
					<nav class="site-footer__nav" aria-label="<?php esc_attr_e('Liên kết trang', 'cordyceps'); ?>">
						<?php
						cordyceps_render_footer_nav_column(
							'footer',
							'footer_pages_links',
							'cordyceps_footer_nav_fallback'
						);
						?>
					</nav>
						</div>

						<div class="site-footer__col site-footer__col--products">
					<?php if (!empty($footer['products_heading'])) : ?>
						<h2 class="site-footer__heading"><?php echo esc_html($footer['products_heading']); ?></h2>
					<?php endif; ?>
					<nav class="site-footer__nav" aria-label="<?php esc_attr_e('Liên kết sản phẩm', 'cordyceps'); ?>">
						<?php
						cordyceps_render_footer_nav_column(
							'footer-products',
							'footer_products_links',
							'cordyceps_footer_products_nav_fallback'
						);
						?>
					</nav>
						</div>
					</div>
				</div>

				<div class="site-footer__col site-footer__col--addresses">
					<?php if (!empty($footer['addresses_heading'])) : ?>
						<h2 class="site-footer__heading"><?php echo esc_html($footer['addresses_heading']); ?></h2>
					<?php endif; ?>
					<div class="site-footer__contact-list">
						<?php foreach ($footer['addresses'] as $item) : ?>
							<?php cordyceps_render_footer_contact_item($item); ?>
						<?php endforeach; ?>
					</div>
				</div>

				<div class="site-footer__col site-footer__col--channels">
					<?php if (!empty($footer['channels_heading'])) : ?>
						<h2 class="site-footer__heading"><?php echo esc_html($footer['channels_heading']); ?></h2>
					<?php endif; ?>
					<div class="site-footer__contact-list">
						<?php foreach ($footer['channels'] as $item) : ?>
							<?php cordyceps_render_footer_contact_item($item); ?>
						<?php endforeach; ?>
					</div>
				</div>

			</div>
		</div>
	</div>

	<div class="site-footer__bottom">
		<div class="site-footer__container">
			<p class="site-footer__copyright"><?php echo esc_html($footer['copyright']); ?></p>
		</div>
	</div>
</footer>
