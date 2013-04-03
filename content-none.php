<?php
/**
 * The template for displaying a "No posts found" message.
 *
 * @package WordPress
 * @subpackage Theme_Name
 * @since Theme Name 1.0
 */
?>

<section id="post-0">

	<h1><?php _e( 'Nothing Found', 'theme_name' ); ?></h1>

	<p><?php _e( 'No results were found. Perhaps searching will help find a related post.', 'theme_name' ); ?></p>
	<?php get_search_form(); ?>

</section><!-- .post -->