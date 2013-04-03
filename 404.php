<?php
/**
 * The template for displaying 404 pages (Not Found).
 *
 * @package WordPress
 * @subpackage Theme_Name
 * @since Theme Name 1.0
 */

get_header(); ?>

<h1><?php _e( 'This is somewhat embarrassing, isn&rsquo;t it?', 'theme_name' ); ?></h1>
<p><?php _e( 'It seems we can&rsquo;t find what you&rsquo;re looking for. Perhaps searching can help.', 'theme_name' ); ?></p>
<?php get_search_form(); ?>

<?php get_sidebar(); ?>
<?php get_footer(); ?>