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

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Wp_Event_Management
 * @subpackage Wp_Event_Management/admin
 * @author     Dharmesh Lakum <dharmeshlakum0000@gmail.com>
 */
class Wp_Event_Management_Admin
{

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct($plugin_name, $version)
	{
		$this->plugin_name = $plugin_name;
		$this->version = $version;

		$this->load_dependencies();
	}

	/**
	 * Load necessary dependencies for the plugin.
	 */
	private function load_dependencies()
	{
		// Define the list of files to be loaded.
		$files = [
			'class-wp-event-management-meta-box.php',
			'class-wp-event-management-post-status.php',
			'class-wp-event-management-filter.php',
			'class-wp-event-management-capabilities.php',
			'class-wp-event-management-rest-api.php'
		];

		// Loop through each file and require it.
		foreach ($files as $file) {
			require_once plugin_dir_path(dirname(__FILE__)) . "admin/$file";
		}
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles()
	{

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Wp_Event_Management_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Wp_Event_Management_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/wp-event-management-admin.css', array(), $this->version, 'all');
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts()
	{

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Wp_Event_Management_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Wp_Event_Management_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/wp-event-management-admin.js', array('jquery'), $this->version, false);
	}

	public function init()
	{
	}
}
