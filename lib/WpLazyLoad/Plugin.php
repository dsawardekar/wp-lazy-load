<?php

namespace WpLazyLoad;

use Encase\Container;
use Arrow\AssetManager\AssetManager;

class Plugin {

  static $instance = null;
  static function create($file) {
    if (is_null(self::$instance)) {
      self::$instance = new Plugin($file);
    }

    return self::$instance;
  }

  static function getInstance() {
    return self::$instance;
  }

  public $container;

  function __construct($file) {
    $this->container = new Container();
    $this->container
      ->object('pluginMeta', new PluginMeta($file))
      ->object('assetManager', new AssetManager($this->container))
      ->object('optionsManager', new OptionsManager($this->container));
  }

  function enable() {
    add_action('admin_init', array($this, 'initAdmin'));
    add_action('admin_menu', array($this, 'initAdminMenu'));
    add_action('init', array($this, 'initFrontEnd'));
  }

  function lookup($key) {
    return $this->container->lookup($key);
  }

  function initAdmin() {
    $this->lookup('optionsPostHandler')->enable();
  }

  function initAdminMenu() {
    $this->lookup('optionsPage')->register();
  }

  function initFrontEnd() {

  }

}
