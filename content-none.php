<?php
/**
 * The template for displaying a "No posts found" message.
 *
 * @package WordPress
 * @subpackage theme_folder
 * @since Theme_Name 1.0
 */
?>

<section id="post-0">

	<h1><?php _e('Nothing Found', 'theme_text_domain'); ?></h1>

	<p><?php _e('No results were found. Perhaps searching will help find a related post.', 'theme_text_domain'); ?></p>
	<?php get_search_form(); ?>

</section><!-- .post -->