<?php

/**
 * Plugin Name: Plugin Control Center
 * Description: Custom REST API endpoints for plugin management.
 * Version: 1.0
 * Author: Dharmesh Lakum
 */

class PluginControlCenter
{
    public function __construct()
    {
        add_action('rest_api_init', [$this, 'register_routes']);
    }

    /**
     * Registers REST routes for plugin control center.
     *
     * @return void
     */
    public function register_routes()
    {
        // List plugins route
        register_rest_route('plugin-control-center/v2', '/list-plugins', [
            'methods' => 'GET',
            'callback' => [$this, 'listPlugins'],
        ]);

        // Deactivate plugin route
        register_rest_route('plugin-control-center/v2', '/deactivate-plugin', [
            'methods' => 'GET',
            'callback' => [$this, 'deactivatePlugin'],
            'args' => [
                'plugin_name' => [
                    'required' => true
                ]
            ]
        ]);

        // Activate plugin route
        register_rest_route('plugin-control-center/v2', '/activate-plugin', [
            'methods' => 'GET',
            'callback' => [$this, 'activatePlugin'],
            'args' => [
                'plugin_name' => [
                    'required' => true
                ]
            ]
        ]);
    }

    /**
     * This function lists all the plugins installed in the WordPress system.
     *
     * @return array List of plugins
     */
    public function listPlugins()
    {
        // Get all plugins installed in the system
        $plugins = get_plugins();

        // Return the list of plugins
        return $plugins;
    }

    /**
     * Deactivates a WordPress plugin.
     *
     * @param WP_REST_Request $request The REST request object.
     * @return string The status message.
     */
    public function deactivatePlugin(WP_REST_Request $request)
    {
        // Get the plugin name from the request
        $plugin_name = $request->get_param('plugin_name');

        // Deactivate the plugin
        deactivate_plugins($plugin_name, true);

        // Return the status message
        return 'Plugin deactivated: ' . $plugin_name;
    }

    /**
     * Activates a plugin via a REST request.
     *
     * @param WP_REST_Request $request The REST request object.
     * @return string The activation status message.
     */
    public function activatePlugin(WP_REST_Request $request)
    {
        // Get the plugin name from the request
        $plugin_name = $request->get_param('plugin_name');

        // Activate the plugin
        activate_plugin($plugin_name);

        // Return the activation status message
        return 'Plugin activated: ' . $plugin_name;
    }
}

// Instantiate the plugin class
new PluginControlCenter();
