<?php
/**
 * The template for pagination on all post types.
 *
 * @package WordPress
 * @subpackage theme_folder
 * @since Theme_Name 1.0
 */
?>

<?php if (is_single()): ?>
	<div class="paginate">
		<?php previous_post_link('<span class="prev">%link</span>', '<span>&laquo;</span> %title') ?>
		<?php if (get_previous_post() && get_next_post()): ?>
			<span> | </span>
		<?php endif ?>
		<?php next_post_link('<span class="next">%link</span>', '%title <span>&raquo;</span>') ?>
	</div>
<?php else: ?>
	<div class="paginate">
	 <?php if (get_next_posts_link()): ?>
		<span class="prev"><?php next_posts_link(__('&laquo; Older Entries', 'theme_text_domain')) ?></span>
	 <?php endif ?>
	 <?php if (get_previous_posts_link()): ?>
		<span class="next"><?php previous_posts_link(__('Newer Entries &raquo;', 'theme_text_domain')) ?></span>
	 <?php endif ?>
	</div>
<?php endif ?>