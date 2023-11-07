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


class Wp_Event_Management_Meta_Box
{
    private $screens = array('event');
    private $fields = array(
        array(
            'label' => 'Date and Time',
            'id' => 'event-date-time',
            'type' => 'datetime-local',
        ),
        array(
            'label' => 'Organizer',
            'id' => 'event-organizer',
            'type' => 'text',
        ),
        array(
            'label' => 'Type',
            'id' => 'event-type',
            'type' => 'radio',
            'default' => 'physical',
            'options' => array(
                'physical' => 'Physical',
                'virtual' => 'Virtual',
            ),
        ),
        array(
            'label' => 'Address',
            'id' => 'event-address',
            'type' => 'text',
        ),
        array(
            'label' => 'Link',
            'id' => 'event-link',
            'type' => 'url',
        ),
    );
    /**
     * Constructor for the class.
     *
     * This function adds actions to add custom meta boxes and save custom meta data.
     *
     * @return void
     */
    public function __construct()
    {
        // Add actions to add custom meta boxes and save custom meta data.
        add_action('add_meta_boxes', array($this, 'add_meta_boxes'));
        add_action('save_post', array($this, 'save_fields'));
    }

    /**
     * Adds custom meta boxes to the event post type.
     *
     * @throws Some_Exception_Class description of exception
     */
    public function add_meta_boxes()
    {
        foreach ($this->screens as $s) {
            add_meta_box(
                'EventOptions',
                __('Event Options', 'wp-event-management'),
                array($this, 'meta_box_callback'),
                $s,
                'advanced',
                'high'
            );
        }
    }

    public function meta_box_callback($post)
    {
        wp_nonce_field('EventOptions_data', 'EventOptions_nonce');
        $this->field_generator($post);
    }

    public function field_generator($post)
    {
        $output = '';
        foreach ($this->fields as $field) {
            $label = '<label for="' . $field['id'] . '">' . $field['label'] . '</label>';
            $meta_value = get_post_meta($post->ID, $field['id'], true);
            if (empty($meta_value)) {
                if (isset($field['default'])) {
                    $meta_value = $field['default'];
                }
            }
            switch ($field['type']) {
                case 'radio':
                    $input = '<fieldset>';
                    $input .= '<legend class="screen-reader-text">' . $field['label'] . '</legend>';
                    $i = 0;
                    foreach ($field['options'] as $key => $value) {
                        $field_value = !is_numeric($key) ? $key : $value;
                        $input .= sprintf(
                            '<label><input %s id="%s" name="%s" type="radio" value="%s"> %s</label>%s',
                            $meta_value === $field_value ? 'checked' : '',
                            $field['id'],
                            $field['id'],
                            $field_value,
                            $value,
                            $i < count($field['options']) - 1 ? '<br>' : ''
                        );
                        $i++;
                    }
                    $input .= '</fieldset>';
                    break;
                default:
                    $input = sprintf(
                        '<input %s id="%s" name="%s" type="%s" value="%s">',
                        $field['type'] !== 'color' ? 'style="width: 100%"' : '',
                        $field['id'],
                        $field['id'],
                        $field['type'],
                        $meta_value
                    );
            }
            $output .= $this->format_rows($label, $input);
        }
        echo $output;
    }

    public function format_rows($label, $input)
    {
        return '<div class="event-form-group"><div style="margin-top: 10px;"><strong>' . $label . '</strong></div><div>' . $input . '</div></div>';
    }


    /**
     * Saves custom meta data for a post.
     *
     * @param int $post_id The ID of the post being saved.
     */
    public function save_fields($post_id)
    {
        if (!isset($_POST['EventOptions_nonce'])) {
            return $post_id;
        }
        $nonce = $_POST['EventOptions_nonce'];
        if (!wp_verify_nonce($nonce, 'EventOptions_data')) {
            return $post_id;
        }
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return $post_id;
        }
        foreach ($this->fields as $field) {
            if (isset($_POST[$field['id']])) {
                $_POST[$field['id']] = sanitize_text_field($_POST[$field['id']]);
                update_post_meta($post_id, $field['id'], sanitize_text_field($_POST[$field['id']]));
            }
        }
    }
}
