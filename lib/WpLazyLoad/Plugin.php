<?php

namespace WpLazyLoad;

use Arrow\AssetManager\AssetManager;

class Plugin extends \Arrow\Plugin {

  function __construct($file) {
    parent::__construct($file);

    $this->container
      ->object('pluginMeta', new PluginMeta($file))
      ->packager('optionsPackager', 'Arrow\Options\Packager')
      ->singleton('optionsController', 'WpLazyLoad\OptionsController')
      ->singleton('imageSourceReplacer', 'WpLazyLoad\ImageSourceReplacer')
      ->singleton('scriptPlacer', 'WpLazyLoad\ScriptPlacer');
  }

  function enable() {
    add_action('init', array($this, 'initFrontEnd'));
  }

  function initFrontEnd() {
    $this->lookup('imageSourceReplacer')->enable();
  }

}
