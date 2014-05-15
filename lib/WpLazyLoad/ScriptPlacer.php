<?php

namespace WpLazyLoad;

class ScriptPlacer {

  public $enabled = false;

  function needs() {
    return array('pluginMeta', 'scriptLoader', 'optionsStore');
  }

  function enable() {
    if ($this->enabled) {
      return;
    }

    $options = array('dependencies' => 'jquery');
    $this->scriptLoader->stream('jquery-lazy-load', $options);

    $options = array(
      'dependencies' => 'jquery-lazy-load',
      'localizer' => array($this, 'getPluginOptions')
    );
    $this->scriptLoader->stream('wp-lazy-load-options', $options);

    $this->enabled = true;
  }

  function getPluginOptions($script) {
    $options = $this->optionsStore->getOptions();

    if ($options['placeholder'] !== '') {
      $path  = get_stylesheet_directory_uri();
      $path .= '/' . $this->pluginMeta->getSlug();
      $path .= '/' . $options['placeholder'];

      $options['placeholder'] = $path;
    }

    return $options;
  }

}
