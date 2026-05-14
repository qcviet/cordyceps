<?php

/**
 * Block: Swiper
 *
 * @package cordyceps-component
 * @author biogreen
 * @since 0.0.1
 */

$data = wp_parse_args(
	$args,
	array(
		'class'                  => '',
		'data_block'             => '',
		'previous_svg_icon'      => 'chevron-left',
		'next_svg_icon'          => 'chevron-right',
		'settings' => [
			'autoplay' => false,
			'pagination' => true,
			'prevNextButtons' => true
		],
		'enable_container'       => false,
		'container_class'        => 'swiper-container',
		'items'                  => array()
	)
);

$_class  = 'js-swiper swiper';
$_class .= !empty($data['class']) ? esc_attr(' ' . $data['class']) : '';

$_settings = $data['settings'] ?? [];

if (!empty($data['items'])) :

	// Build markup
	ob_start();
?>
	<div class="swiper-wrapper">
		<?php foreach ($data['items'] as $item) :
			$_item_class = 'swiper-slide';

			if (!empty($item['class'])) :
				$_item_class .= esc_attr(' ' . $item['class']);
			endif;

			if (!empty($item['lazyload'])) :
				$_item_class .= ' is-not-loaded';
			endif;
		?>
			<div class="<?php echo esc_attr($_item_class); ?>">
				<?php if (!empty($item['lazyload'])) : ?>
					<noscript>
						<?php echo $item['content']; ?>
					</noscript>
				<?php else : ?>
					<?php echo $item['content']; ?>
				<?php endif; ?>
			</div><!-- Close .swiper-slide -->
		<?php endforeach; ?>
	</div><!-- Close .swiper-wrapper -->
	<?php if (!empty($_settings['pagination'])) : ?>
		<div class="swiper-pagination"></div>
	<?php endif; ?>
	<?php if (!empty($_settings['prevNextButtons'])) : ?>
		<div class="swiper-button swiper-button-prev">
			<span class="icon pe-none">
				<?php echo cordyceps_get_svg_icon($data['previous_svg_icon']); ?>
			</span>
		</div>
		<div class="swiper-button swiper-button-next">
			<span class="icon pe-none">
				<?php echo cordyceps_get_svg_icon($data['next_svg_icon']); ?>
			</span>
		</div>
	<?php endif; ?>
	<?php
	$slide_html = ob_get_clean();
	?>
	<div class="<?php echo esc_attr($_class); ?>" data-settings='<?php echo esc_attr(wp_json_encode($_settings)); ?>'
		<?php if (!empty($data['data_block'])) : ?>
		data-block="<?php echo esc_attr($data['data_block']); ?><?php endif; ?>">
		<?php if (!empty($data['enable_container'])) : ?>
			<div class="<?php echo esc_attr($data['container_class']); ?>"><?php echo $slide_html; // phpcs:ignore
																			?></div><!-- Close .swiper-container -->
		<?php else : ?>
			<?php echo $slide_html; // phpcs:ignore
			?>
		<?php endif; ?>
	</div>
<?php else : ?>
	<!-- T
here are no slider items to render -->
<?php
endif;
