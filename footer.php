<?php
/**
 * The template for displaying the footer.
 *
 * @package WordPress
 * @subpackage Theme_Name
 * @since Theme Name 1.0
 */
?>
			</div><!-- #main -->

		</div><!-- .area-content -->

		<footer class="area-footer">

			<div class="wrap cf">

				<?php // footer navigation ?>
				<?php
					wp_nav_menu(array(
						'theme_location' => 'footer-menu',
						'fallback_cb' => '',
						'container'  => '',
						'menu_id' => 'footer-menu',
						'menu_class' => 'footer-menu'
						)
					);
				?>

				<div>
					&copy; <?php echo date('Y'); ?> <?php bloginfo(); ?> | Powered by <a href="http://wordpress.org">WordPress</a>
				</div>

			</div>

		</footer><!-- .area-footer -->

		<?php wp_footer(); ?>

	</body>
</html>

<?php
/*	if (current_user_can('administrator')){
		global $wpdb;
		echo "<pre>";
		print_r($wpdb->queries);
		echo "<br><br>";
		debug_print_backtrace();
		echo "</pre>";
	}*/
?>
