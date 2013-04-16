<?php
/*
This is a sample local-config.php file
In it, you *must* include the four main database defines

You may include other settings here that you only want enabled on your local development checkouts
*/

define('DB_NAME', 'database_name');
define('DB_USER', 'root');
define('DB_PASSWORD', 'password');
define('DB_HOST', 'localhost');

/* ============================================================================
 * Debug mode
 * ===========================================================================*/
define('SAVEQUERIES', true);
define('WP_DEBUG', true);