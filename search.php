<?php
/**
 * The template for displaying Search Results pages.
 *
 * @package WordPress
 * @subpackage theme_folder
 * @since Theme Name 1.0
 */

get_header(); ?>

<?php if (have_posts()) : ?>

	<section>
		<h1 class="page-title"><?php printf( __('Results for: %s', 'theme_local'), '<span>' . get_search_query() . '</span>'); ?></h1>

		<?php while (have_posts()) : the_post(); ?>
			<?php get_template_part('content' , get_post_format()); ?>
		<?php endwhile; ?>

		<?php get_template_part('pagination'); ?>
	</section>

<?php else : ?>

	<p><?php _e('Sorry, we didn\'t find what you were looking for. Please try again with a different keyword.', 'theme_local'); ?></p>

<?php endif; // have_posts() ?>

<?php get_sidebar(); ?>
<?php get_footer(); ?>