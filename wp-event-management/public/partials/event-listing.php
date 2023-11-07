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

$events_url = home_url('wp-json/wp/v2/events');
$response = wp_remote_get($events_url);
$events = json_decode(wp_remote_retrieve_body($response));

if ($events) : ?>
    <div class="event-listing">
        <?php foreach ($events as $event) : ?>
            <div class="event-card">
                <h2><?php echo $event->title ?></h2>
                <ul>
                    <li>Date & Time: <?php echo $event->date ?></li>
                    <li>Type: <?php echo $event->type ?></li>
                    <?php if ($event->type == 'physical') : ?>
                        <li>Address: <?php echo $event->address ?></li>
                    <?php else : ?>
                        <li>Link: <a href="<?php echo $event->link ?>" target="_blank"><?php echo $event->link ?></a></li>
                    <?php endif; ?>
                    <li>Organizer: <?php echo $event->organizer; ?></li>
                </ul>
            </div>
        <?php endforeach; ?>
    </div>
<?php
else :
    echo 'No events found';
endif;
