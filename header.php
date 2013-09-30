<?php
/**
 * The Header for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="main">
 *
 * @package WordPress
 * @subpackage theme_folder
 * @since Theme_Name 1.0
 */
?>
<!doctype html>
<!--[if IE 8]>    <html class="no-js ie8 oldie" <?php language_attributes(); ?>> <![endif]-->
<!--[if IE 9]>    <html class="no-js ie9 oldie" <?php language_attributes(); ?>> <![endif]-->
<!--[if gt IE 9]><!--> <html class="no-js" <?php language_attributes(); ?>> <!--<![endif]-->

	<?php get_template_part('partials/meta') ?>

	<body <?php body_class(); ?>>

		<!--[if lte IE 8]>
		<div class="wrap cf">
			<div class="alert">Your browser is <em>ancient!</em> <a href="http://browsehappy.com/">Upgrade to a modern browser</a> to experience this site.</div>
		</div>
		<![endif]-->

	<header role="banner">

		<div class="wrap cf">

					<a class="logo" href="<?php echo home_url('/'); ?>">
            <svg width="100" height="100">
							<image xlink:href="<?php echo THEME_URI; ?>/img/logo.svg" src="<?php echo THEME_URI; ?>/img/logo.png"  width="100" height="100" />
						</svg>
					</a>

			<?php // allow screenreaders to skip navigation ?>
			<a class="visuallyhidden" href="#main">skip navigation and go to main content</a>

			<nav id="nav" role="navigation">
				<?php wp_nav_menu(array(
					'theme_location' => 'menu-primary',
					'fallback_cb' => 'default_primary_menu',
					'menu_class' => 'menu menu-primary',
					'container' => false,
				)); ?>
			</nav>

		</div>

	</header>

	<div class="area-content">

		<div id="main" class="wrap cf" role="main">
