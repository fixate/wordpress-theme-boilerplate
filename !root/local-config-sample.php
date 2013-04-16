<?php
/*
This is a sample local-config.php file
In it, you *must* include the four main database defines

You may include other settings here that you only want enabled on your local development checkouts
*/

/**
 * Easy local testing on multiple devices without any frustrating server setups.
 *
 * Ensure that in httpd-vhosts.conf you have the following alias for your local
 * site's address:
 * ServerAlias [local.domain].*.xip.io
 *
 * Once you have updated your server config, uncomment the lines below, and then
 * resave your permalink structure in the WordPress admin.
 *
 * Make sure you're testing on devices connected to the same network.
 */
// define('WP_HOME','http://[local.domain].[your ip address].xip.io');
// define('WP_SITEURL','http://[local.domain].[your ip address].xip.io');

define('DB_NAME', 'database_name');
define('DB_USER', 'root');
define('DB_PASSWORD', 'password');
define('DB_HOST', 'localhost');

/* ============================================================================
 * Debug mode
 * ===========================================================================*/
define('SAVEQUERIES', true);
define('WP_DEBUG', true);