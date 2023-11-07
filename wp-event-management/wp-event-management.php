<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://devdharmesh.github.io/Dharmesh-Lakum/
 * @since             1.0.0
 * @package           Wp_Event_Management
 *
 * @wordpress-plugin
 * Plugin Name:       Event Management
 * Plugin URI:        #
 * Description:       Simplify event management on your WordPress website with our event management plugin. Easily create and manage events.
 * Version:           1.0.0
 * Author:            Dharmesh Lakum
 * Author URI:        https://devdharmesh.github.io/Dharmesh-Lakum//
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       wp-event-management
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'WP_EVENT_MANAGEMENT_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-wp-event-management-activator.php
 */
function activate_wp_event_management() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-wp-event-management-activator.php';
	Wp_Event_Management_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-wp-event-management-deactivator.php
 */
function deactivate_wp_event_management() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-wp-event-management-deactivator.php';
	Wp_Event_Management_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_wp_event_management' );
register_deactivation_hook( __FILE__, 'deactivate_wp_event_management' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-wp-event-management.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_wp_event_management() {

	$plugin = new Wp_Event_Management();
	$plugin->run();

}
run_wp_event_management();
