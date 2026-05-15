<?php

/**
 * Block: Process section
 *
 * @package cordyceps
 * @author biogreen
 * @since 0.0.1
 */

$data = wp_parse_args($args, [
	'class' => '',
	'title' => '',
	'subtitle' => '',
	'description' => '',
	'process_items' => [],
	'highlighted_content' => '',
	'background_highlighted' => '',
]);
$_class = 'process-section';
$_class .= !empty($data['class']) ? esc_attr(' ' . $data['class']) : '';
?>

<section class="process-section">
	<div class="<?php echo esc_attr($_class); ?> py-3 text-white position-relative">
		<div class="process-section__content text-center">
			<?php if (!empty($data['subtitle'])): ?>
				<h5 class="process-section__subtitle text-uppercase"><?php echo esc_html($data['subtitle']); ?></h5>
			<?php endif; ?>
			<?php if (!empty($data['title'])): ?>
				<h1 class="process-section__title text-uppercase"><?php echo esc_html($data['title']); ?></h1>
			<?php endif; ?>
			<?php if (!empty($data['description'])): ?>
				<h5 class="process-section__description"><?php echo wp_kses_post($data['description']); ?></h5>
			<?php endif; ?>
		</div>

		<div class="process-section__process-items-container">
			<div class="process-section__process-items d-flex">
				<?php if (!empty($data['process_items'])): ?>
					<?php foreach ($data['process_items'] as $item): ?>
						<div class="process-section__process-item justify-content-between m-1">

							<div class="process-section__item-top d-flex align-items-center justify-content-center gap-1">
								<h4 class="process-section__process-item-number fw-bold d-flex align-items-center justify-content-center"><?php echo esc_html($item['number']); ?></h4>
								<h4 class="process-section__process-item-title fw-bold"><?php echo esc_html($item['title']); ?></h4>
							</div>

							<div class="process-section__item-image">
								<?php
								get_template_part('templates/core-blocks/image', null, [
									'image_id' => $item['image'],
									'image_size' => 'full',
									'lazyload' => true,
									'class' => 'process-section__item-image',
								]);
								?>
							</div>

							<div class="process-section__item-bottom mt-1">
								<h5 class="process-section__item-description"><?php echo wp_kses_post($item['description']); ?></h5>
							</div>

						</div>
					<?php endforeach; ?>
				<?php endif; ?>
			</div>
		</div>

		<?php if (!empty($data['highlighted_content']) || !empty($data['background_highlighted'])): ?>
			<div class="process-section__highlight w-100 mt-1">
				<div class="process-section__highlight-frame position-relative w-100 overflow-hidden">
					<?php if (!empty($data['background_highlighted'])): ?>
						<div class="process-section__highlight-media" aria-hidden="true">
							<?php
							get_template_part('templates/core-blocks/image', null, [
								'image_id' => $data['background_highlighted'],
								'image_size' => 'full',
								'lazyload' => true,
								'class' => 'process-section__highlight-figure',
							]);
							?>
						</div>
					<?php endif; ?>
					<div class="process-section__highlight-scrim" aria-hidden="true"></div>
					<div class="process-section__highlight-inner position-relative w-100">
						<?php if (!empty($data['highlighted_content'])): ?>
							<h2 class="process-section__highlight-title mb-0 fst-italic text-start fw-normal">
								<span class="process-section__highlight-mark" aria-hidden="true">“</span>
								<span class="process-section__highlight-text"><?php echo esc_html($data['highlighted_content']); ?></span>
							</h2>
						<?php endif; ?>
					</div>
				</div>
			</div>
		<?php endif; ?>
	</div>
</section>
