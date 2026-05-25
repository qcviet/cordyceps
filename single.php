<?php

/**
 * Single template: Blog post.
 *
 * @package cordyceps
 * @author biogreen
 * @since 0.0.1
 */

get_header();

while (have_posts()) :
	the_post();

	$data = cordyceps_get_post_single_data(get_the_ID());
	$post_id = !empty($data['post_id']) ? (int) $data['post_id'] : get_the_ID();
	$page_url = !empty($data['permalink']) ? $data['permalink'] : get_permalink($post_id);
	$share_title = !empty($data['title']) ? $data['title'] : get_the_title($post_id);
	$reading_label = !empty($data['reading_time']) ? cordyceps_format_post_reading_time($data['reading_time']) : '';
	$sidebar_query = cordyceps_query_post_single_sidebar($post_id, 8);

	$facebook_share_url = cordyceps_get_post_share_url('facebook', $page_url, $share_title);
	$twitter_share_url = cordyceps_get_post_share_url('twitter', $page_url, $share_title);
	?>
<main id="primary" class="post-single">
	<div class="post-single__wrap">
		<div class="post-single__container">
			<div class="post-single__layout">
				<article class="post-single__article" itemscope itemtype="https://schema.org/Article">
					<div class="post-single__card">
						<header class="post-single__header">
							<div class="post-single__header-row">
								<p class="post-single__label"><?php esc_html_e('TIN TỨC', 'cordyceps'); ?></p>

								<?php if (!empty($data['share_enable'])) : ?>
									<div class="post-single__share" aria-label="<?php esc_attr_e('Chia sẻ bài viết', 'cordyceps'); ?>">
										<ul class="post-single__share-list">
											<li>
												<a
													class="post-single__share-btn"
													href="<?php echo esc_url($facebook_share_url); ?>"
													target="_blank"
													rel="noopener noreferrer"
													aria-label="<?php esc_attr_e('Chia sẻ Facebook', 'cordyceps'); ?>"
												>
													<span class="post-single__share-icon" aria-hidden="true"><?php echo cordyceps_get_svg_icon('brand-facebook'); ?></span>
												</a>
											</li>
											<li>
												<a
													class="post-single__share-btn"
													href="<?php echo esc_url($twitter_share_url); ?>"
													target="_blank"
													rel="noopener noreferrer"
													aria-label="<?php esc_attr_e('Chia sẻ X (Twitter)', 'cordyceps'); ?>"
												>
													<span class="post-single__share-icon" aria-hidden="true"><?php echo cordyceps_get_svg_icon('brand-twitter'); ?></span>
												</a>
											</li>
											<li>
												<button
													type="button"
													class="post-single__share-btn post-single__share-btn--copy"
													data-post-copy-link
													data-copy-url="<?php echo esc_url($page_url); ?>"
													aria-label="<?php esc_attr_e('Sao chép liên kết', 'cordyceps'); ?>"
												>
													<span class="post-single__share-icon" aria-hidden="true"><?php echo cordyceps_get_svg_icon('link'); ?></span>
												</button>
											</li>
										</ul>
										<p class="post-single__share-feedback" data-post-copy-feedback hidden role="status">
											<?php esc_html_e('Đã sao chép liên kết!', 'cordyceps'); ?>
										</p>
									</div>
								<?php endif; ?>
							</div>

							<?php if (!empty($data['featured_image_id'])) : ?>
								<figure class="post-single__figure">
									<?php
									get_template_part('templates/core-blocks/image', null, [
										'image_id' => (int) $data['featured_image_id'],
										'image_size' => 'large',
										'lazyload' => false,
										'class' => 'post-single__image-figure image--cover',
										'image_class' => 'post-single__image-img',
									]);
									?>
								</figure>
							<?php endif; ?>

							<?php if (!empty($data['title'])) : ?>
								<h1 class="post-single__title" itemprop="headline"><?php echo esc_html($data['title']); ?></h1>
							<?php endif; ?>

							<?php if (!empty($data['short_desc'])) : ?>
								<p class="post-single__lead" itemprop="description"><?php echo esc_html($data['short_desc']); ?></p>
							<?php endif; ?>

							<ul class="post-single__meta">
								<?php if (!empty($data['date_label'])) : ?>
									<li class="post-single__meta-item">
										<span class="post-single__meta-icon" aria-hidden="true"><?php echo cordyceps_get_svg_icon('calendar'); ?></span>
										<time datetime="<?php echo esc_attr($data['date_iso']); ?>" itemprop="datePublished"><?php echo esc_html($data['date_label']); ?></time>
									</li>
								<?php endif; ?>

								<?php if (!empty($data['category_links'])) : ?>
									<li class="post-single__meta-item post-single__meta-item--categories">
										<span class="post-single__meta-icon" aria-hidden="true"><?php echo cordyceps_get_svg_icon('tag'); ?></span>
										<span class="post-single__meta-cats">
											<?php
											$cat_count = count($data['category_links']);
											foreach ($data['category_links'] as $index => $category) :
												?>
												<a href="<?php echo esc_url($category['url']); ?>" rel="category tag"><?php echo esc_html($category['name']); ?></a><?php echo $index < $cat_count - 1 ? '<span class="post-single__meta-sep">,</span> ' : ''; ?>
											<?php endforeach; ?>
										</span>
									</li>
								<?php endif; ?>

								<?php if ('' !== $reading_label) : ?>
									<li class="post-single__meta-item">
										<span class="post-single__meta-icon" aria-hidden="true"><?php echo cordyceps_get_svg_icon('clock'); ?></span>
										<span><?php echo esc_html($reading_label); ?></span>
									</li>
								<?php endif; ?>

								<?php if (!empty($data['author_name'])) : ?>
									<li class="post-single__meta-item">
										<span class="post-single__meta-icon" aria-hidden="true"><?php echo cordyceps_get_svg_icon('user'); ?></span>
										<span itemprop="author"><?php echo esc_html($data['author_name']); ?></span>
									</li>
								<?php endif; ?>
							</ul>
						</header>

						<div class="post-single__content entry-content cordyceps-rich-content" itemprop="articleBody">
							<?php the_content(); ?>
						</div>
					</div>
				</article>

				<aside class="post-single__sidebar" aria-labelledby="post-single-sidebar-title">
					<div class="post-single__sidebar-card">
						<h2 id="post-single-sidebar-title" class="post-single__sidebar-title"><?php esc_html_e('Bài viết mới', 'cordyceps'); ?></h2>

						<?php if ($sidebar_query->have_posts()) : ?>
							<ul class="post-single__sidebar-list">
								<?php
								while ($sidebar_query->have_posts()) :
									$sidebar_query->the_post();
									$sidebar_id = get_the_ID();
									$sidebar_thumb = (int) get_post_thumbnail_id($sidebar_id);
									?>
									<li class="post-single__sidebar-item">
										<a class="post-single__sidebar-link" href="<?php the_permalink(); ?>">
											<span class="post-single__sidebar-thumb">
												<?php if ($sidebar_thumb) : ?>
													<?php
													get_template_part('templates/core-blocks/image', null, [
														'image_id' => $sidebar_thumb,
														'image_size' => 'thumbnail',
														'lazyload' => true,
														'class' => 'post-single__sidebar-image-figure image--cover',
														'image_class' => 'post-single__sidebar-image-img',
													]);
													?>
												<?php else : ?>
													<span class="post-single__sidebar-thumb-placeholder" aria-hidden="true"></span>
												<?php endif; ?>
											</span>
											<span class="post-single__sidebar-body">
												<span class="post-single__sidebar-item-title"><?php the_title(); ?></span>
												<time class="post-single__sidebar-date" datetime="<?php echo esc_attr(get_the_date('c')); ?>"><?php echo esc_html(get_the_date('d/m/Y')); ?></time>
											</span>
										</a>
									</li>
								<?php endwhile; ?>
							</ul>
						<?php else : ?>
							<p class="post-single__sidebar-empty"><?php esc_html_e('Chưa có bài viết khác.', 'cordyceps'); ?></p>
						<?php endif; ?>
					</div>
				</aside>
			</div>
		</div>
	</div>
</main>
<script>
(function () {
	var buttons = document.querySelectorAll('[data-post-copy-link]');
	if (!buttons.length) {
		return;
	}

	var feedback = document.querySelector('[data-post-copy-feedback]');

	function showFeedback() {
		if (!feedback) {
			return;
		}

		feedback.hidden = false;
		window.setTimeout(function () {
			feedback.hidden = true;
		}, 2800);
	}

	function copyText(text) {
		if (navigator.clipboard && window.isSecureContext) {
			return navigator.clipboard.writeText(text);
		}

		var textarea = document.createElement('textarea');
		textarea.value = text;
		textarea.setAttribute('readonly', '');
		textarea.style.position = 'absolute';
		textarea.style.left = '-9999px';
		document.body.appendChild(textarea);
		textarea.select();
		document.execCommand('copy');
		document.body.removeChild(textarea);
		return Promise.resolve();
	}

	buttons.forEach(function (button) {
		button.addEventListener('click', function () {
			var url = button.getAttribute('data-copy-url') || window.location.href;
			copyText(url).then(showFeedback).catch(function () {});
		});
	});
})();
</script>
	<?php
	wp_reset_postdata();
endwhile;

get_footer();
