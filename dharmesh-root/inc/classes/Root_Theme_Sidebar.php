<?php

/**
 * @package Dharmesh_Root
 */
if (!class_exists('Root_Theme_Sidebar')) {
    class Root_Theme_Sidebar
    {
        // Constructor function
        public function __construct()
        {
            // Add necessary hooks and filters
            add_action('widgets_init', array($this, 'register_sidebar'));
        }

        // Register sidebar
        public function register_sidebar()
        {
            register_sidebar(array(
                'name'          => esc_html__('Main Sidebar', 'dharmesh-root'),
                'id'            => 'main-sidebar',
                'description'   => esc_html__('Add widgets here to appear in the main sidebar.', 'dharmesh-root'),
                'before_widget' => '<div id="%1$s" class="widget %2$s">',
                'after_widget'  => '</div>',
                'before_title'  => '<h2 class="widget-title">',
                'after_title'   => '</h2>',
            ));
        }
    }
}

new Root_Theme_Sidebar();
