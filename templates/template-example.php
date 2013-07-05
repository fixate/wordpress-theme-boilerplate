<?php
/*
Template Name: Example template (Remove if not using)
*/
?>
<?php
/**
 * Template for creating template pages
 *
 * @package WordPress
 * @subpackage theme_folder
 * @since Theme Name 1.0
 */
?>
<?php get_header(); ?>

<?php if (have_posts()): the_post(); ?>

	<h1 id="post-<?php the_id() ?>" class="title"><?php the_title(); ?></h1>
	<?php the_content(); ?>

<?php endif; ?>

<?php get_footer(); ?>