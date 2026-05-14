<?php
/**
 * Container Cordyceps
 *
 * @package cordyceps
 * @author biogreen
 * @since 0.0.1
 */

namespace Cordyceps;

class Container_Layout
{
    public function __construct()
    {
        add_filter('generate_inside_header_class', [$this, 'inside_classes'], 100);
        add_filter('generate_inside_navigation_class', [$this, 'inside_classes'], 100);
        add_filter('generate_inside_footer_class', [$this, 'inside_classes'], 100);

        add_action('generate_inside_site_container', [$this, 'content_container_class_open'], 1);
        add_action('generate_before_footer', [$this, 'content_container_class_close'], 1);

        add_filter('generate_page_class', [$this, 'content_classes'], 1000);
        add_filter('generate_page_class', [$this, 'content_container_classes'], 110);
    }

    public function inside_classes($classes)
    {
        $has_container = false;

        foreach ($classes as $index => $class) {
            if (in_array($class, ['grid-container', 'footer-widgets-container', 'inside-navigation'])) {
                unset($classes[$index]);
            }

            if (!$has_container && $class === 'container') {
                $has_container = true;
            }
        }

        if (!$has_container) {
            $classes[] = 'container';
        }

        return $classes;
    }

    public function content_container_class_open()
    {
        if (is_singular())
            return;

        echo '<div class="container">';
    }

    public function content_container_class_close()
    {
        if (is_singular())
            return;

        echo '</div><!-- End .container -->';
    }

    public function content_classes($classes)
    {
        if (!is_singular())
            return $classes;

        $content_area_type = get_post_meta(get_the_ID(), '_generate-full-width-content', true);

        if (empty($content_area_type)) {
            $classes[] = 'container';
        } elseif ('true' === $content_area_type) {
            foreach ($classes as $index => $class) {
                if ('container' === $class) {
                    unset($classes[$index]);
                }
            }
        }

        return $classes;
    }

    public function content_container_classes($classes)
    {
        $is_site_container = in_array('site', $classes);

        if (!$is_site_container) {
            return $classes;
        }

        foreach ($classes as $index => $class) {
            if (in_array($class, ['grid-container'])) {
                unset($classes[$index]);
            }

            if (!is_page() && 'container' === $class) {
                unset($classes[$index]);
            }
        }

        return $classes;
    }
}

new Container_Layout();
