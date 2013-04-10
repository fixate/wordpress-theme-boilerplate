<?php
/**
 * The template for displaying all pages.
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site will use a
 * different template.
 *
 * @package WordPress
 * @subpackage Theme_Name
 * @since Theme Name 1.0
 */

get_header(); ?>

	<?php while ( have_posts()) : the_post(); ?>
		<?php get_template_part('content', 'page') ?>
	<?php endwhile; ?>

<?php get_sidebar(); ?>
<?php get_footer(); ?>