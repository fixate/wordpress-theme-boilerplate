<?php

// General Admin Initialisation
function mp_admin_init() {
  // Register and enqueue admin scripts
  wp_register_style('mp-admin', THEME_DIR. '/inc/wp-admin/css/admin.css');
  wp_enqueue_style('mp-admin');
} add_action('admin_init', 'mp_admin_init');
