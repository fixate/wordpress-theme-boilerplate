<?php
/**
 * The template for displaying Tag pages.
 *
 * Used to display archive-type pages for posts with any particular tag.
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package WordPress
 * @subpackage theme_folder
 * @since Theme_Name 1.0
 */

get_header(); ?>

<?php if ( have_posts()) : ?>
	<h1><?php printf( __('Tag Archives: %s', 'theme_local'), '<span>' . single_tag_title('', false) . '</span>'); ?></h1>

	<?php	while ( have_posts()) : the_post(); ?>

		<?php	get_template_part('content', get_post_format()); ?>

	<?php endwhile; ?>

	<?php get_template_part('pagination');?>

<?php else : ?>
	<?php get_template_part('content', 'none'); ?>
<?php endif; // have_posts() ?>

<?php get_sidebar(); ?>
<?php get_footer(); ?>