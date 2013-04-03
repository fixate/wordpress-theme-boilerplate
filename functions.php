<?php

define( 'THEME_DIR', get_template_directory_uri() );
define( 'THEME_NAME_THEME_BASEPATH', str_replace('\\', '/', dirname(__FILE__)) );
define( 'GA_UACODE', false );

// Add theme support - must come before init() hook
if ( function_exists( 'add_theme_support' ) ) {
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'nav_menus' );
	add_theme_support( 'automatic_feed_links' );
}

# Include helper classes
// require_once 'inc/utils.php';
# Include custom widgets
// require_once 'inc/widgets.php';
# Include shortcodes
// require_once 'inc/shortcodes.php';
# Include if using the contact form
require_once 'inc/validation.php';
require_once 'inc/mailman.php';
Mailman::wp_bootstrap(array(
	'to' => array('stan@fixate.it', 'your name'),	
	'templates' => array(
		'html' => THEME_NAME_THEME_BASEPATH.'/inc/templates/contact.html.php',
		'text' => THEME_NAME_THEME_BASEPATH.'/inc/templates/contact.text.php',
	),
	'success_message' => "Your message has been sent! You'll hear back from us soon.",
	'validation_messages' => array(
		'Validation::presence' => '%s cannot be blank.',
		'Validation::email' => '%s must be a valid email address.',
	),
	'validates' => array(
		'mail_name' => array('Validation::presence'),
		'mail_email' => array('Validation::presence', 'Validation::email'),
		'mail_message' =>  array('Validation::presence' => 'Please enter a message.'),
	)
));


// Conditionally include admin functions
if (is_admin()) {	
	require_once 'inc/wp-admin/functions.php';	
}

/* *****************************************************************************
	 &actions
	 ************************************************************************** */

/**
 * Initialise scripts site-wide
 *
 * All scripts are registered here, but must be specifically enqueued where they
 * are required via theme_name_enqueue_scripts_styles()
 *
 * @since Theme Name 1.0
 */
function theme_name_init() {
	// If in the admin, nonet of the code below applies
	if (is_admin())
		return;

	// Register Scripts
	wp_deregister_script( 'jquery' );
	wp_deregister_script( 'comment-reply' );
	wp_register_script( 'jquery', 'http://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js', false, false, true );
	wp_register_script( 'comment-reply', ' /wp-includes/js/comment-reply.min.js', array( 'jquery' ), false, true );

} add_action( 'init', 'theme_name_init' );

/**
 * Enqueue scripts and styles for front-end.
 *
 * @since Theme Name 1.0
 */
function theme_name_enqueue_scripts_styles() {
	global $wp_styles;

	/*
	 * Add JavaScript to pages with the comment form to support sites with
	 * threaded comments (when in use).
	 */
	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) )
		wp_enqueue_script( 'comment-reply', '', '', '', 'true' );

	wp_enqueue_script( 'jquery' );

} add_action( 'wp_enqueue_scripts', 'theme_name_enqueue_scripts_styles' );

/**
 * Remove junk from head
 *
 * @since Theme Name 1.0
 */
remove_action('wp_head', 'rsd_link');
remove_action('wp_head', 'wp_generator');
remove_action('wp_head', 'feed_links', 2);
remove_action('wp_head', 'index_rel_link');
remove_action('wp_head', 'wlwmanifest_link');
remove_action('wp_head', 'feed_links_extra', 3);
remove_action('wp_head', 'start_post_rel_link', 10, 0);
remove_action('wp_head', 'parent_post_rel_link', 10, 0);
remove_action('wp_head', 'adjacent_posts_rel_link_wp_head');


/* *****************************************************************************
	 &functions
	 ************************************************************************** */

/**
 * Fallback for primary nav
 *
 * Used as a callback by wp_nav_menu() when no menu is created in the admin.
 *
 * @since Theme Name 1.0
 */
if ( ! function_exists( 'default_primary_nav' ) ) {
	function default_primary_nav() {
		echo '<ul id="primary-menu" class="primary-menu">';
		wp_list_pages('title_li=');
		echo '</ul>';
	}
}


if ( ! function_exists( 'theme_name_comment' ) ) {
	/**
	 * Template for comments and pingbacks.
	 *
	 * To override this walker in a child theme without modifying the comments template
	 * simply create your own theme_name_comment(), and that function will be used instead.
	 *
	 * Used as a callback by wp_list_comments() for displaying the comments.
	 *
	 * @since Theme Name 1.0
	 */
	function theme_name_comment( $comment, $args, $depth ) {
		$GLOBALS['comment'] = $comment;
		include 'comment-single.php';
	}
}

/**
 * Customise post excerpts
 *
 * @since Theme Name 1.0
 */
function theme_name_custom_excerpt($limit) {
	$excerpt = explode(' ', get_the_excerpt(), $limit);
	if (count($excerpt) >= $limit) {
		array_pop($excerpt);
		$excerpt = implode(" ",$excerpt).'...';
	} else {
		$excerpt = implode(" ",$excerpt);
	}
	$excerpt = preg_replace('`\[[^\]]*\]`','',$excerpt);
	return $excerpt;
}


/**
 * Output paginated links
 *
 * @since Theme Name 1.0
 */
