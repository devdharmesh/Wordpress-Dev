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


class Wp_Event_Management_Filter
{
    /**
     * Constructor for the class.
     *
     * @return void
     */
    public function __construct()
    {
        add_action(
            'restrict_manage_posts',
            [$this, 'restrict_manage_posts'],
            10,
            1
        );

        add_filter(
            'parse_query',
            [$this, 'parse_query'],
            10,
            1
        );
    }

    /**
     * Restrict manage posts.
     *
     * @param string $screen_id The screen ID.
     * @return void
     */
    public function restrict_manage_posts(string $screen_id): void
    {
        $screen = get_current_screen();
        if ($screen->id === 'edit-event') {
            $terms = get_terms('city', 'hide_empty=0');
            $selected_city = $this->get_selected_city();
            $selected_date_filter = $this->get_selected_date_filter();
            $selected_event_type = $this->get_selected_event_type();

            $this->output_city_select($terms, $selected_city);
            $this->output_event_type_select($selected_event_type);
            $this->output_date_filter_select($selected_date_filter);
        }
    }

    /**
     * Get the selected city.
     *
     * @return string The selected city.
     */
    private function get_selected_city(): string
    {
        return isset($_GET['city']) ? $_GET['city'] : '';
    }

    /**
     * Get the selected date filter.
     *
     * @return string The selected date filter.
     */
    private function get_selected_date_filter(): string
    {
        return isset($_GET['event_date_filter']) ? $_GET['event_date_filter'] : '';
    }

    /**
     * Get the selected event type.
     *
     * @return string The selected event type.
     */
    private function get_selected_event_type(): string
    {
        return isset($_GET['event_type']) ? $_GET['event_type'] : '';
    }

    /**
     * Output the city select.
     *
     * @param array $terms The terms.
     * @param string $selected_city The selected city.
     * @return void
     */
    private function output_city_select(array $terms, string $selected_city): void
    {
        echo '<select name="city">';
        echo '<option value="">Filter by City</option>';
        foreach ($terms as $term) {
            $selected = ($selected_city === $term->slug) ? 'selected' : '';
            echo '<option value="' . $term->slug . '" ' . $selected . '>' . $term->name . '</option>';
        }
        echo '</select>';
    }

    /**
     * Output the event type select.
     *
     * @param string $selected_event_type The selected event type.
     * @return void
     */
    private function output_event_type_select(string $selected_event_type): void
    {
        echo '<select name="event_type">';
        echo '<option value="">Filter by Event Type</option>';
        echo '<option value="physical" ' . selected('physical', $selected_event_type, false) . '>Physical</option>';
        echo '<option value="virtual" ' . selected('virtual', $selected_event_type, false) . '>Virtual</option>';
        echo '</select>';
    }

    /**
     * Output the date filter select.
     *
     * @param string $selected_date_filter The selected date filter.
     * @return void
     */
    private function output_date_filter_select(string $selected_date_filter): void
    {
        echo '<select name="event_date_filter">';
        echo '<option value="">Filter by Date</option>';
        echo '<option value="next_day" ' . selected('next_day', $selected_date_filter, false) . '>Next Day</option>';
        echo '<option value="next_week" ' . selected('next_week', $selected_date_filter, false) . '>Next Week</option>';
        echo '<option value="next_month" ' . selected('next_month', $selected_date_filter, false) . '>Next Month</option>';
        echo '<option value="past_events" ' . selected('past_events', $selected_date_filter, false) . '>Past Events</option>';
        echo '</select>';
    }

    /**
     * Parse query
     *
     * @param \WP_Query $query
     * @return void
     */
    public function parse_query(\WP_Query $query): void
    {
        if (!is_admin() || !$query->is_main_query()) {
            return;
        }

        $this->setCityTaxQuery($query);
        $this->setEventTypeMetaQuery($query);
        $this->setEventDateFilter($query);
    }

    /**
     * Set city tax query
     *
     * @param \WP_Query $query
     * @return void
     */
    private function setCityTaxQuery(\WP_Query $query): void
    {
        if (isset($_GET['city']) && $_GET['city'] !== '') {
            $query->set('tax_query', [
                [
                    'taxonomy' => 'city',
                    'field' => 'slug',
                    'terms' => $_GET['city'],
                ],
            ]);
        }
    }

