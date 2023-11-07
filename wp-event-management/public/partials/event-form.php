<?php

/**
 * Provide a public-facing view for the plugin
 *
 * This file is used to markup the public-facing aspects of the plugin.
 *
 * @link       https://devdharmesh.github.io/Dharmesh-Lakum/
 * @since      1.0.0
 *
 * @package    Wp_Event_Management
 * @subpackage Wp_Event_Management/public/partials
 */

 $city = ['Ahmedabad', 'Surat', 'Vadodara', 'Mumbai', 'Pune', 'Nagpur', 'Jaipur', 'Jodhpur', 'Udaipur', 'Indore', 'Bhopal', 'Jabalpur'];
?>

<h2>Add New Event</h2>
<div class="event-form">
    <form action="#" method="post" class="event-form-wrapper">
        <div class="event-group">
            <label for="event-name"><?php _e('Event Name', 'wp-event-management'); ?></label>
            <input type="text" id="event-name" name="event-name" class="event-form-input">
        </div>

        <div class="event-group">
            <label for="event-description"><?php _e('Event Description', 'wp-event-management'); ?></label>
            <textarea id="event-description" name="event-description" class="event-form-input"></textarea>
        </div>

        <div class="event-group">
            <label for="event-date-time"><?php _e('Event Date & Time', 'wp-event-management'); ?></label>
            <input type="text" id="event-date-time" name="event-date-time" class="event-form-input" autocomplete="false">
        </div>

        <div class="event-group">
            <label for="event-organizer-name"><?php _e('Event Organizer Name', 'wp-event-management'); ?></label>
            <input type="text" id="event-organizer-name" name="event-organizer-name" class="event-form-input"></label>
        </div>

        <div class="event-group">
            <label><?php _e('Event Type', 'wp-event-management'); ?></label>
            <div class="event-form-check">
                <input class="event-check-input" type="radio" name="event-type" id="event-physical" value="physical" checked>
                <label for="event-physical" class="event-check-label"><?php _e('Physical', 'wp-event-management'); ?></label>
            </div>

            <div class="event-form-check">
                <input class="event-check-input" type="radio" name="event-type" id="event-virtual" value="virtual">
                <label for="event-virtual" class="event-check-label"><?php _e('Virtual', 'wp-event-management'); ?></label>
            </div>
        </div>

        <div class="event-group">
            <label for="event-address"><?php _e('Event Address', 'wp-event-management'); ?></label>
            <input type="text" id="event-address" name="event-address" class="event-form-input event-type-physical event-type-relation">
        </div>

        <div class="event-group" style="display: none;">
            <label for="event-link"><?php _e('Event Link', 'wp-event-management'); ?></label>
            <input type="text" id="event-link" name="event-link" class="event-form-input event-type-virtual event-type-relation">
        </div>

        <div class="event-group">
            <label for="event-city"><?php _e('Event City', 'wp-event-management'); ?></label>
            <select name="event-city" id="event-city" class="event-form-select">
                <option value="">Select City</option>
                <?php foreach ($city as $value) : ?>
                    <option value="<?php echo $value; ?>"><?php echo $value; ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="event-group">
            <label for="event-image"><?php _e('Event Image', 'wp-event-management'); ?></label>
            <input type="file" id="event-image" name="event-image" accept=".jpg, .jpeg, .png" class="event-form-input">
            <small><?php _e('Image should be in .jpg, .jpeg, .png format and size should be less than 2MB', 'wp-event-management'); ?></small>
        </div>

        <div>
            <div class="event-form-message"></div>
            <input type="submit" class="event-form-submit" value="Add Event">
        </div>
    </form>
</div>