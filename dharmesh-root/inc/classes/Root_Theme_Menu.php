<?php

/**
 * @package Dharmesh_Root
 */
if (!class_exists('Root_Theme_Menu')) {
    class Root_Theme_Menu
    {
        // Constructor function
        public function __construct()
        {
            // Add necessary hooks and filters
            add_action('init', array($this, 'register_menu'));
        }

        // Register menu
        public function register_menu()
        {
            register_nav_menu('primary', esc_html__('Primary Menu', 'my-theme'));
        }
    }
}

new Root_Theme_Menu();
