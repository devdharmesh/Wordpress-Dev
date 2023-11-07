<?php

/**
 * @package Dharmesh_Root
 */

if (!class_exists('Root_Unnecessary_Item')) {
    class Root_Unnecessary_Item
    {
        // Constructor function
        public function __construct()
        {
            // Add necessary hooks and filters
            add_action('init', array($this, 'remove_unnecessary_items'));
            add_action('wp_enqueue_scripts', array($this, 'remove_unnecessary_script'));
            add_action('wp_default_scripts', array($this, 'remove_jquery_migrate'));
        }

        // Remove unnecessary items
        public function remove_unnecessary_items()
        {
            // Remove emojis
            remove_action('wp_head', 'print_emoji_detection_script', 7);
            remove_action('admin_print_scripts', 'print_emoji_detection_script');
            remove_action('wp_print_styles', 'print_emoji_styles');
            remove_action('admin_print_styles', 'print_emoji_styles');

            // Remove RSD link
            remove_action('wp_head', 'rsd_link');

            // Remove WordPress version number
            remove_action('wp_head', 'wp_generator');
            remove_action('wp_head', 'wp_resource_hints', 2);

            // Remove WLW link
            remove_action('wp_head', 'wlwmanifest_link');

            // Remove feed link
            remove_action('wp_head', 'feed_links', 2);
            remove_action('wp_head', 'feed_links_extra', 3);

            // Remove rel link
            remove_action('wp_head', 'index_rel_link');
            remove_action('wp_head', 'parent_post_rel_link', 10, 0);
            remove_action('wp_head', 'start_post_rel_link', 10, 0);
            remove_action('wp_head', 'adjacent_posts_rel_link', 10, 0);

            // Remove short link
            remove_action('wp_head', 'wp_shortlink_wp_head', 10, 0);

            // Remove canonical link
            remove_action('wp_head', 'rel_canonical');
            remove_action('embed_head', 'rel_canonical');
            add_filter('wpseo_canonical', '__return_false');

            // Remove admin bar
            add_filter('show_admin_bar', '__return_false');


            remove_action('wp_body_open', 'wp_global_styles_render_svg_filters');
        }

        public function remove_unnecessary_script()
        {
            wp_dequeue_style('wp-block-library');
            wp_dequeue_style('wp-block-library-theme');
            wp_dequeue_style('classic-theme-styles');
            wp_dequeue_style('global-styles');
            wp_dequeue_style('global-styles');
            wp_dequeue_script('wp-embed');
        }

        public function remove_jquery_migrate($scripts)
        {
            if (!is_admin() && isset($scripts->registered['jquery'])) {
                $script = $scripts->registered['jquery'];
                if ($script->deps) {
                    $script->deps = array_diff($script->deps, array('jquery-migrate'));
                }
            }
        }
    }
}

new Root_Unnecessary_Item();