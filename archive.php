<?php
/**
 * The template for displaying Archive pages.
 *
 * Used to display archive-type pages if nothing more specific matches a query.
 * For example, puts together date-based pages if no date.php file exists.
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package WordPress
 * @subpackage theme_folder
 * @since Theme_Name 1.0
 */

get_header(); ?>

<?php if ( have_posts()) : ?>
	<h1>
		<?php
			if ( is_day()) :
				printf( __('Daily Archives: %s', 'theme_text_domain'), '<span>' . get_the_date() . '</span>');
			elseif ( is_month()) :
				printf( __('Monthly Archives: %s', 'theme_text_domain'), '<span>' . get_the_date( _x('F Y', 'monthly archives date format', 'theme_text_domain')) . '</span>');
			elseif ( is_year()) :
				printf( __('Yearly Archives: %s', 'theme_text_domain'), '<span>' . get_the_date( _x('Y', 'yearly archives date format', 'theme_text_domain')) . '</span>');
			else :
				_e('Archives', 'theme_text_domain');
			endif;
		?>
	</h1>

	<?php	while ( have_posts()) : the_post(); ?>

		<?php	get_template_part('content', get_post_format()); ?>

	<?php endwhile; ?>

	<?php get_template_part('partials/pagination'); ?>

<?php else : ?>
	<?php get_template_part('content', 'none'); ?>
<?php endif; // have_posts() ?>

<?php get_sidebar(); ?>
<?php get_footer(); ?>