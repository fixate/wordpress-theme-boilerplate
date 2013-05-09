<?php

# Include custom metaboxes
# require_once ('metaboxes.php');

// General Admin Initialisation
function mp_admin_init() {
  // Register and enqueue admin scripts
  wp_register_style('mp-admin', THEME_URI. '/inc/wp-admin/css/admin.css');
  wp_enqueue_style('mp-admin');
} add_action('admin_init', 'mp_admin_init');
