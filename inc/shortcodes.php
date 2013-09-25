<?php

/**
 * creates a wp_query from the query attribute, and inserts html from a template file
 * @param  array $atts    attributes of the shortcode
 * @param  string $content [ignored]
 * @return string          the resulting markup
 */
function theme_local_shortcode_loop($atts, $content = null) {
	extract(shortcode_atts(
						array(
							'name' => '',
							'template' => 'loop',
							'query' => 'post_type=post'
					 ), $atts));

	global $wp_query;
	$old_wp = $wp_query;

	$wp_query = new WP_Query($query);

	ob_start();
	get_template_part($template, $name);

	$wp_query = $old_wp;
	return ob_get_clean();
} add_shortcode('loop', 'theme_local_shortcode_loop');

/**
 * write html tag as shortcode in editor
 * @param  array $args    	arguments used in shortcode
 * @param  string $content 	the content of the shortcode
 * @return string          	the resulting markup
 */
function theme_local_shortcode_htmltag($args, $content = null) {
	if (!$tag = $args['tag'])
		return $content;
	unset($args['tag']);

	$attrs = '';
	if ($args && is_array($args)) {
		foreach ($args as $k => $v) {
			$attrs .= " {$k}=\"{$v}\"";
		}
	}
	return sprintf('<%1$s %2$s>%3$s</%1$s>', $tag, $attrs, do_shortcode($content));
} add_shortcode('htmltag', 'theme_local_shortcode_htmltag');
