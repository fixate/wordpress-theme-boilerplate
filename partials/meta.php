<head>
	<meta charset="<?php bloginfo('charset'); ?>">
	<link rel="dns-prefetch" href="//ajax.googleapis.com" />

	<title><?php wp_title('|'); ?></title>

	<?php // enable responsive behaviour for all devices ?>
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<?php // Windows 8 start screen tile ?>
	<meta name="msapplication-TileColor" content="#ffffff"/>
	<meta name="msapplication-TileImage" content="apple-touch-icon-152x152-precomposed.png"/>

	<link rel="SHORTCUT ICON" href="<?php echo THEME_URI; ?>/img/favicon.ico" type="image/x-icon" />
	<link rel="logo" type="image/svg" href="<?php echo THEME_URI; ?>/img/logo.svg"/>
	<!--[if ! lte IE 7]><!-->
	<link rel="stylesheet" href="<?php bloginfo('template_url'); ?>/style.min.css" />
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
