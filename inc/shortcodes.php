<?php

/*
 * Name: mp_shortcode_loop
 *
 * WORDPRESS SHORTCODE
 * - Creates a wp_query from the query attribute
 *   and inserts html from a template file ($template[-{$name}]?.php)
 *
 * @param $atts ARRAY (name, template, query)
 * @param $content STRING (Optional) Content is ignored
 * @return STRING evaluated content of template file
 */
function mp_shortcode_loop($atts, $content = null) {
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
} add_shortcode('loop', 'mp_shortcode_loop');

/*
 * Name: mp_shortcode_htmltag
 *
 * WORDPRESS SHORTCODE
 * - Inserts an html tag
 *
 * @param $atts ARRAY (tag, [any other attribute for the tag])
 * @param $content STRING (Optional) Content to be included in the tag
 * @return STRING Html tag
 */
function mp_shortcode_htmltag($args, $content = null) {
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
} add_shortcode('htmltag', 'mp_shortcode_htmltag');
