<?php

/**
 * @package Rocket_Homepage
 */

if (!class_exists('Root_Theme_Enqueue')) {
    class Root_Theme_Enqueue
    {
        public $minify = true;
        public $minify_class;

        public function __construct()
        {
            // Add necessary hooks and filters
            $this->minify_class = new Root_Minify_Files();
            add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));
            add_action('wp_enqueue_scripts', array($this, 'enqueue_styles'));
            add_filter('script_loader_src', array($this, 'remove_query_strings'), 10, 1);
            add_filter('style_loader_src', array($this, 'remove_query_strings'), 10, 1);
        }

        // Enqueue scripts
        public function enqueue_scripts()
        {
            wp_enqueue_script('jquery');

            $minify_js_file = [
                THEME_DIR . 'assets/js/script.js'
            ];
            $this->minify_class->minify_theme_files($minify_js_file);
            wp_enqueue_script('theme', THEME_URI . 'assets/js/script.min.js', [], THEME_VERSION, true);
        }

        // Enqueue styles
        public function enqueue_styles()
        {
            $minify_css_file = [
                THEME_DIR . 'assets/css/style.css'
            ];
            $this->minify_class->minify_theme_files($minify_css_file);
            wp_enqueue_style('theme', THEME_URI . 'assets/css/style.min.css', [], THEME_VERSION);
            wp_localize_script( 'theme', 'themeObject', array(
                'ajaxUrl' => admin_url( 'admin-ajax.php' ),
                'nonce'   => wp_create_nonce( 'Dharmesh_Root' ),
                'homeUrl' => home_url('/')
            ));
            
        }

        public function remove_query_strings($src)
        {
            $parts = explode('?', $src);
            return $parts[0];
        }
    }
}

new Root_Theme_Enqueue();
