<?php

namespace WpLazyLoad;

use Arrow\AssetManager\AssetManager;

class Plugin extends \Arrow\Plugin {

  function __construct($file) {
    parent::__construct($file);

    $this->container
      ->object('pluginMeta', new PluginMeta($file))
      ->object('assetManager', new AssetManager($this->container))
      ->object('optionsManager', new OptionsManager($this->container))
      ->singleton('imageSourceReplacer', 'WpLazyLoad\ImageSourceReplacer')
      ->singleton('scriptPlacer', 'WpLazyLoad\ScriptPlacer')
      ->singleton('contentReplacer', 'WpLazyLoad\ContentReplacer');
  }

  function enable() {
    add_action('admin_init', array($this, 'initAdmin'));
    add_action('admin_menu', array($this, 'initAdminMenu'));
    add_action('init', array($this, 'initFrontEnd'));
  }

  function initAdmin() {
    $this->lookup('optionsPostHandler')->enable();
  }

  function initAdminMenu() {
    $this->lookup('optionsPage')->register();
  }

  function initFrontEnd() {
    $contentReplacer = $this->lookup('contentReplacer');

    add_action('the_content', array($contentReplacer, 'replace'), 99);
  }

}
