<?php
/*
Plugin Name: wp-lazy-load
Description: Lazy Load images using jQuery Lazy Load.
Version: 0.3.1
Author: Darshan Sawardekar
Author URI: http://pressing-matters.io/
Plugin URI: http://wordpress.org/plugins/wp-lazy-load
License: GPLv2
*/

require_once(__DIR__ . '/vendor/dsawardekar/arrow/lib/Arrow/ArrowPluginLoader.php');

function wp_lazy_load_main() {
  $options = array(
    'plugin' => 'WpLazyLoad\Plugin',
    'arrowVersion' => '1.8.0'
  );

  ArrowPluginLoader::load(__FILE__, $options);
}

wp_lazy_load_main();