    /**
     * Set event type meta query
     *
     * @param \WP_Query $query
     * @return void
     */
    private function setEventTypeMetaQuery(\WP_Query $query): void
    {
        if (isset($_GET['event_type']) && $_GET['event_type'] !== '') {
            $event_type = sanitize_text_field($_GET['event_type']);
            $meta_query = $this->getMetaQuery($event_type);
            if ($meta_query) {
                $query->set('meta_query', $meta_query);
            }
        }
    }

    /**
     * Get meta query
     *
     * @param string $event_type
     * @return array|null
     */
    private function getMetaQuery(string $event_type): ?array
    {
        $meta_query = null;
        if ($event_type === 'physical' || $event_type === 'virtual') {
            $meta_query = [
                'relation' => 'AND',
                [
                    'key' => 'event-type',
                    'value' => $event_type,
                    'compare' => '=',
                ],
            ];
        }
        return $meta_query;
    }

    /**
     * Set event date filter
     *
     * @param \WP_Query $query
     * @return void
     */
    private function setEventDateFilter(\WP_Query $query): void
    {
        if (isset($_GET['event_date_filter']) && $_GET['event_date_filter'] !== '') {
            $now = current_time('timestamp');
            $next_day = date('Y-m-d', strtotime('+1 day', $now));
            $two_days_later = date('Y-m-d', strtotime('+2 days', $now));

            $start_of_next_week = strtotime('next Monday', $now);
            $end_of_next_week = strtotime('next Sunday', $start_of_next_week);

            $start_of_next_month = strtotime('first day of next month', $now);
            $end_of_next_month = strtotime('last day of next month', $start_of_next_month);

            $date_filter = $_GET['event_date_filter'];
            $meta_query = $this->getDateMetaQuery($date_filter, $now, $next_day, $two_days_later, $start_of_next_week, $end_of_next_week, $start_of_next_month, $end_of_next_month);
            if ($meta_query) {
                $query->set('meta_query', $meta_query);
            }
        }
    }

    /**
     * Get date meta query
     *
     * @param string $date_filter
     * @param int $now
     * @param string $next_day
     * @param string $two_days_later
     * @param int $start_of_next_week
     * @param int $end_of_next_week
     * @param int $start_of_next_month
     * @param int $end_of_next_month
     * @return array|null
     */
    private function getDateMetaQuery(string $date_filter, int $now, string $next_day, string $two_days, int $start_of_next_week, int $end_of_next_week, int $start_of_next_month, int $end_of_next_month): ?array
    {
        $meta_query = null;
        $date_time_key = 'event-date-time';
        $compare_key = 'compare';
        $meta_type = 'type';
        $meta_relation = 'DATE';
        $relation_key = 'relation';
        $and_relation = 'AND';

        switch ($date_filter) {
            case 'next_day':
                $meta_query = [
                    $relation_key => $and_relation,
                    [
                        'key' => $date_time_key,
                        'value' => $next_day,
                        'compare' => '>=',
                        'type' => 'DATE',
                    ],
                    [
                        'key' => $date_time_key,
                        'value' => $two_days,
                        'compare' => '<=',
                        'type' => 'DATE',
                    ],
                ];
                break;
            case 'next_week':
                $meta_query = [
                    $relation_key => $and_relation,
                    [
                        'key' => $date_time_key,
                        'value' => date('Y-m-d', $start_of_next_week),
                        'compare' => '>=',
                        'type' => 'DATE',
                    ],
                    [
                        'key' => $date_time_key,
                        'value' => date('Y-m-d', $end_of_next_week),
                        'compare' => '<=',
                        'type' => 'DATE',
                    ]
                ];
                break;
            case 'next_month':
                $meta_query = [
                    $relation_key => $and_relation,
                    [
                        'key' => $date_time_key,
                        'value' => date('Y-m-d', $start_of_next_month),
                        'compare' => '>=',
                        'type' => 'DATE',
                    ],
                    [
                        'key' => $date_time_key,
                        'value' => date('Y-m-d', $end_of_next_month),
                        'compare' => '<=',
                        'type' => 'DATE',
                    ]
                ];
                break;
            case 'past_events':
                $meta_query = [
                    $relation_key => $and_relation,
                    [
                        'key' => $date_time_key,
                        'value' => date('Y-m-d', $now),
                        'compare' => '<',
                        'type' => 'DATE',
                    ],
                ];
                break;
        }

        return $meta_query;
    }
}
