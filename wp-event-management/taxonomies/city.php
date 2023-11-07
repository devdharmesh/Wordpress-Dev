<?php

/**
 * Registers the `city` taxonomy,
 * for use with 'event'.
 */
function city_init()
{
	register_taxonomy('city', ['event'], [
		'hierarchical'          => true,
		'public'                => true,
		'publicly_queryable'    => false,
		'show_in_nav_menus'     => true,
		'show_ui'               => true,
		'show_admin_column'     => true,
		'query_var'             => true,
		'rewrite'               => true,
		'capabilities'          => [
			'manage_terms' => 'edit_posts',
			'edit_terms'   => 'edit_posts',
			'delete_terms' => 'edit_posts',
			'assign_terms' => 'edit_posts',
		],
		'labels'                => [
			'name'                       => __('Cities', 'wp-event-management'),
			'singular_name'              => _x('City', 'taxonomy general name', 'wp-event-management'),
			'search_items'               => __('Search Cities', 'wp-event-management'),
			'popular_items'              => __('Popular Cities', 'wp-event-management'),
			'all_items'                  => __('All Cities', 'wp-event-management'),
			'parent_item'                => __('Parent City', 'wp-event-management'),
			'parent_item_colon'          => __('Parent City:', 'wp-event-management'),
			'edit_item'                  => __('Edit City', 'wp-event-management'),
			'update_item'                => __('Update City', 'wp-event-management'),
			'view_item'                  => __('View City', 'wp-event-management'),
			'add_new_item'               => __('Add New City', 'wp-event-management'),
			'new_item_name'              => __('New City', 'wp-event-management'),
			'separate_items_with_commas' => __('Separate Cities with commas', 'wp-event-management'),
			'add_or_remove_items'        => __('Add or remove Cities', 'wp-event-management'),
			'choose_from_most_used'      => __('Choose from the most used Cities', 'wp-event-management'),
			'not_found'                  => __('No Cities found.', 'wp-event-management'),
			'no_terms'                   => __('No Cities', 'wp-event-management'),
			'menu_name'                  => __('Cities', 'wp-event-management'),
			'items_list_navigation'      => __('Cities list navigation', 'wp-event-management'),
			'items_list'                 => __('Cities list', 'wp-event-management'),
			'most_used'                  => _x('Most Used', 'city', 'wp-event-management'),
			'back_to_items'              => __('&larr; Back to Cities', 'wp-event-management'),
		],
		'show_in_rest'          => true,
		'rest_base'             => 'city',
		'rest_controller_class' => 'WP_REST_Terms_Controller',
	]);
}

add_action('init', 'city_init');

/**
 * Sets the post updated messages for the `city` taxonomy.
 *
 * @param  array $messages Post updated messages.
 * @return array Messages for the `city` taxonomy.
 */
function city_updated_messages($messages)
{

	$messages['city'] = [
		0 => '', // Unused. Messages start at index 1.
		1 => __('City added.', 'wp-event-management'),
		2 => __('City deleted.', 'wp-event-management'),
		3 => __('City updated.', 'wp-event-management'),
		4 => __('City not added.', 'wp-event-management'),
		5 => __('City not updated.', 'wp-event-management'),
		6 => __('Cities deleted.', 'wp-event-management'),
	];

	return $messages;
}

add_filter('term_updated_messages', 'city_updated_messages');
