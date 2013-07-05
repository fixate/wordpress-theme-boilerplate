<?php
/*
Template Name: Contact form template (Remove if not using)
*/
?>
<?php
/**
 * Contact form template
 *
 * @package WordPress
 * @subpackage theme_folder
 * @since Theme Name 1.0
 */
get_header(); ?>

	<?php while ( have_posts()) : the_post(); ?>
		<?php get_template_part('contactform', 'page') ?>
	<?php endwhile; ?>

<?php get_sidebar(); ?>
<?php get_footer(); ?>