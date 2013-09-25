<?php
/**
 * The template for displaying Category pages.
 *
 * Included in functions.php (theme_fn_prefix_comment())
 *
 * @package WordPress
 * @subpackage theme_folder
 * @since Theme_Name 1.0
 */
?>

<?php
	switch ($comment->comment_type) :
		case 'pingback' :
		case 'trackback' :
		// Display trackbacks differently to normal comments.
	?>
	<li <?php comment_class(); ?> id="comment-<?php comment_ID(); ?>">
		<p><?php
			$comment->comment_type == 'pingback' ? _e('Pingback:', 'theme_text_domain') : _e('Trackback:', 'theme_text_domain');
			 ?>
			 <?php comment_author_link(); ?> <?php edit_comment_link( __('(Edit)', 'theme_text_domain'), '<span>', '</span>'); ?>
		</p>
	<?php
			break;
		default :
		// Proceed with normal comments.
		global $post;
	?>
	<li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">
		<article id="comment-<?php comment_ID(); ?>" class="comment">
			<header class="comment-meta comment-author vcard">
				<?php
					echo get_avatar($comment, 44);
					printf('<cite class="fn">%1$s %2$s</cite>',
						get_comment_author_link(),
						// If current post author is also comment author, make it known visually.
						($comment->user_id === $post->post_author) ? '<span> ' . __('Post author', 'theme_text_domain') . '</span>' : ''
					);
					printf('<a href="%1$s"><time datetime="%2$s">%3$s</time></a>',
						esc_url( get_comment_link($comment->comment_ID)),
						get_comment_time('c'),
						/* translators: 1: date, 2: time */
						sprintf( __('%1$s at %2$s', 'theme_text_domain'), get_comment_date(), get_comment_time())
					);
				?>
			</header><!-- .comment-meta -->

			<?php if ($comment->comment_approved == '0') : ?>
				<p class="comment-awaiting-moderation"><?php _e('Your comment is awaiting moderation.', 'theme_text_domain'); ?></p>
			<?php endif; ?>

			<div class="comment-content comment">
				<?php comment_text(); ?>
			</div><!-- .comment-content -->

			<div class="reply">
				<?php comment_reply_link( array_merge($args, array('reply_text' => __('Reply', 'theme_text_domain'), 'depth' => $depth, 'max_depth' => $args['max_depth']))); ?>
			</div><!-- .reply -->
		</article><!-- #comment-## -->
	<?php
		break;
	endswitch; // end comment_type check
?>
<?php /* </li> - added automatically by wordpress */?>