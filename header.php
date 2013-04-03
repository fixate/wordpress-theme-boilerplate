<?php
/**
 * The Header for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="main">
 *
 * @package WordPress
 * @subpackage Theme_Name
 * @since Theme Name 1.0
 */
?>
<!doctype html>
<!--[if IE 8]>    <html class="no-js ie8 oldie" <?php language_attributes(); ?>> <![endif]-->
<!--[if IE 9]>    <html class="no-js ie9 oldie" <?php language_attributes(); ?>> <![endif]-->
<!--[if gt IE 9]><!--> <html class="no-js" <?php language_attributes(); ?>> <!--<![endif]-->
	<head>
		<meta charset="<?php bloginfo('charset'); ?>">
		<link rel="dns-prefetch" href="//ajax.googleapis.com" />

		<title><?php wp_title('|'); ?></title>

		<?php // enable responsive behaviour for all devices ?>
		<meta name="viewport" content="width=device-width, initial-scale=1">

		<?php // Windows 8 start screen tile ?>
		<meta name="msapplication-TileColor" content="#ffffff"/>
		<meta name="msapplication-TileImage" content="apple-touch-icon-144x144-precomposed.png"/>

		<link rel="SHORTCUT ICON" href="<?php echo THEME_DIR; ?>/img/favicon.ico" type="image/x-icon" />
		<link rel="logo" type="image/svg" href="<?php echo THEME_DIR; ?>/img/logo.svg"/>
		<!--[if ! lte IE 7]><!-->
		<link rel="stylesheet" href="<?php bloginfo( 'stylesheet_url' ); ?>" />
		<!--<![endif]-->

		<link rel="alternate" type="application/rss+xml" title="RSS 2.0" href="<?php bloginfo('rss2_url'); ?>" />
		<link rel="alternate" type="text/xml" title="RSS .92" href="<?php bloginfo('rss_url'); ?>" />
		<link rel="alternate" type="application/atom+xml" title="Atom 0.3" href="<?php bloginfo('atom_url'); ?>" />
		<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />

		<!--[if lte IE 7]>
			<link rel="stylesheet" href="http://universal-ie6-css.googlecode.com/files/ie6.1.1.css" media="screen, projection">
		<![endif]-->

		<!--[if lt IE 9]>
		<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
		<![endif]-->

		<?php wp_head(); ?>
	</head>
	<?php flush(); ?>
	<body <?php body_class(); ?>>

		<!--[if lte IE 8]>
		<div class="wrap cf">
			<div class="alert alert-danger">Your browser is <em>ancient!</em> <a href="http://browsehappy.com/">Upgrade to a different browser</a> or <a href="http://www.google.com/chromeframe/?redirect=true">install Google Chrome Frame</a> to experience this site.</div>
		</div>
		<![endif]-->
	<header>

		<div class="wrap cf">

				<!--[if lte IE 8]><a class="logo" href="<?php echo home_url('/'); ?>"><img src="<?php echo THEME_DIR; ?>/img/logo.png"></a><![endif]-->
				<!--[if gt IE 8]><a class="logo" href="<?php echo home_url('/'); ?>"><img src="<?php echo THEME_DIR; ?>/img/logo.svg"></a><![endif]-->
				<!--[if !IE]> --><a class="logo" href="<?php echo home_url('/'); ?>"><img src="<?php echo THEME_DIR; ?>/img/logo.svg"></a><!-- <![endif]-->

			<?php // allow screenreaders to skip navigation ?>
			<a class="visuallyhidden" href="#main">skip navigation and go to main content</a>

			<nav id="nav" role="navigation">
				<?php wp_nav_menu(array(
					'theme_location' => 'primary-nav',
					'fallback_cb' => 'default_primary_nav',
					'menu_class' => 'menu',
					'container' => false,
				)); ?>
			</nav>

		</div>

	</header>

	<div class="area-content">

		<div id="main" class="wrap cf" role="main">

			<!--[if lte IE 8]><div class="alert alert-danger">Your browser is <em>ancient!</em> <a href="http://browsehappy.com/">Upgrade to a different browser</a> or <a href="http://www.google.com/chromeframe/?redirect=true">install Google Chrome Frame</a> to experience this site.</div><![endif]-->
