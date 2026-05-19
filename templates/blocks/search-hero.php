<?php

/**
 * Block: Search results hero.
 *
 * @package cordyceps
 * @author biogreen
 * @since 0.0.1
 */

$data = wp_parse_args($args, [
	'class' => '',
	'search_term' => '',
	'product_count' => 0,
	'post_count' => 0,
	'has_results' => false,
	'paged' => 1,
]);

$search_term = trim((string) $data['search_term']);
$paged = max(1, (int) $data['paged']);

$_class = 'search-hero';
$_class .= !empty($data['class']) ? ' ' . esc_attr($data['class']) : '';

$title = '' !== $search_term
	? sprintf(
		/* translators: %s: search keyword */
		__('Kết quả tìm kiếm: «%s»', 'cordyceps'),
		$search_term
	)
	: __('Tìm kiếm', 'cordyceps');

$summary = '';

if ('' === $search_term) {
	$summary = __('Nhập từ khóa vào ô tìm kiếm để bắt đầu.', 'cordyceps');
} elseif (!empty($data['has_results'])) {
	$product_count = max(0, (int) $data['product_count']);
	$post_count = max(0, (int) $data['post_count']);

	if ($product_count > 0 && $post_count > 0) {
		$summary = sprintf(
			/* translators: 1: product count, 2: post count */
			__('Tìm thấy %1$d sản phẩm và %2$d bài viết.', 'cordyceps'),
			$product_count,
			$post_count
		);
	} elseif ($product_count > 0) {
		$summary = sprintf(
			/* translators: %d: product count */
			__('Tìm thấy %d sản phẩm.', 'cordyceps'),
			$product_count
		);
	} else {
		$summary = sprintf(
			/* translators: %d: post count */
			__('Tìm thấy %d bài viết.', 'cordyceps'),
			$post_count
		);
	}

	if ($paged > 1) {
		$summary .= ' ' . sprintf(
			/* translators: %d: current page number */
			__('Trang %d.', 'cordyceps'),
			$paged
		);
	}
} else {
	$summary = sprintf(
		/* translators: %s: search keyword */
		__('Không có kết quả phù hợp với «%s». Thử từ khóa khác.', 'cordyceps'),
		$search_term
	);
}
?>

<section class="search-hero-section" aria-labelledby="search-hero-title">
	<div class="<?php echo esc_attr($_class); ?>">
		<div class="search-hero__scrim" aria-hidden="true"></div>

		<div class="search-hero__inner container">
			<h1 id="search-hero-title" class="search-hero__title">
				<?php echo esc_html($title); ?>
			</h1>

			<?php if ('' !== $summary) : ?>
				<p class="search-hero__summary">
					<?php echo esc_html($summary); ?>
				</p>
			<?php endif; ?>

			<form class="search-hero__form" method="get" action="<?php echo esc_url(home_url('/')); ?>" role="search">
				<label class="search-hero__label" for="search-hero-input">
					<?php esc_html_e('Tìm lại', 'cordyceps'); ?>
				</label>
				<div class="search-hero__form-row d-flex flex-wrap align-items-stretch gap-2">
					<input
						type="search"
						name="s"
						id="search-hero-input"
						class="search-hero__input flex-fill"
						value="<?php echo esc_attr($search_term); ?>"
						placeholder="<?php echo esc_attr__('Nhập từ khóa…', 'cordyceps'); ?>"
						autocomplete="off"
						required
					/>
					<button type="submit" class="search-hero__submit">
						<?php esc_html_e('Tìm kiếm', 'cordyceps'); ?>
					</button>
				</div>
			</form>
		</div>
	</div>
</section>
