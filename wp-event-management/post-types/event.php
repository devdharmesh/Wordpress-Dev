<?php

/**
 * Registers the `event` post type.
 */
function event_init() {
	$capabilities = array(
        'edit_post'          => 'edit_events',
        'read_post'          => 'read_events',
        'delete_post'        => 'delete_events',
        'edit_posts'         => 'edit_events',
        'edit_others_posts'  => 'edit_others_events',
        'publish_posts'      => 'publish_events',
        'read_private_posts' => 'read_private_events',
    );

	register_post_type(
		'event',
		[
			'labels'                => [
				'name'                  => __( 'Events', 'wp-event-management' ),
				'singular_name'         => __( 'Event', 'wp-event-management' ),
				'all_items'             => __( 'All Events', 'wp-event-management' ),
				'archives'              => __( 'Event Archives', 'wp-event-management' ),
				'attributes'            => __( 'Event Attributes', 'wp-event-management' ),
				'insert_into_item'      => __( 'Insert into Event', 'wp-event-management' ),
				'uploaded_to_this_item' => __( 'Uploaded to this Event', 'wp-event-management' ),
				'featured_image'        => _x( 'Featured Image', 'event', 'wp-event-management' ),
				'set_featured_image'    => _x( 'Set featured image', 'event', 'wp-event-management' ),
				'remove_featured_image' => _x( 'Remove featured image', 'event', 'wp-event-management' ),
				'use_featured_image'    => _x( 'Use as featured image', 'event', 'wp-event-management' ),
				'filter_items_list'     => __( 'Filter Events list', 'wp-event-management' ),
				'items_list_navigation' => __( 'Events list navigation', 'wp-event-management' ),
				'items_list'            => __( 'Events list', 'wp-event-management' ),
				'new_item'              => __( 'New Event', 'wp-event-management' ),
				'add_new'               => __( 'Add New', 'wp-event-management' ),
				'add_new_item'          => __( 'Add New Event', 'wp-event-management' ),
				'edit_item'             => __( 'Edit Event', 'wp-event-management' ),
				'view_item'             => __( 'View Event', 'wp-event-management' ),
				'view_items'            => __( 'View Events', 'wp-event-management' ),
				'search_items'          => __( 'Search Events', 'wp-event-management' ),
				'not_found'             => __( 'No Events found', 'wp-event-management' ),
				'not_found_in_trash'    => __( 'No Events found in trash', 'wp-event-management' ),
				'parent_item_colon'     => __( 'Parent Event:', 'wp-event-management' ),
				'menu_name'             => __( 'Events', 'wp-event-management' ),
			],
			'public'                => true,
			'hierarchical'          => false,
			'show_ui'               => true,
			'show_in_nav_menus'     => true,
			'supports'              => [ 'title', 'thumbnail', 'editor', 'excerpt' ],
			'has_archive'           => false,
			'rewrite'               => true,
			'query_var'             => true,
			'menu_position'         => null,
			'menu_icon'             => 'dashicons-tickets-alt',
			'capabilities'       	=> $capabilities,
			'show_in_rest'          => true,
			'rest_base'             => 'event',
			'rest_controller_class' => 'WP_REST_Posts_Controller',
		]
	);

}

add_action( 'init', 'event_init' );

/**
 * Sets the post updated messages for the `event` post type.
 *
 * @param  array $messages Post updated messages.
 * @return array Messages for the `event` post type.
 */
function event_updated_messages( $messages ) {
	global $post;

	$permalink = get_permalink( $post );

	$messages['event'] = [
		0  => '', // Unused. Messages start at index 1.
		/* translators: %s: post permalink */
		1  => sprintf( __( 'Event updated. <a target="_blank" href="%s">View Event</a>', 'wp-event-management' ), esc_url( $permalink ) ),
		2  => __( 'Custom field updated.', 'wp-event-management' ),
		3  => __( 'Custom field deleted.', 'wp-event-management' ),
		4  => __( 'Event updated.', 'wp-event-management' ),
		/* translators: %s: date and time of the revision */
		5  => isset( $_GET['revision'] ) ? sprintf( __( 'Event restored to revision from %s', 'wp-event-management' ), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false, // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		/* translators: %s: post permalink */
		6  => sprintf( __( 'Event published. <a href="%s">View Event</a>', 'wp-event-management' ), esc_url( $permalink ) ),
		7  => __( 'Event saved.', 'wp-event-management' ),
		/* translators: %s: post permalink */
		8  => sprintf( __( 'Event submitted. <a target="_blank" href="%s">Preview Event</a>', 'wp-event-management' ), esc_url( add_query_arg( 'preview', 'true', $permalink ) ) ),
		/* translators: 1: Publish box date format, see https://secure.php.net/date 2: Post permalink */
		9  => sprintf( __( 'Event scheduled for: <strong>%1$s</strong>. <a target="_blank" href="%2$s">Preview Event</a>', 'wp-event-management' ), date_i18n( __( 'M j, Y @ G:i', 'wp-event-management' ), strtotime( $post->post_date ) ), esc_url( $permalink ) ),
		/* translators: %s: post permalink */
		10 => sprintf( __( 'Event draft updated. <a target="_blank" href="%s">Preview Event</a>', 'wp-event-management' ), esc_url( add_query_arg( 'preview', 'true', $permalink ) ) ),
	];

	return $messages;
}

add_filter( 'post_updated_messages', 'event_updated_messages' );

/**
 * Sets the bulk post updated messages for the `event` post type.
 *
 * @param  array $bulk_messages Arrays of messages, each keyed by the corresponding post type. Messages are
 *                              keyed with 'updated', 'locked', 'deleted', 'trashed', and 'untrashed'.
 * @param  int[] $bulk_counts   Array of item counts for each message, used to build internationalized strings.
 * @return array Bulk messages for the `event` post type.
 */
function event_bulk_updated_messages( $bulk_messages, $bulk_counts ) {
	global $post;

	$bulk_messages['event'] = [
		/* translators: %s: Number of Events. */
		'updated'   => _n( '%s Event updated.', '%s Events updated.', $bulk_counts['updated'], 'wp-event-management' ),
		'locked'    => ( 1 === $bulk_counts['locked'] ) ? __( '1 Event not updated, somebody is editing it.', 'wp-event-management' ) :
						/* translators: %s: Number of Events. */
						_n( '%s Event not updated, somebody is editing it.', '%s Events not updated, somebody is editing them.', $bulk_counts['locked'], 'wp-event-management' ),
		/* translators: %s: Number of Events. */
		'deleted'   => _n( '%s Event permanently deleted.', '%s Events permanently deleted.', $bulk_counts['deleted'], 'wp-event-management' ),
		/* translators: %s: Number of Events. */
		'trashed'   => _n( '%s Event moved to the Trash.', '%s Events moved to the Trash.', $bulk_counts['trashed'], 'wp-event-management' ),
		/* translators: %s: Number of Events. */
		'untrashed' => _n( '%s Event restored from the Trash.', '%s Events restored from the Trash.', $bulk_counts['untrashed'], 'wp-event-management' ),
	];

	return $bulk_messages;
}

add_filter( 'bulk_post_updated_messages', 'event_bulk_updated_messages', 10, 2 );
