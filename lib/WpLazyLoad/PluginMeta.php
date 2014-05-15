<?php

namespace WpLazyLoad;

class PluginMeta extends \Arrow\PluginMeta {

  function getVersion() {
    return Version::$version;
  }

  function getDefaultOptions() {
    return array(
      'threshold' => 200,
      'effect' => 'fade',
      'skipInvisible' => true,
      'placeholder' => ''
    );
  }

  function getEffectTypes() {
    return array('fade', 'show', 'none');
  }

}
