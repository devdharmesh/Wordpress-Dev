<?php

/**
 * Theme Setup
 * 
 * @package Dharmesh_Root
 */

if (!class_exists('Root_Theme_Setup')) {
    class Root_Theme_Setup
    {

        // Constructor function
        function __construct()
        {
            add_action('init', array($this, 'init'));
            add_action('after_setup_theme', array($this, 'setup_theme'));
            add_action('wp_head', array($this, 'wp_head'));
        }

        public function init()
        {
        }
        public function setup_theme()
        {
            // Add support for automatic feed links
            add_theme_support('automatic-feed-links');

            // Add support for title tag
            add_theme_support('title-tag');

            // Add support for post thumbnails
            add_theme_support('post-thumbnails');

            // Add support for HTML5 markup
            add_theme_support('html5', array('search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script'));

            // Add support for core custom logo.
            add_theme_support('custom-logo');
        }

        public function wp_head()
        {
            if (is_singular() && pings_open()) {
                printf('<link rel="pingback" href="%s">', esc_url(get_bloginfo('pingback_url')));
            }
        }
    }
}
