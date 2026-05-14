<?php
/**
 * Main Functions
 *
 * @package cordyceps
 * @author biogreen
 * @since 0.0.1
 */

require_once get_theme_file_path('inc/class-theme-init.php');


/**
 * Track post views
 */
function cordyceps_track_post_views() {
    if (!is_single()) {
        return;
    }

    $post_id = get_the_ID();
    $views = (int) get_post_meta($post_id, 'post_views', true);
    update_post_meta($post_id, 'post_views', $views + 1);
}
add_action('wp', 'cordyceps_track_post_views');

/**
 * Remove GeneratePress default sidebars
 */
function cordyceps_remove_generatepress_sidebars() {
    remove_action('widgets_init', 'generate_widgets_init');
}
add_action('after_setup_theme', 'cordyceps_remove_generatepress_sidebars', 0);

