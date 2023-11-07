<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://devdharmesh.github.io/Dharmesh-Lakum/
 * @since      1.0.0
 *
 * @package    Wp_Event_Management
 * @subpackage Wp_Event_Management/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Wp_Event_Management
 * @subpackage Wp_Event_Management/public
 * @author     Dharmesh Lakum <dharmeshlakum0000@gmail.com>
 */
class Wp_Event_Management_Public
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
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct($plugin_name, $version)
	{

		$this->plugin_name = $plugin_name;
		$this->version = $version;

		$this->load_dependencies();

		add_shortcode('event_listing', [$this, 'event_listing']);
		add_shortcode('event_form', [$this, 'event_form']);

		add_action('wp_ajax_wp_event_management_save_event', [$this, 'save_event']);
		add_action('wp_ajax_nopriv_wp_event_management_save_event', [$this, 'save_event']);
	}

	/**
	 * Load necessary dependencies for the plugin.
	 */
	private function load_dependencies()
	{
		// Define the list of files to be loaded.
		$files = [
			'class-wp-event-management-random-event.php'
		];

		// Loop through each file and require it.
		foreach ($files as $file) {
			require_once plugin_dir_path(dirname(__FILE__)) . "public/$file";
		}
	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
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

		wp_enqueue_style('datetimepicker', plugin_dir_url(__FILE__) . 'css/jquery.datetimepicker.min.css', array(), '2.5.19', false);
		wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/wp-event-management-public.css', array('datetimepicker'), $this->version, 'all');
	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
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

		wp_enqueue_script('datetimepicker', plugin_dir_url(__FILE__) . 'js/jquery.datetimepicker.full.min.js', array('jquery'), '2.5.19', false);
		wp_enqueue_script('validator', plugin_dir_url(__FILE__) . 'js/validator.min.js', array(), '13.11.0', false);
		wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/wp-event-management-public.js', array('validator', 'datetimepicker'), $this->version, false);
		wp_localize_script($this->plugin_name, 'wp_event_management', [
			'ajax_url' => admin_url('admin-ajax.php'),
			'nonce' => wp_create_nonce('wp-event-management-nonce'),
		]);
	}

	public function event_listing()
	{
		ob_start();
		include plugin_dir_path(__FILE__) . 'partials/event-listing.php';
		return ob_get_clean();
	}

	public function event_form()
	{
		ob_start();
		include plugin_dir_path(__FILE__) . 'partials/event-form.php';
		return ob_get_clean();
	}

	public function save_event()
	{
		
		$status = 0;
		$message = "Something went wrong, please try again";
		echo $status ? wp_send_json_success($message) : wp_send_json_error($message);
		wp_die();
		if (wp_verify_nonce($_POST['nonce'], 'wp-event-management-nonce')) {
			$name = sanitize_text_field($_POST['name']);
			$description = sanitize_text_field($_POST['description']);
			$date_time = sanitize_text_field($_POST['date_time']);
			$organizer = sanitize_text_field($_POST['organizer']);
			$type = sanitize_text_field($_POST['type']);
			$address = sanitize_text_field($_POST['address']);
			$link = sanitize_text_field($_POST['link']);
			$city = sanitize_text_field($_POST['city']);

			$from_date = trim(preg_replace('/\s*\([^)]*\)/', '', $date_time));
			$dt = DateTime::createFromFormat('D M d Y H:i:s O', $from_date);
			$formattedDate = $dt->format('Y-m-d\TH:i');

			$file = $_FILES['file'];

			// Create Event Post
			$post_id = wp_insert_post(array(
				'post_title' => $name,
				'post_content' => $description,
				'post_type' => 'event',
				'post_status' => 'pending-event',
			));

			if (!is_wp_error($post_id)) {

				// Set Meta Fields
				update_post_meta($post_id, 'event-date-time', $formattedDate);
				update_post_meta($post_id, 'event-organizer', $organizer);
				update_post_meta($post_id, 'event-type', $type);
				update_post_meta($post_id, 'event-address', $address);
				update_post_meta($post_id, 'event-link', $link);

				// Create/Assign Terms
				$city_term = term_exists($city, 'city');
				if (!$city_term) {
					$city_term = wp_insert_term($city, 'city');
				}
				wp_set_post_terms($post_id, $city_term['term_id'], 'city', true);

				// Upload and Set Featured Image
				if ($file['error'] == 0) {
					require_once(ABSPATH . 'wp-admin/includes/image.php');
					require_once(ABSPATH . 'wp-admin/includes/file.php');
					require_once(ABSPATH . 'wp-admin/includes/media.php');

					$attachment_id = media_handle_upload('file', $post_id);
					if (!is_wp_error($attachment_id)) {
						set_post_thumbnail($post_id, $attachment_id);
					}
				}

				$status = 1;
				$message = "Event created successfully";
			} else {
				$message = $post_id->get_error_message();
			}
		} else {
			$message = "Invalid nonce";
		}

		echo $status ? wp_send_json_success($message) : wp_send_json_error($message);
		wp_die();
	}
	
}
