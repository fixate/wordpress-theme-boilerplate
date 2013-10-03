<?php

define('THEME_URI', get_template_directory_uri());
define('THEME_BASEPATH', str_replace('\\', '/', dirname(__FILE__)));
define('GA_UACODE', false);

// Add theme support - must come before init() hook
if (function_exists('add_theme_support')) {
	add_theme_support('post-thumbnails');
	add_theme_support('nav_menus');
	add_theme_support('automatic_feed_links');
}

# Include helper classes
// require_once 'inc/utils.php';
# Include custom widgets
// require_once 'inc/widgets.php';
# Include shortcodes
// require_once 'inc/shortcodes.php';

# Include if using the contact form
/*require_once 'inc/validation.php';
require_once 'inc/mailman.php';
Mailman::wp_bootstrap(array(
	'to' => array('your@email.com', 'your name'),
	'from_fields' => array('mail_email', 'mail_name'),
	'templates' => array(
		'html' => THEME_BASEPATH.'/inc/templates/contact.html.php',
		'text' => THEME_BASEPATH.'/inc/templates/contact.text.php',
	),
	'success_message' => __('Your message has been sent! You\'ll hear back from us soon.', 'theme_text_domain'),
	'validation_messages' => array(
		'Validation::presence' => '%s cannot be blank.',
		'Validation::email' => '%s must be a valid email address.',
	),
	'validates' => array(// ▾ Friendly field name (optional)
		'mail_name' => array('@Name', 'Validation::presence'),
		'mail_email' => array('@Email', 'Validation::presence', 'Validation::email'),
		'mail_message' => array('Validation::presence' => 'Please enter a message.'),
	),
	// If using SMTP delivery method
	// 'smtp' => array(
	// 	'host' => 'your.isp.net',
	// 	'port' => 25,
	// 	'auth' => true, // if false or omitted, leave out the following
	// 	'username' => 'your@username',
	// 	'password' => 'you know what to do...'
	//)
));*/


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
 * are required via theme_fn_prefix_enqueue_scripts_styles()
 *
 * @since Theme_Name 1.0
 */
function theme_fn_prefix_init() {
	// If in the admin, none of the code below applies
	if (is_admin())
		return;

	// Register Scripts
	wp_deregister_script('jquery');
	wp_deregister_script('comment-reply');
	wp_register_script('jquery', 'http://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js', false, false, true);
	wp_register_script('comment-reply', ' /wp-includes/js/comment-reply.min.js', array('jquery'), false, true);

  if (defined('WP_LOCAL_DEV') && WP_LOCAL_DEV === true) {
    // define unminified scripts here
  } else {
    // define minified / production scripts here
  }

} add_action('init', 'theme_fn_prefix_init');


/**
 * Enqueue scripts and styles for front-end.
 *
 * @since Theme_Name 1.0
 */
function theme_fn_prefix_enqueue_scripts_styles() {
	global $wp_styles;

	/*
	 * Add JavaScript to pages with the comment form to support sites with
	 * threaded comments (when in use).
	 */
	if (is_singular() && comments_open() && get_option('thread_comments'))
		wp_enqueue_script('comment-reply', '', '', '', 'true');

	wp_enqueue_script('jquery');

} add_action('wp_enqueue_scripts', 'theme_fn_prefix_enqueue_scripts_styles');


/**
 * Remove junk from head
 *
 * @since Theme_Name 1.0
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


/**
 * Add Theme_Name avatar
 *
 * @since Theme_Name 1.0
 */
// function theme_fn_prefix_add_gravatar($avatar_defaults) {
// 	$avatar = THEME_URI . '/img/avatar.png';
// 	$avatar_defaults[$avatar] = 'Theme_Name';

// 	return $avatar_defaults;
// } add_filter('avatar_defaults', 'theme_fn_prefix_add_gravatar');





/* *****************************************************************************
	 &functions
	 ************************************************************************** */

/**
 * Fallback for primary nav
 *
 * Used as a callback by wp_nav_menu() when no menu is created in the admin.
 *
 * @since Theme_Name 1.0
 */
if (! function_exists('default_primary_menu')) {
	function default_primary_menu() {
		echo '<ul class="menu-primary">';
		wp_list_pages('title_li=');
		echo '</ul>';
	}
}


if (! function_exists('theme_fn_prefix_comment')) {
	/**
	 * Template for comments and pingbacks.
	 *
	 * To override this walker in a child theme without modifying the comments template
	 * simply create your own theme_fn_prefix_comment(), and that function will be used instead.
	 *
	 * Used as a callback by wp_list_comments() for displaying the comments.
	 *
	 * @since Theme_Name 1.0
	 */
	function theme_fn_prefix_comment($comment, $args, $depth) {
		$GLOBALS['comment'] = $comment;
		include 'partials/comment-single.php';
	}
}


/**
 * Customise post excerpts
 *
 * @since Theme_Name 1.0
 */
