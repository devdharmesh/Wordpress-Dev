<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://devdharmesh.github.io/Dharmesh-Lakum/
 * @since      1.0.0
 *
 * @package    Wp_Event_Management
 * @subpackage Wp_Event_Management/admin
 */

class Wp_Event_Management_Capabilities
{
    /**
     * Class constructor.
     * Registers capabilities on 'init' action.
     */
    function __construct()
    {
        // Register capabilities on 'init' action
        add_action('init', array($this, 'register_capabilities'));
    }

    /**
     * Registers the 'event_manager' role with appropriate capabilities.
     *
     * @return void
     */
    public function register_capabilities()
    {
        // Check if 'event_manager' role already exists
        $event_manager = get_role('event_manager');

        // If role exists, return
        if (!$event_manager) {
            // Register 'event_manager' role
            add_role('event_manager', 'Event Manager');
        }

        // Add capabilities to 'event_manager' role
        $event_manager->add_cap('create_events');
        $event_manager->add_cap('edit_events');
        $event_manager->add_cap('read_events');
        $event_manager->add_cap('delete_events');
        $event_manager->add_cap('edit_others_events');
        $event_manager->add_cap('publish_events');
        $event_manager->add_cap('read_private_events');

        // Allow admins to manage event_manager posts
        $admin_role = get_role('administrator');
        $admin_role->add_cap('edit_events');
        $admin_role->add_cap('edit_others_events');
        $admin_role->add_cap('publish_events');
        $admin_role->add_cap('read_private_events');
        $admin_role->add_cap('delete_events');

    }
}
