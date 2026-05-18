<?php

/**
 * Featured product: category pill tabs.
 *
 * @package cordyceps
 * @author biogreen
 * @since 0.0.1
 *
 * @var array $args {
 *     @type WP_Term[] $categories
 *     @type int       $active_id 0 = all products tab.
 * }
 */

$args = wp_parse_args($args, [
	'categories' => [],
	'active_id' => 0,
]);

$categories = is_array($args['categories']) ? $args['categories'] : [];
$active_id = absint($args['active_id']);
$is_all_active = 0 === $active_id;
?>

<nav class="fp__tabs" aria-label="<?php esc_attr_e('Danh mục sản phẩm', 'cordyceps'); ?>">
	<ul class="fp__tabs-list d-flex flex-wrap justify-content-center align-items-center list-unstyled m-0 p-0">
		<li class="fp__tabs-item">
			<button
				type="button"
				class="fp__tab<?php echo $is_all_active ? ' fp__tab--active' : ''; ?>"
				data-fp-category="0"
				aria-pressed="<?php echo $is_all_active ? 'true' : 'false'; ?>"
			>
				<?php esc_html_e('Tất cả sản phẩm', 'cordyceps'); ?>
			</button>
		</li>
		<?php foreach ($categories as $term) : ?>
			<?php
			if (!$term instanceof WP_Term) {
				continue;
			}

			$is_active = $active_id > 0 && $active_id === (int) $term->term_id;
			$tab_class = 'fp__tab';
			$tab_class .= $is_active ? ' fp__tab--active' : '';
			?>
			<li class="fp__tabs-item">
				<button
					type="button"
					class="<?php echo esc_attr($tab_class); ?>"
					data-fp-category="<?php echo esc_attr((string) $term->term_id); ?>"
					aria-pressed="<?php echo $is_active ? 'true' : 'false'; ?>"
				>
					<?php echo esc_html($term->name); ?>
				</button>
			</li>
		<?php endforeach; ?>
	</ul>
</nav>
