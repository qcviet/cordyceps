<?php

/**
 * Core Block: Product card (image, title, link only).
 *
 * @package cordyceps
 * @author biogreen
 * @since 0.0.1
 */

$data = wp_parse_args($args, [
	'post_id' => 0,
	'class' => '',
]);

$post_id = absint($data['post_id']);

if ($post_id < 1) {
	return;
}

$permalink = get_permalink($post_id);
$title = get_the_title($post_id);
$thumbnail_id = (int) get_post_thumbnail_id($post_id);

if (!$permalink || !$title) {
	return;
}

$_class = 'fp__card';
$_class .= !empty($data['class']) ? ' ' . esc_attr($data['class']) : '';
?>

<article class="<?php echo esc_attr($_class); ?>">
	<a class="fp__card-link" href="<?php echo esc_url($permalink); ?>">
		<div class="fp__image">
			<?php if ($thumbnail_id) : ?>
				<?php
				get_template_part('templates/core-blocks/image', null, [
					'image_id' => $thumbnail_id,
					'image_size' => 'woocommerce_thumbnail',
					'lazyload' => true,
					'class' => 'fp__image-figure image--cover',
					'image_class' => 'fp__image-img',
				]);
				?>
			<?php else : ?>
				<span class="fp__image-placeholder" aria-hidden="true"></span>
			<?php endif; ?>
		</div>
		<div class="fp__card-body">
			<h3 class="fp__card-title"><?php echo esc_html($title); ?></h3>
		</div>
	</a>
</article>