function theme_name_paginate_links($total = -1) {
	global $wp_query;
	if ($total < 0)
		$total = $wp_query->max_num_pages;

	// check to see if there is more than one page
	if ($total <= 1)
		return;

	echo '<div class="paginate">';

	$current = max($wp_query->query_vars['paged'],  1);

	$pagination = array(
		'base'         => '%_%',
		'format'       => '?page=%#%',
		'total'        => $total,
		'current'      => $current,
		'show_all'     => false,
		'end_size'     => 1,
		'mid_size'     => 3,
		'prev_next'    => true,
		'prev_text'    => __('Newer'),
		'next_text'    => __('Older'),
		'type'         => 'plain',
		'add_args'     => false,
		'add_fragment' => ''
	);

	// enable proper link generation of page links if using permalinks
	global $wp_rewrite;
	if ($wp_rewrite->using_permalinks())
		$pagination['base'] = user_trailingslashit(
			trailingslashit(remove_query_arg('s', get_pagenum_link(1))) . 'page/%#%/',
			'paged');

	// append search query to page links
	if (!empty($wp_query->query_vars['s']))
		$pagination['add_args'] = array('s' => get_query_var('s'));

	echo paginate_links($pagination);
	echo '</div>';
}


/**
 * Add Google Analytics to footer
 *
 * @since Theme Name 1.0
 */
if  (GA_UACODE !== false):
	function theme_name_add_google_analytics() {
	 ?>
<script type="text/javascript">//<![CDATA[
	var _gaq = _gaq || [];
	_gaq.push(['_setAccount', '<?php echo GA_UACODE; ?>']);
	_gaq.push(['_trackPageview']);
	_gaq.push(['_trackPageLoadTime']);

	(function() {
	var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
	ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
	var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
	})();
//]]></script>
<?php
} add_action('wp_footer', 'theme_name_add_google_analytics', 12);
endif;


/**
 * Mailer Functionality
 *
 * @since Theme Name 1.0
 */
/*function theme_name_pre_get_posts($request) {
	$query_vars = &$request->query_vars;

	if ($query_vars['pagename'] == 'contact') {
		require_once (theme_name_THEME_BASEPATH.'/inc/mailman.php');

		if (Mailman::has_post()) {
			$query_vars['mailman_pat'] = Mailman::delivers($_POST)
				->with(array('subject' => 'Pembury - Request from %s'))
				->to(get_bloginfo('admin_email'));
		} else {
			$query_vars['mailman_pat'] = Mailman::no_work();
		}
	}
} add_action('pre_get_posts', 'theme_name_pre_get_posts');*/


/* *****************************************************************************
	 &filters
	 ************************************************************************** */

/**
 * Disable WordPress outputting default gallery styles
 *
 * @since Theme Name 1.0
 */
add_filter('use_default_gallery_style', '__return_false');


/**
 * Wrap embeds in flexible wrappers
 *
 * @since Theme Name 1.0
 */
function theme_name_embed_filter( $output, $data, $url ) {
	$return = '<div class="flex-video">'.$output.'</div>';
	return $return;
} add_filter('oembed_dataparse', 'theme_name_embed_filter', 90, 3 );


/* *****************************************************************************
	 &post types
	 ************************************************************************** */

/**
 * Register new post types
 *
 * This is most safely done in a theme specific plugin so that new themes will
 * preserve data added to these posts.
 *
 * @since Theme Name 1.0
 */
/*function theme_name_register_post_types() {
	register_post_type('[posttype]', array(
		'labels' => array(
			'name'               => __('[posttype]', ''),
			'singular_name'      => __('[posttype]', ''),
			'add_new'            => __('Add New', ''),
			'add_new_item'       => __('Add new entry', ''),
			'edit_item'          => __('Edit entry', ''),
			'new_item'           => __('New entry', ''),
			'view_item'          => __('View entry', ''),
			'search_items'       => __('Search [posttype]', ''),
			'not_found'          => __('No entries found', ''),
			'not_found_in_trash' => __('No entries found in Trash', ''),
		),
		'description' => '[posttype]',
		'public'      => true,
		'supports'    => array('title', 'thumbnail', 'editor'),
		'taxonomies'  => array('category'),
		'has_archive' => true,
	));
} add_action('init', 'theme_name_register_post_types');*/


/* *****************************************************************************
	 &menus
	 ************************************************************************** */

/**
 * Register nav menus
 *
 * @since Theme Name 1.0
 */
if (function_exists('register_nav_menus')) {
	register_nav_menus( array(
		'primary-menu' => __( 'Primary Navigation', 'theme_name' ),
		'footer-menu' => __( 'Footer Navigation', 'theme_name' ),
	) );
}


/* *****************************************************************************
	 &widgets
	 ************************************************************************** */

/**
 * Register widgets
 *
 * @since Theme Name 1.0
 */
/*if (function_exists('register_sidebar')) {
	register_sidebar(
		array(
			'id'            => 'sidebar-top',
			'name'          => __( 'Sidebar Top', 'theme_name' ),
			'before_widget' => '<li id="%1$s" class="widget-container %2$s">',
			'after_widget'  => '<div class="hr"></div>',
			'before_title'  => '<h3 class="widget-title">',
			'after_title'   => '</h3>',
		));
}*/