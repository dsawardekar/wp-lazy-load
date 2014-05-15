<?php
/*
Plugin Name: wp-lazy-load
Description: Lazy Load images using jQuery Lazy Load.
Version: 0.1.1
Author: Darshan Sawardekar
Author URI: http://pressing-matters.io/
Plugin URI: http://wordpress.org/plugins/wp-lazy-load
License: GPLv2
*/

require_once(__DIR__ . '/vendor/dsawardekar/wp-requirements/lib/Requirements.php');

function wp_lazy_load_main() {
  $requirements = new WP_Requirements();

  if ($requirements->satisfied()) {
    wp_lazy_load_register();
  } else {
    $plugin = new WP_Faux_Plugin('WP Lazy Load', $requirements->getResults());
    $plugin->activate(__FILE__);
  }
}

function wp_lazy_load_register() {
  require_once(__DIR__ . '/vendor/dsawardekar/arrow/lib/Arrow/ArrowPluginLoader.php');

  $loader = ArrowPluginLoader::getInstance();
  $loader->register(__FILE__, '0.5.1', 'wp_lazy_load_load');
}

function wp_lazy_load_load() {
  $file = __FILE__;
  require_once(__DIR__ . '/wp-lazy-load-load.php');
}

wp_lazy_load_main();
