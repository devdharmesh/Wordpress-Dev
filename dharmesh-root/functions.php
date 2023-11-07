<?php

/**
 * Dharmesh Root functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package Dharmesh_Root
 */

if (!defined('ABSPATH')) {
    header('Location: /', 404);
	exit; // Exit if accessed directly.
}

// Define theme constants
define('THEME_NAME', 'Dharmesh_Root');
define('THEME_VERSION', '1.0');
define('THEME_DIR', trailingslashit(get_template_directory()));
define('THEME_URI', trailingslashit(get_template_directory_uri()));

// Define the directory where the class files are located
$class_dir = THEME_DIR . 'inc/classes/';

// Get all PHP files in the class directory
$class_files = glob($class_dir . '*.php');

$class_files[] = THEME_DIR . 'inc/template-functions.php';

$exclude_file = []; // only filename with extension

// Require each class file
foreach ($class_files as $class_file) {
    if( $exclude_file ) {
        if( in_array( basename( $class_file ), $exclude_file ) ) continue;
    }
	require_once $class_file;
}

new Root_Theme_Setup();
