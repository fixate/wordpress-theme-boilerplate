<?php
/**
 * The template for displaying Category pages.
 *
 * Used to display archive-type pages for posts in a category.
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package WordPress
 * @subpackage Theme_Name
 * @since Theme Name 1.0
 */

get_header(); ?>

<?php if ( have_posts()) : ?>
	<h1><?php printf( __('Category Archives: %s', 'twentytwelve'), '<span>' . single_cat_title('', false) . '</span>'); ?></h1>

	<?php if ( category_description()) : // Show an optional category description ?>
		<div><?php echo category_description(); ?></div>
	<?php endif; ?>

	<?php	while ( have_posts()) : the_post(); ?>

		<?php	get_template_part('content', get_post_format()); ?>

	<?php endwhile; ?>

	<?php get_template_part('pagination');?>

<?php else : ?>
	<?php get_template_part('content', 'none'); ?>
<?php endif; // have_posts() ?>

<?php get_sidebar(); ?>
<?php get_footer(); ?>