<?php

/**
 * Fired during plugin activation
 *
 * @link       https://devdharmesh.github.io/Dharmesh-Lakum/
 * @since      1.0.0
 *
 * @package    Wp_Event_Management
 * @subpackage Wp_Event_Management/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Wp_Event_Management
 * @subpackage Wp_Event_Management/includes
 * @author     Dharmesh Lakum <dharmeshlakum0000@gmail.com>
 */
class Wp_Event_Management_Activator
{

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate()
	{

		// Define the page content with shortcodes
		$event_form_content = '[event_form]';
		$event_listing_content = '[event_listing]';

		$event_form_page = array(
			'post_title'    => 'Event Form',
			'post_content'  => $event_form_content,
			'post_status'   => 'publish',
			'post_type'     => 'page',
		);
		wp_insert_post($event_form_page);

		$event_listing_page = array(
			'post_title'   	=> 'Event Listing',
			'post_content'  => $event_listing_content,
			'post_status'   => 'publish',
			'post_type'     => 'page',
		);
		wp_insert_post($event_listing_page);


		flush_rewrite_rules();
	}
}
