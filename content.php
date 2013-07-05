<?php
/**
 * The default template for displaying content. Used for both single and index/archive/search.
 *
 * @package WordPress
 * @subpackage theme_folder
 * @since Theme Name 1.0
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

	<?php if (is_single()) : ?>
		<h1><?php the_title(); ?></h1>
	<?php else: ?>
		<h1><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h1>
	<?php endif // is_single() ?>

	<p>
		<span>Written by: <?php the_author_posts_link() ?>, on</span>

		<time datetime="<?php the_time('o-m-d') ?>" pubdate><?php the_time('M j, Y') ?>,</time>

		<?php if (has_category()): ?>
			<span>categorised under: <?php the_category(', ') ?>,</span>
		<?php endif; // has_category(); ?>

		<?php if ( comments_open()) : ?>
			<span>
				<?php
					comments_popup_link( __('0 Comments', 'theme_local'), __('1 Comment', 'theme_local'), __('% Comments', 'theme_local'));
				?>
			</span>
		<?php endif; // comments_open() ?>
	</p>

	<?php if (!is_single()): ?>
		<p>
			<?php echo theme_local_custom_excerpt(40); ?>
			<a href="<?php the_permalink() ?>">read more</a>
		</p>
	<?php else: ?>
		<?php the_content(); ?>

		<?php if (has_tag()): ?>
		<div>
			Tags: <?php the_tags(' <span>', ', ', '</span>'); ?>
		</div>
		<?php endif; // has_tag() ?>
	<?php endif // is_single() ?>

</article><!-- .post -->