<?php

# Include custom metaboxes
# require_once ('metaboxes.php');

/**
 * Register and enqueue admin styles
 *
 * @since Theme_Name 1.0
 */
/*function theme_local_admin_init() {
  // Register and enqueue admin scripts
  wp_register_style('theme_local-admin', THEME_URI. '/inc/wp-admin/css/admin.css');
  wp_enqueue_style('theme_local-admin');
} add_action('admin_init', 'theme_local_admin_init');*/


/**
 * Add theme favicon to admin area
 *
 * @since Theme_Name 1.0
 */
function theme_local_admin_area_favicon() {
	$favicon_url = get_bloginfo('stylesheet_directory') . '/img/favicon.ico';
	echo '<link rel="shortcut icon" href="' . $favicon_url . '" />';
} add_action('admin_head', 'theme_local_admin_area_favicon');


/**
 * Use theme styles for editor
 *
 * @since Theme_Name 1.0
 */
function theme_local_add_editor_styles() {
	add_editor_style('inc/wp-admin/css/style-editor.css');
} add_action('init', 'theme_local_add_editor_styles');