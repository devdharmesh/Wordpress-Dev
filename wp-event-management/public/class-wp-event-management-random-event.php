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

class Wp_Event_Management_Random_Event extends WP_Widget
{
    public function __construct()
    {
        parent::__construct(
            'random_event_widget',
            __('Random Event', 'wp-event-management'), // Widget name and text domain
            array('description' => __('Display a random event', 'wp-event-management')) // Widget description and text domain
        );
    }

    public function form($instance)
    {
        $per_page = isset($instance['per_page']) ? esc_attr($instance['per_page']) : 5; ?>
        <p>
            <label for="<?php echo $this->get_field_id('per_page'); ?>"><?php _e('Events per Page:', 'wp-event-management'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('per_page'); ?>" name="<?php echo $this->get_field_name('per_page'); ?>" type="number" min="1" step="1" value="<?php echo $per_page; ?>" />
        </p>
    <?php }


    public function widget($args, $instance)
    {
        echo $args['before_widget'];
        echo $args['before_title'] . __('Random Event', 'wp-event-management') . $args['after_title'];
        $per_page = isset($instance['per_page']) ? absint($instance['per_page']) : 5;
        $random_events = $this->get_random_events($per_page);

        if ($random_events) {
            echo '<ul>';
            foreach ($random_events as $random_event) {
                $post_id = $random_event->ID;

                $field_date_time = get_post_meta($post_id, 'event-date-time', true);
                $dateTime = DateTime::createFromFormat('Y-m-d\TH:i', $field_date_time);
                $formattedDateTime = $dateTime->format('d-m-Y h:i A');

                $address = get_post_meta($post_id, 'event-address', true);
                $organizer = get_post_meta($post_id, 'event-organizer', true);

                echo '<li>';
                echo '<h4><a href="' . get_permalink($random_event->ID) . '">' . $random_event->post_title . '</a></h4>';
                echo '<p>Date & Time: ' . $formattedDateTime . '</p>';
                echo '<hr />';
                echo '</li>';
            }
            echo '</ul>';
        }

        echo $args['after_widget'];
    }


    public function get_random_events($per_page)
    {
        $args = array(
            'post_type' => 'event',
            'post_status' => 'publish',
            'posts_per_page' => $per_page,
            'orderby' => 'rand'
        );
        $query = new WP_Query($args);

        if ($query->have_posts()) {
            return $query->posts;
        }

        return array();
    }
}

function register_random_event_widget()
{
    register_widget('Wp_Event_Management_Random_Event');
}
add_action('widgets_init', 'register_random_event_widget');
