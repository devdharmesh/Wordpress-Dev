<?php

/**
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package Dharmesh_Root
 */

if (!function_exists('wp_body_open')) {
	/**
	 * Shim for sites older than 5.2.
	 *
	 * @link https://core.trac.wordpress.org/ticket/12563
	 */
	function wp_body_open()
	{
		do_action('wp_body_open');
	}
}

/**
 * Get image form attachment_id
 *
 * @param int $attachment_id
 * @param string $size
 * @param boolean $echo
 * @param boolean $return_url
 * @return void
 */
function get_image($attachment_id, $size = 'full', $echo = true, $return_url = false)
{
	$image = wp_get_attachment_image($attachment_id, $size);
	$attachment = get_post($attachment_id);
	$mime_type = $attachment->post_mime_type;
	if ($mime_type == 'image/svg+xml') {
		$image = file_get_contents(get_attached_file($attachment_id));
	}
	if ($return_url) {
		$url = wp_get_attachment_url($attachment_id);
		return $url;
	}
	if ($echo) {
		echo $image;
	} else {
		return $image;
	}
}

/**
 * Get image url form attachment_id
 *
 * @param int $attachment_id
 * @param boolean $echo
 * @return void
 */
function get_image_url($attachment_id, $echo = true)
{
	$image = get_image($attachment_id, 'full', false, true);
	if ($echo) {
		echo $image;
	} else {
		return $image;
	}
}

/**
 * allows uploading SVG file in media library
 *
 * @param string $mimes
 * @return void
 */
function allow_svg_upload( $mimes ) {
    $mimes['svg'] = 'image/svg+xml';
    return $mimes;
}
add_filter( 'upload_mimes', 'allow_svg_upload' );
