<?php
/**
 * Fallback archive template (valid markup + Loop).
 *
 * @package cordyceps
 * @author biogreen
 * @since 0.0.1
 */

get_header();
?>

<main id="main" class="site-main w-100 archive">
	<div class="container">
		<div class="row">
			<div class="col-12">
				<?php do_action('generate_before_main_content'); ?>

				<?php if (have_posts()) : ?>
					<div class="archive-posts archive-posts-stack">
						<?php while (have_posts()) : ?>
							<?php the_post(); ?>
							<article <?php post_class('archive-post'); ?>>
								<h2 class="entry-title archive-post__title mb-3 h4 fw-bold">
									<a class="archive-post__link text-decoration-none" href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
								</h2>
							</article>
						<?php endwhile; ?>
					</div>
					<?php generate_content_nav('nav-below'); ?>
				<?php else : ?>
					<p class="archive-empty" role="status"><?php esc_html_e('Nothing found in this archive.', 'cordyceps'); ?></p>
				<?php endif; ?>

				<?php do_action('generate_after_main_content'); ?>
			</div>
		</div>
	</div>
</main>

<?php
get_footer();
