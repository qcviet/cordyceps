<?php

/**
 * Product single: hero (image + summary).
 *
 * @package cordyceps
 * @author biogreen
 * @since 0.0.1
 */

$data = wp_parse_args($args, [
	'post_id' => 0,
	'title' => '',
	'permalink' => '',
	'thumbnail_id' => 0,
	'short_description' => '',
]);

$post_id = (int) $data['post_id'];
$page_url = !empty($data['permalink']) ? $data['permalink'] : get_permalink($post_id);
$share_title = !empty($data['title']) ? $data['title'] : get_the_title($post_id);
$share_text = !empty($data['short_description']) ? wp_strip_all_tags($data['short_description']) : '';
$facebook_share_url = cordyceps_get_product_share_url('facebook', $page_url);
$zalo_share_url = cordyceps_get_product_share_url('zalo', $page_url);
$messenger_share_url = cordyceps_get_product_share_url('messenger', $page_url);
$telegram_share_url = cordyceps_get_product_share_url('telegram', $page_url);
$share_menu_id = 'product-share-menu-' . $post_id;
?>

<section class="product-hero" aria-labelledby="product-hero-title">
	<div class="product-hero__inner container">
		<div class="product-hero__grid">
			<div class="product-hero__media">
				<?php if (!empty($data['thumbnail_id'])) : ?>
					<div class="product-hero__image-wrap">
						<?php
						get_template_part('templates/core-blocks/image', null, [
							'image_id' => (int) $data['thumbnail_id'],
							'image_size' => 'large',
							'lazyload' => false,
							'class' => 'product-hero__image-figure image--cover',
							'image_class' => 'product-hero__image-img',
						]);
						?>
					</div>
				<?php else : ?>
					<div class="product-hero__image-wrap product-hero__image-wrap--placeholder" aria-hidden="true"></div>
				<?php endif; ?>
			</div>

			<div class="product-hero__summary">
				<?php if (!empty($data['title'])) : ?>
					<h1 id="product-hero-title" class="product-hero__title">
						<span class="product-hero__title-accent" aria-hidden="true"></span>
						<span class="product-hero__title-text"><?php echo esc_html($data['title']); ?></span>
					</h1>
				<?php endif; ?>

				<?php if (!empty($data['short_description'])) : ?>
					<div class="product-hero__excerpt">
						<?php echo wp_kses_post(wpautop($data['short_description'])); ?>
					</div>
				<?php endif; ?>

				<div class="product-hero__share">
					<ul class="product-hero__share-actions d-flex flex-row flex-wrap align-items-center">
						<li>
							<a
								class="product-hero__share-action"
								href="<?php echo esc_url($facebook_share_url); ?>"
								target="_blank"
								rel="noopener noreferrer"
								aria-label="<?php esc_attr_e('Chia sẻ qua Facebook', 'cordyceps'); ?>"
							>
								<span class="product-hero__share-action-icon" aria-hidden="true">
									<?php echo cordyceps_get_svg_icon('brand-facebook'); ?>
								</span>
							</a>
						</li>
						<li>
							<a
								class="product-hero__share-action"
								href="<?php echo esc_url($zalo_share_url); ?>"
								target="_blank"
								rel="noopener noreferrer"
								aria-label="<?php esc_attr_e('Chia sẻ qua Zalo', 'cordyceps'); ?>"
							>
								<span class="product-hero__share-action-icon" aria-hidden="true">
									<?php echo cordyceps_get_svg_icon('brand-zalo'); ?>
								</span>
							</a>
						</li>
						<li
							class="product-hero__share-more"
							data-share-more
							data-share-url="<?php echo esc_url($page_url); ?>"
							data-share-title="<?php echo esc_attr($share_title); ?>"
							data-share-text="<?php echo esc_attr($share_text); ?>"
						>
							<button
								type="button"
								class="product-hero__share-action product-hero__share-more__trigger"
								data-share-more-trigger
								aria-haspopup="true"
								aria-expanded="false"
								aria-controls="<?php echo esc_attr($share_menu_id); ?>"
								aria-label="<?php esc_attr_e('Thêm tùy chọn chia sẻ', 'cordyceps'); ?>"
							>
								<span class="product-hero__share-action-icon" aria-hidden="true">
									<?php echo cordyceps_get_svg_icon('dots'); ?>
								</span>
							</button>
							<div
								id="<?php echo esc_attr($share_menu_id); ?>"
								class="product-hero__share-menu"
								data-share-menu
								hidden
							>
								<ul class="product-hero__share-menu-list">
									<li>
										<button type="button" class="product-hero__share-menu-item" data-native-share>
											<span class="product-hero__share-menu-icon" aria-hidden="true">
												<?php echo cordyceps_get_svg_icon('share'); ?>
											</span>
											<?php esc_html_e('Chia sẻ qua ứng dụng', 'cordyceps'); ?>
										</button>
									</li>
									<li>
										<button type="button" class="product-hero__share-menu-item" data-copy-link>
											<span class="product-hero__share-menu-icon" aria-hidden="true">
												<?php echo cordyceps_get_svg_icon('link'); ?>
											</span>
											<?php esc_html_e('Sao chép liên kết', 'cordyceps'); ?>
										</button>
									</li>
									<li>
										<a
											class="product-hero__share-menu-item"
											href="<?php echo esc_url($messenger_share_url); ?>"
											target="_blank"
											rel="noopener noreferrer"
										>
											<span class="product-hero__share-menu-icon" aria-hidden="true">
												<?php echo cordyceps_get_svg_icon('brand-messenger'); ?>
											</span>
											<?php esc_html_e('Messenger', 'cordyceps'); ?>
										</a>
									</li>
									<li>
										<a
											class="product-hero__share-menu-item"
											href="<?php echo esc_url($telegram_share_url); ?>"
											target="_blank"
											rel="noopener noreferrer"
										>
											<span class="product-hero__share-menu-icon" aria-hidden="true">
												<?php echo cordyceps_get_svg_icon('brand-telegram'); ?>
											</span>
											<?php esc_html_e('Telegram', 'cordyceps'); ?>
										</a>
									</li>
								</ul>
							</div>
						</li>
					</ul>
					<p class="product-hero__share-toast" data-share-feedback hidden role="status"></p>
				</div>
			</div>
		</div>
	</div>
</section>
