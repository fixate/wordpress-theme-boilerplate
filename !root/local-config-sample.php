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
define('SAVEQUERIES', true); // all WordPress queries can be accessed via $wpdb->queries
define('WP_DEBUG', true);

/* ============================================================================
 * Clean up trash
 * ===========================================================================*/
define('EMPTY_TRASH_DAYS', 0 );

/* ============================================================================
 * Don't ask for FTP credentials in dev environment
 *
 * If having issues on Mac, you may need to update our Apache User and Group:
 * https://gist.github.com/larrybotha/0600c4fec587cafebb7a
 * ===========================================================================*/
// define('FS_METHOD', 'direct');

/* ============================================================================
 * Import Local Test Data via admin - https://wpcom-themes.svn.automattic.com/demo/theme-unit-test-data.xml
 * ===========================================================================*/
