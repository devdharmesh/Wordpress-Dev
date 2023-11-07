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

class Wp_Event_Management_REST_API
{
    function __construct()
    {
        add_action('rest_api_init', array($this, 'register_rest_api'));
    }

    public function register_rest_api()
    {
        register_rest_route('wp/v2', '/events', array(
            'methods' => 'GET',
            'callback' => array($this, 'get_events'),
            'permission_callback' => '__return_true',
        ));
    }

    public function get_events()
    {
        $args = array(
            'post_type' => 'event',
            'posts_per_page' => -1,
        );

        $events = get_posts($args);

        if (empty($events)) {
            return rest_ensure_response([]);
        }

        $formatted_events = array_map(function ($event) {
            $post_id = $event->ID;
            $field_date_time = get_post_meta($post_id, 'event-date-time', true);
            $dateTime = DateTime::createFromFormat('Y-m-d\TH:i', $field_date_time);
            $formattedDateTime = $dateTime->format('d-m-Y h:i A');

            $address = get_post_meta($post_id, 'event-address', true);
            $organizer = get_post_meta($post_id, 'event-organizer', true);
            $type = get_post_meta($post_id, 'event-type', true);
            $link = get_post_meta($post_id, 'event-link', true);

            return array(
                'title' => $event->post_title,
                'content' => $event->post_content,
                'date' => $formattedDateTime,
                'type' => $type,
                'address' => $address,
                'link' => $link,
                'organizer' => $organizer
            );
        }, $events);

        return rest_ensure_response($formatted_events);
    }
}
