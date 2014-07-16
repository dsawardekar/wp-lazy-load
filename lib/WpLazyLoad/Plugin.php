<?php

namespace WpLazyLoad;

use Arrow\AssetManager\AssetManager;

class Plugin extends \Arrow\Plugin {

  function __construct($file) {
    parent::__construct($file);

    $this->container
      ->object('pluginMeta', new PluginMeta($file))
      ->packager('optionsPackager', 'Arrow\Options\Packager')
      ->singleton('imageSourceReplacer', 'WpLazyLoad\ImageSourceReplacer')
      ->singleton('scriptPlacer', 'WpLazyLoad\ScriptPlacer')
      ->singleton('contentReplacer', 'WpLazyLoad\ContentReplacer');
  }

  function enable() {
    add_action('init', array($this, 'initFrontEnd'));
  }

  function initFrontEnd() {
    $contentReplacer = $this->lookup('contentReplacer');

    add_action('the_content', array($contentReplacer, 'replace'), 99);
  }

}