function theme_fn_prefix_custom_excerpt($limit) {
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
 * @since Theme_Name 1.0
 */
function theme_fn_prefix_paginate_links($total = -1) {
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
 * @since Theme_Name 1.0
 */
if  (defined('WP_LOCAL_DEV') && WP_LOCAL_DEV === true && GA_UACODE !== false):
	function theme_fn_prefix_add_google_analytics() {
	 ?>
<script>
	var _gaq = _gaq || [];
	_gaq.push(['_setAccount', '<?php echo GA_UACODE; ?>']);
	_gaq.push(['_trackPageview']);
	(function() {
		var ga = document.createElement('script');
		ga.type = 'text/javascript';
		ga.async = true;
		ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
		var s = document.getElementsByTagName('script')[0];
		s.parentNode.insertBefore(ga, s);
	})();
</script>
<?php
} add_action('wp_footer', 'theme_fn_prefix_add_google_analytics', 12);
endif;




/**
 * Mailer Functionality
 *
 * @since Theme_Name 1.0
 */
/*function theme_fn_prefix_pre_get_posts($request) {
	$query_vars = &$request->query_vars;

	if ($query_vars['pagename'] == 'contact') {
		require_once (THEME_BASEPATH.'/inc/mailman.php');

		if (Mailman::has_post()) {
			$query_vars['mailman_pat'] = Mailman::delivers($_POST)
				->with(array('subject' => 'Pembury - Request from %s'))
				->to(get_bloginfo('admin_email'));
		} else {
			$query_vars['mailman_pat'] = Mailman::no_work();
		}
	}
} add_action('pre_get_posts', 'theme_fn_prefix_pre_get_posts');*/





/* *****************************************************************************
	 &filters
	 ************************************************************************** */

/**
 * Disable WordPress outputting default gallery styles
 *
 * @since Theme_Name 1.0
 */
add_filter('use_default_gallery_style', '__return_false');


/**
 * Wrap embeds in flexible wrappers. Requires classes for flex-video:
 * http://mopo.ws/TuyaHd
 *
 * @since Theme_Name 1.0
 */
function theme_fn_prefix_embed_filter($output, $data, $url) {
	$return = '<div class="flex-video">'.$output.'</div>';
	return $return;
} add_filter('oembed_dataparse', 'theme_fn_prefix_embed_filter', 90, 3);


/**
 * Replace default class on comment_reply_link
 * @param  string $class The string containing the entire class attribute
 * @return string        The modified class attribute
 *
 * @since Theme_Name 1.0
 */
function theme_fn_prefix_reply_link_class($class){
	$class = str_replace("class='comment-reply-link'", "class='btn'", $class);
	return $class;
} add_filter('comment_reply_link', 'theme_fn_prefix_reply_link_class');


/**
 * remove query strings from scripts and styles
 * @param  array $src array of scripts and styles
 * @return array
 */
function theme_fn_prefix_remove_script_version($src)
{
	$parts = explode('?ver', $src);
	return $parts[0];
}
add_filter('script_loader_src', 'theme_fn_prefix_remove_script_version', 15, 1);
add_filter('style_loader_src', 'theme_fn_prefix_remove_script_version', 15, 1);





/* *****************************************************************************
	 &post types
	 ************************************************************************** */

/**
 * Register new post types
 *
 * This is most safely done in a theme specific plugin so that new themes will
 * preserve data added to these posts.
 *
 * @since Theme_Name 1.0
 */
/*function theme_fn_prefix_register_post_types() {
	register_post_type('[posttype]', array(
		'labels' => array(
			'name'               => __('[posttype]s', ''),
			'singular_name'      => __('[posttype]', ''),
			'all_items'          => __('All Items', ''),
			'add_new'            => __('Add New', ''),
			'add_new_item'       => __('Add new [posttype]', ''),
			'edit_item'          => __('Edit [posttype]', ''),
			'new_item'           => __('New [posttype]', ''),
			'view_item'          => __('View [posttype]', ''),
			'search_items'       => __('Search [posttype]s', ''),
			'not_found'          => __('No [posttype]s found', ''),
			'not_found_in_trash' => __('No [posttype]s found in Trash', ''),
		),
		'description' => '[posttype]',
		'public'      => true,
		'supports'    => array('title', 'thumbnail', 'editor'),
		'taxonomies'  => array('category'),
		'has_archive' => true,
	));
} add_action('init', 'theme_fn_prefix_register_post_types');*/





/* *****************************************************************************
	 &taxonomies
	 ************************************************************************** */
/*function theme_fn_prefix_add_custom_taxonomies() {
	register_taxonomy('[taxonomy]', '[post_type]', array(
		'hierarchical'				=> true,
		'labels'							=> array(
			'name'							=> _x('[taxonomy_plural]', 'taxonomy general name'),
			'singular_name'			=> _x('[taxonomy_singular]', 'taxonomy singular name'),
			'search_items'			=>  __('Search [taxonomy_plural]'),
			'all_items'					=> __('All [taxonomy_plural]'),
			'parent_item'				=> __('Parent [taxonomy_singular]'),
			'parent_item_colon'	=> __('Parent [taxonomy_singular]:'),
			'edit_item'					=> __('Edit [taxonomy_singular]'),
			'update_item'				=> __('Update [taxonomy_singular]'),
			'add_new_item'			=> __('Add New [taxonomy_singular]'),
			'new_item_name'			=> __('New [taxonomy_singular] Name'),
			'menu_name'					=> __('[taxonomy_plural]'),
		),
		'rewrite'					=> array(
			'slug'					=> '[taxonomy]',
			'with_front'		=> false,
			'hierarchical'	=> true
		),
	));
} add_action('init', 'theme_fn_prefix_add_custom_taxonomies', 0);

function theme_fn_prefix_add_taxonomy_to_cpt(){
	register_taxonomy_for_object_type('[taxonomy]', '[post_type]');
} add_action('init','theme_fn_prefix_add_taxonomy_to_cpt');*/





/* *****************************************************************************
	 &menus
	 ************************************************************************** */

/**
 * Register nav menus
 *
 * @since Theme_Name 1.0
 */
if (function_exists('register_nav_menus')) {
	register_nav_menus(array(
		'menu-primary' => __('Primary Navigation', 'theme_text_domain'),
		'menu-footer' => __('Footer Navigation', 'theme_text_domain'),
	));
}
