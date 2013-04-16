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
						'theme_location' => 'footer-nav',
						'fallback_cb' => '',
						'container'  => '',
						'menu_id' => 'footer-nav',
						'menu_class' => 'footer-nav'
						)
					);
				?>

				<div>
					<?php bloginfo(); ?> &copy; <?php echo date('Y'); ?> | Powered by <a href="http://wordpress.org">WordPress</a>
				</div>

			</div>

		</footer><!-- .area-footer -->

		<?php wp_footer(); ?>

	</body>
</html>