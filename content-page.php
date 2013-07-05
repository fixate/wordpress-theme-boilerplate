<?php
/**
 * The template used for displaying page content in page.php
 *
 * @package WordPress
 * @subpackage theme_folder
 * @since Theme_Name 1.0
 */
?>

<section id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

	<h1><?php the_title(); ?></h1>

	<?php the_content(); ?>

</section><!-- .post -->