<?php

// Theme functions
get_template_part('functions/core');
get_template_part('functions/setting');
get_template_part('functions/template');
get_template_part('functions/widget');

//Reomve unnecessary header
remove_action('wp_head', 'rsd_link');
remove_action('wp_head', 'wlwmanifest_link');
remove_action('wp_head', 'wp_generator');
remove_action('wp_head', 'start_post_rel_link');
remove_action('wp_head', 'index_rel_link');
remove_action('wp_head', 'adjacent_posts_rel_link');

// Remove default Emoji
remove_action('admin_print_scripts', 'print_emoji_detection_script');
remove_action('admin_print_styles', 'print_emoji_styles');
remove_action('wp_head', 'print_emoji_detection_script', 7);
remove_action('wp_print_styles', 'print_emoji_styles');
remove_filter('the_content_feed', 'wp_staticize_emoji');
remove_filter('comment_text_rss', 'wp_staticize_emoji');
remove_filter('wp_mail', 'wp_staticize_emoji_for_email');

// Disable post revision
define('WP_POST_REVISIONS', false);

// Disable xml rpc
add_filter('xmlrpc_enabled', '__return_false');

// Disable wordpress rest api
add_filter('json_enabled', '__return_false');
add_filter('json_jsonp_enabled', '__return_false');
remove_action( 'wp_head', 'rest_output_link_wp_head', 10 );
remove_action( 'wp_head', 'wp_oembed_add_discovery_links', 10 );

//Remove google fonts
function da07ng_remove_open_sans()
{
  wp_deregister_style('open-sans');
  wp_register_style('open-sans', false);
  wp_enqueue_style('open-sans', '');
}
add_action('init', 'da07ng_remove_open_sans');

//Remove jQuery & wp-embed
function da07ng_remove_scripts()
{
  wp_deregister_script('jquery');
  wp_deregister_script('wp-embed');
}
add_action('init', 'da07ng_remove_scripts');

// Add post thumbnail
add_theme_support('post-thumbnails');

// Register menu
register_nav_menus(array(
  'top-menu' => __('Top menu', DA07NG_THEME_NAME),
  'foot-menu' => __('Foot menu', DA07NG_THEME_NAME)
));

// Register sidebar
if (function_exists('register_sidebar')) {
  register_sidebar(array(
    'name' => 'sidebar',
    'before_widget' => '<div class="widget">',
    'after_widget' => '</div>',
    'before_title' => '<h3>',
    'after_title' => '</h3>'
  ));
}

// Enqueue theme css and javascript file
function da07ng_script()
{
  wp_enqueue_style('style', DA07NG_THEME_DIRECTORY . '/public/dist/main.min.css', array(), DA07NG_THEME_VERSION, 'screen');
  wp_enqueue_script('script', DA07NG_THEME_DIRECTORY . '/public/dist/main.js', array(), DA07NG_THEME_VERSION, false);
}
add_action('wp_enqueue_scripts', 'da07ng_script');

?>
