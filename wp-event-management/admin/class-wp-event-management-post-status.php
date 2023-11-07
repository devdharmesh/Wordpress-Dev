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

class Wp_Event_Management_Post_Status
{
    public $post_types = array('event');
    public $post_status = array(
        'pending-event'    => 'Pending Event'
    );

    public function __construct()
    {
        add_action('init', array($this, 'register_post_status'), 8);
        add_action('admin_print_footer_scripts', array($this, 'append_post_status_list'), 8);
        add_filter('display_post_states', array($this, 'display_status_label'));
        add_filter('post_row_actions', array($this, 'post_row_actions'), 10, 2);
        add_action('admin_init', array($this, 'admin_init'));
    }

    public function register_post_status()
    {
        foreach ($this->post_status as $key => $status) {
            register_post_status($key, array(
                'label'                     => _x($status, 'wp-event-management'),
                'public'                    => true,
                'exclude_from_search'       => true,
                'show_in_admin_all_list'    => true,
                'show_in_admin_status_list' => true,
                'label_count'               => _n_noop($status . ' <span class="count">(%s)</span>', $status . ' <span class="count">(%s)</span>'),
            ));
        }
    }

    public function append_post_status_list()
    {
        global $post;
        if (!isset($post)) return;
        if (!($post instanceof WP_Post)) return;
        if (!in_array($post->post_type, array_keys($this->post_types))) return;
        $is_selected =  in_array($post->post_status, array_keys($this->post_status));
        echo sprintf(
            '<script type="text/javascript">
            jQuery(function() {
                let expired_selected = %1$s;
                let mainStatus = JSON.parse(\'%2$s\');
                let $post_status = jQuery("#post_status"), $post_status_display = jQuery("#post-status-display");
                for( status in mainStatus ) {
                    $post_status.append(`<option value="${status}">${mainStatus[status]}</option>`);
                    if( expired_selected ) {
                        $post_status.val( `${status}` );
                        $post_status_display.text(`${mainStatus[status]}`);
                    }
                }
            });
            jQuery(function() {
                const insert_expired_status_to_inline_edit = function(t, post_id, $row) {
                    let $editRow = jQuery(`#edit-` + post_id);
                    let $rowData = jQuery(`#inline_` + post_id);
                    let status = jQuery(`._status`, $rowData).text();
                    let mainStatus = JSON.parse(\'%2$s\');
                    let $status_select = $editRow.find(`select[name="_status"]`);
                    for( _status in mainStatus ) {
                        if ( $status_select.find(`option[value="${_status}"]`).length < 1 ) {
                            $status_select.append(`<option value="${_status}">${mainStatus[_status]}</option>`);
                        }
                        if ( status === _status ) $status_select.val( `${_status}` );
                    }
                };
                const inline_edit_post_status = function() {
                    let t = window.inlineEditPost;
                    let $row = jQuery(this).closest(`tr`);
                    let post_id = t.getId(this);
                    if ( typeof requestAnimationFrame === "function" ) {
                        requestAnimationFrame(function() { return insert_expired_status_to_inline_edit( t, post_id, $row ); });
                    } else {
                        setTimeout(function() { return insert_expired_status_to_inline_edit( t, post_id, $row ); }, 250 );
                    }
                };
                
                jQuery(`#the-list`).on(`click`, `.editinline`, inline_edit_post_status);
            });
            </script>',
            ($is_selected ? 1 : 0),
            json_encode($this->post_status)
        );
    }

    public function display_status_label($statuses)
    {
        global $post;
        if (!in_array(get_query_var('post_status'), array_keys($this->post_status))) {
            if (in_array($post->post_status, array_keys($this->post_status))) {
                echo sprintf('<script>jQuery(document).ready( function() { jQuery( `#post-status-display` ).text( %s ); });</script>', $this->post_status[$post->post_status]);
                return array($this->post_status[$post->post_status]);
            }
        }
        return $statuses;
    }

    public function post_row_actions($actions, $post)
    {
        if ($post->post_type === 'event' && $post->post_status === 'pending-event') {
            $approve_url = admin_url("post.php?post={$post->ID}&action=edit&event_approve=1");
            $actions['event-approve'] = '<a href="' . esc_url($approve_url) . '">Approve</a>';
        }
        return $actions;
    }

    public function admin_init()
    {
        if (isset($_GET['post']) && isset($_GET['action']) && $_GET['action'] === 'edit' && isset($_GET['event_approve']) && $_GET['event_approve'] === '1') {
            $post_id = intval($_GET['post']);
            $post = get_post($post_id);

            if ($post && $post->post_type === 'event' && $post->post_status === 'pending-event') {
                // Approve the event post.
                wp_update_post(array(
                    'ID' => $post_id,
                    'post_status' => 'publish'
                ));
                wp_redirect(admin_url('edit.php?post_type=event'));
                exit;
            }
        }
    }
}
